<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\Tests;

use ArrayObject;
use BestIt\CommercetoolsODM\ActionBuilder\ActionBuilderProcessorInterface;
use BestIt\CommercetoolsODM\ActionBuilderProcessorAwareTrait;
use BestIt\CommercetoolsODM\ClientAwareTrait;
use BestIt\CommercetoolsODM\DocumentManager;
use BestIt\CommercetoolsODM\DocumentManagerInterface;
use BestIt\CommercetoolsODM\Event\LifecycleEventArgs;
use BestIt\CommercetoolsODM\Event\ListenersInvoker;
use BestIt\CommercetoolsODM\Events;
use BestIt\CommercetoolsODM\Exception\ConnectException as OdmConnectException;
use BestIt\CommercetoolsODM\Exception\NotFoundException;
use BestIt\CommercetoolsODM\Helper\EventManagerAwareTrait;
use BestIt\CommercetoolsODM\Helper\ListenerInvokerAwareTrait;
use BestIt\CommercetoolsODM\Mapping\Annotations\Field;
use BestIt\CommercetoolsODM\Mapping\ClassMetadata;
use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use BestIt\CommercetoolsODM\Tests\UnitOfWork\TestCustomEntity;
use BestIt\CommercetoolsODM\UnitOfWork;
use BestIt\CommercetoolsODM\UnitOfWorkInterface;
use Commercetools\Core\Model\Cart\LineItem;
use Commercetools\Core\Model\Category\CategoryReference;
use Commercetools\Core\Model\Common\Address;
use Commercetools\Core\Model\Common\AddressCollection;
use Commercetools\Core\Model\Common\Attribute;
use Commercetools\Core\Model\Common\JsonObject;
use Commercetools\Core\Model\Common\LocalizedString;
use Commercetools\Core\Model\Common\Money;
use Commercetools\Core\Model\CustomObject\CustomObject;
use Commercetools\Core\Model\Customer\Customer;
use Commercetools\Core\Model\Order\Order;
use Commercetools\Core\Model\Product\Product;
use Commercetools\Core\Model\Product\ProductCatalogData;
use Commercetools\Core\Model\Product\ProductData;
use Commercetools\Core\Model\Product\ProductDraft;
use Commercetools\Core\Model\Product\ProductVariant;
use Commercetools\Core\Model\ProductType\ProductType;
use Commercetools\Core\Model\ProductType\ProductTypeDraft;
use Commercetools\Core\Model\ProductType\ProductTypeReference;
use Commercetools\Core\Model\TaxCategory\TaxCategoryReference;
use Commercetools\Core\Request\Orders\OrderDeleteRequest;
use Commercetools\Core\Request\ProductTypes\ProductTypeCreateRequest;
use DateTime;
use Doctrine\Common\EventManager;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use Psr\Http\Message\RequestInterface;
use Psr\Log\LoggerAwareTrait;
use ReflectionClass;
use RuntimeException;
use function uniqid;

/**
 * Class UnitOfWorkTest
 *
 * @author blange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\Tests
 */
class UnitOfWorkTest extends TestCase
{
    use TestClientTrait;
    use TestTraitsTrait;

    /**
     * The used document manager.
     *
     * @var ActionBuilderProcessorInterface|PHPUnit_Framework_MockObject_MockObject
     */
    private $actionBuilderProcessor = null;

    /**
     * The used document manager.
     *
     * @var DocumentManagerInterface|PHPUnit_Framework_MockObject_MockObject
     */
    private $documentManager = null;

    /**
     * The fixture.
     *
     * @var UnitOfWork
     */
    protected $fixture = null;

    /**
     * The used document manager.
     *
     * @var ListenersInvoker|PHPUnit_Framework_MockObject_MockObject
     */
    private $listenerInvoker = null;

    /**
     * Adds a mocked call for metadata for the given model.
     *
     * @param string $model
     * @param bool|int $once If true then just once, if false then any, if integer the exact count.
     *
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    private function getOneMockedMetadata(string $model, $once = true): PHPUnit_Framework_MockObject_MockObject
    {
        $expects = $this->once();

        if ($once !== true) {
            if ($once === false) {
                $expects = $this->any();
            } else {
                $expects = $this->exactly($once);
            }
        }

        $this->documentManager
            ->expects($expects)
            ->method('getClassMetadata')
            ->with($model)
            ->willReturn($classMetadata = $this->createMock(ClassMetadataInterface::class));

        $classMetadata
            ->method('getName')
            ->willReturn($model);

        $classMetadata
            ->method('isCTStandardModel')
            ->willReturn(is_a($model, JsonObject::class, true));

        return $classMetadata;
    }

    /**
     * Returns the used traits.
     *
     * @return array
     */
    public static function getUsedTraitNames(): array
    {
        return [
            ActionBuilderProcessorAwareTrait::class,
            ClientAwareTrait::class,
            EventManagerAwareTrait::class,
            ListenerInvokerAwareTrait::class,
            LoggerAwareTrait::class
        ];
    }

    /**
     * Mocks an listener invoker call for the given object.
     *
     * @param object $order
     * @param string $lifeCycleEventName
     * @param ClassMetadataInterface $orderMetadata
     *
     * @return UnitOfWorkTest
     */
    private function mockAndCheckInvokerCall(
        $order,
        string $lifeCycleEventName,
        ClassMetadataInterface $orderMetadata,
        $expected = 'any'
    ): UnitOfWorkTest {
        $this->listenerInvoker
            ->expects(is_string($expected) ? $this->$expected() : $this->at($expected))
            ->method('invoke')
            ->with(
                $this->callback(function (LifecycleEventArgs $eventArgs) use ($order) {
                    static::assertSame($order, $eventArgs->getDocument(), 'Wrong object in event.');

                    static::assertSame(
                        $this->documentManager,
                        $eventArgs->getDocumentManager(),
                        'Wrong object manager in event.'
                    );

                    return true;
                }),
                $lifeCycleEventName,
                $order,
                $orderMetadata
            );

        return $this;
    }

    /**
     * Prepares the removal of an order.
     *
     * @param bool $success
     *
     * @return Order
     */
    private function prepareRemovalOfOneOrder(bool $success = true, Order $order = null): Order
    {
        if (!$order) {
            $order = new Order();
        }

        $orderMetadata = $this->getOneMockedMetadata($className = get_class($order), false);

        $this->mockAndCheckInvokerCall($order, Events::PRE_REMOVE, $orderMetadata, 0);

        if ($success) {
            $this->mockAndCheckInvokerCall($order, Events::POST_REMOVE, $orderMetadata, 1);
        }

        $order
            ->setId($orderId = uniqid())
            ->setVersion($orderVersion = mt_rand(1, 1000));

        $orderMetadata
            ->method('getName')
            ->willReturn($className);

        $this->documentManager
            ->expects($this->once())
            ->method('createRequest')
            ->with(
                $className,
                DocumentManager::REQUEST_TYPE_DELETE_BY_ID,
                $orderId,
                $orderVersion
            )
            ->willReturn(new OrderDeleteRequest($orderId, $orderVersion));

        static::assertSame(
            $this->fixture,
            $this->fixture->scheduleRemove($order),
            'Fluent interface broken.'
        );

        static::assertSame(
            1,
            $this->fixture->countRemovals(),
            'The object should be marked for removal.'
        );

        return $order;
    }

    /**
     * Sets up the test.
     *
     * @return void
     */
    public function setUp()
    {
        $this->fixture = new UnitOfWork(
            $this->actionBuilderProcessor = $this->createMock(ActionBuilderProcessorInterface::class),
            $this->documentManager = $this->createMock(DocumentManagerInterface::class),
            $this->createMock(EventManager::class),
            $this->listenerInvoker = $this->createMock(ListenersInvoker::class)
        );

        $this->setRequestCache(new ArrayObject());
    }

    /**
     * Checks the default return for the count function.
     *
     * @return void
     */
    public function testCountDefault()
    {
        static::assertCount(0, $this->fixture);
    }

    /**
     * Checks the default return for the count method.
     *
     * @return void
     */
    public function testCountManagedObjectsDefault()
    {
        static::assertSame(0, $this->fixture->countManagedObjects());
    }

    /**
     * Checks the default return for the count method.
     *
     * @return void
     */
    public function testCountRemovalsDefault()
    {
        static::assertSame(0, $this->fixture->countRemovals());
    }

    /**
     * Checks if an array for a custom entity is parsed correctly.
     *
     * @return void
     */
    public function testCreateDocumentParseCustomEntitiesArrayProperty()
    {
        $this->documentManager
            ->expects($this->once())
            ->method('getClassMetadata')
            ->with(TestCustomEntity::class)
            ->will($this->returnValue($metadata = new ClassMetadata(TestCustomEntity::class)));

        $metadata
            ->setFieldMappings(['addresses' => $field = new Field()])
            ->setReflectionClass(new ReflectionClass(TestCustomEntity::class));

        $field->collection = AddressCollection::class;
        $field->type = 'array';

        /** @var TestCustomEntity $createdDoc */
        $createdDoc = $this->fixture->createDocument(
            TestCustomEntity::class,
            Customer::fromArray([
                'addresses' => [
                    [
                        'id' => $addressId = uniqid(),
                        'salutation' => 'mr',
                        'firstName' => 'Björn',
                        'lastName' => 'Lange',
                        'streetName' => 'Rekener Str',
                        'streetNumber' => '60',
                        'additionalStreetInfo' => 'CTO',
                        'postalCode' => '46342',
                        'city' => 'Velen',
                        'region' => 'Nordrhein-Westfalen',
                        'state' => 'Nordrhein-Westfalen',
                        'country' => 'DE',
                        'company' => 'best it',
                        'department' => 'Management',
                        'apartment' => 'best it GmbH & Co. KG',
                        'phone' => '+49 2863 38362773',
                        'mobile' => '+49 160 91084976',
                        'email' => 'lange@bestit-online.de'
                    ]
                ]
            ])
        );

        /** @var $address Address */
        static::assertCount(1, $addresses = $createdDoc->getAddresses(), 'Wrong address count.');
        static::assertInstanceOf(Address::class, $address = $addresses[0], 'Wrong address instance.');
        static::assertSame($addressId, $address->getId(), 'Wrong address id.');
    }

    /**
     * Checks if an array for a custom entity is parsed correctly even if its null.
     *
     * @return void
     */
    public function testCreateDocumentParseCustomEntitiesArrayPropertyParseNull()
    {
        $this->documentManager
            ->expects($this->once())
            ->method('getClassMetadata')
            ->with(TestCustomEntity::class)
            ->will($this->returnValue($metadata = new ClassMetadata(TestCustomEntity::class)));

        $metadata
            ->setFieldMappings(['addresses' => $field = new Field()])
            ->setReflectionClass(new ReflectionClass(TestCustomEntity::class));

        $field->collection = AddressCollection::class;
        $field->type = 'array';

        /** @var TestCustomEntity $createdDoc */
        $createdDoc = $this->fixture->createDocument(
            TestCustomEntity::class,
            Customer::fromArray([])
        );

        /** @var $address Address */
        static::assertCount(0, $addresses = $createdDoc->getAddresses());
    }

    /**
     * Checks if an array for a custom entity is parsed correctly, even when its declared as set.
     *
     * @return void
     */
    public function testCreateDocumentParseCustomEntitiesArrayPropertyWithSetDeclaration()
    {
        $this->documentManager
            ->expects($this->once())
            ->method('getClassMetadata')
            ->with(TestCustomEntity::class)
            ->will($this->returnValue($metadata = new ClassMetadata(TestCustomEntity::class)));

        $metadata
            ->setFieldMappings(['addresses' => $field = new Field()])
            ->setReflectionClass(new ReflectionClass(TestCustomEntity::class));

        $field->collection = AddressCollection::class;
        $field->type = 'set';

        /** @var TestCustomEntity $createdDoc */
        $createdDoc = $this->fixture->createDocument(
            TestCustomEntity::class,
            Customer::fromArray([
                'addresses' => [
                    [
                        'id' => $addressId = uniqid(),
                        'salutation' => 'mr',
                        'firstName' => 'Björn',
                        'lastName' => 'Lange',
                        'streetName' => 'Rekener Str',
                        'streetNumber' => '60',
                        'additionalStreetInfo' => 'CTO',
                        'postalCode' => '46342',
                        'city' => 'Velen',
                        'region' => 'Nordrhein-Westfalen',
                        'state' => 'Nordrhein-Westfalen',
                        'country' => 'DE',
                        'company' => 'best it',
                        'department' => 'Management',
                        'apartment' => 'best it GmbH & Co. KG',
                        'phone' => '+49 2863 38362773',
                        'mobile' => '+49 160 91084976',
                        'email' => 'lange@bestit-online.de'
                    ]
                ]
            ])
        );

        /** @var $address Address */
        static::assertCount(1, $addresses = $createdDoc->getAddresses(), 'Wrong address count.');
        static::assertInstanceOf(Address::class, $address = $addresses[0], 'Wrong address instance.');
        static::assertSame($addressId, $address->getId(), 'Wrong address id.');
    }

    /**
     * Checks that a product draft is created correctly.
     *
     * @todo Check more values.
     *
     * @return void
     */
    public function testCreateNewRequestForProduct()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionCode($excCode = mt_rand(1, 100000));

        $product = new Product();

        $product
            ->setKey($key = uniqid())
            ->setProductType(ProductTypeReference::ofId($typeId = uniqid()))
            ->setTaxCategory(TaxCategoryReference::ofId($taxCatId = uniqid()))
            ->setMasterData(new ProductCatalogData())
            ->getMasterData()
            ->setCurrent(new ProductData())
            ->setStaged(new ProductData())
            ->getStaged()
            ->setDescription(LocalizedString::fromArray($desc = ['de' => uniqid()]))
            ->setName(LocalizedString::fromArray($name = ['de' => uniqid()]));

        $product
            ->getMasterData()->getStaged()->setMasterVariant(new ProductVariant());

        $metadataMock = $this->getOneMockedMetadata($className = get_class($product), false);

        $metadataMock
            ->method('getDraft')
            ->willReturn(ProductDraft::class);

        $metadataMock
            ->method('getFieldNames')
            ->willReturn(array_keys($product->fieldDefinitions()));

        $metadataMock
            ->method('isCTStandardModel')
            ->willReturn(true);

        $this->documentManager
            ->expects($this->once())
            ->method('createRequest')
            ->with(
                $className,
                DocumentManagerInterface::REQUEST_TYPE_CREATE,
                $this->callback(function (ProductDraft $draftObject) use ($desc, $key, $name, $taxCatId, $typeId) {
                    static::assertSame($desc, $draftObject->getDescription()->toArray(), 'Wrong Desc.');
                    static::assertSame($key, $draftObject->getKey(), 'Wrong Key.');
                    static::assertSame($typeId, $draftObject->getProductType()->getId(), 'Wrong type id.');
                    static::assertSame($taxCatId, $draftObject->getTaxCategory()->getId(), 'Wrong tax id.');
                    static::assertSame($name, $draftObject->getName()->toArray(), 'Wrong name.');

                    return true;
                })
            )->willThrowException(new RuntimeException('Controlled stop.', $excCode));

        static::assertCount(0, $this->fixture, 'Start count failed.');
        static::assertSame(0, $this->fixture->countNewObjects(), 'Start count of new objects failed.');

        $this->fixture->scheduleSave($product);

        $this->fixture->flush();
    }

    /**
     * Checks that an registered object is returned.
     *
     * @return void
     */
    public function testDetach()
    {
        $order = $this->testRegisterAsManaged();

        static::assertTrue($this->fixture->contains($order), 'Object should be contained in the uow.');

        $this->fixture->detach($order);

        static::assertFalse($this->fixture->contains($order), 'Object should not be contained in the uow.');
        static::assertCount(0, $this->fixture, 'There should be no entity.');
    }

    /**
     * The deferred detach should remove the entity on flush, even if there is no change.
     *
     * @return void
     */
    public function testDetachDeferredNoChange()
    {
        $this->getOneMockedMetadata(Order::class, false);

        static::assertCount(0, $this->fixture, 'Start count failed.');

        $order = new Order([
            'customerId' => uniqid(),
            'customerEmail' => 'test@example.com',
            'id' => uniqid(),
            'version' => 5
        ]);

        static::assertSame(
            $this->fixture,
            $this->fixture->registerAsManaged($order, $order->getId(), $order->getVersion()),
            'Fluent interface failed.'
        );

        static::assertCount(1, $this->fixture, 'There should be a managed entity.');

        $this->fixture->scheduleSave($order);
        $this->fixture->detachDeferred($order);
        $this->fixture->flush();

        static::assertCount(0, $this->fixture, 'The entity should be detached.');
    }

    /**
     * Checks that the "empty" detach does not trigger any error.
     *
     * @return void
     */
    public function testDetachEmpty()
    {
        $this->getOneMockedMetadata(Order::class, false);

        static::assertCount(0, $this->fixture, 'There should be no entity.');

        $this->fixture->detach(new Order());

        static::assertCount(0, $this->fixture, 'There should be no entity. (control value)');
    }

    /**
     * Checks if the changes are extracted correctly.
     *
     * @return void
     */
    public function testExtractChangesFullWithCustomObjectAndValueTypeChange()
    {
        $this->expectException(RuntimeException::class);

        $this->getOneMockedMetadata($className = CustomObject::class, false);

        // Force the unit of work to skip the specific logic for the custom objects.
        $this->documentManager
            ->expects(static::once())
            ->method('getRequestClass')
            ->with($className, DocumentManagerInterface::REQUEST_TYPE_UPDATE_BY_ID);

        /** @var Order $customObject */
        $this->fixture->registerAsManaged(
            $customObject = $className::fromArray($oldData = [
                'container' => $container = uniqid(),
                'key' => $key = uniqid(),
                'value' => '[\"foobar\"]'
            ]),
            uniqid(),
            5
        );

        $customObject->setValue($newValue = [['sku' => uniqid()]]);

        $this->actionBuilderProcessor
            ->expects($this->once())
            ->method('createUpdateActions')
            ->with(
                $this->isInstanceOf(ClassMetadataInterface::class),
                ['value' => $newValue],
                $oldData,
                $customObject
            )
            ->willThrowException(new RuntimeException('Controlled stop.'));

        $this->fixture->scheduleSave($customObject);
        $this->fixture->flush();
    }

    /**
     * Checks if the changes are extracted correctly.
     *
     * @return void
     */
    public function testExtractChangesFullWithOrder()
    {
        $this->expectException(RuntimeException::class);

        $metadata = $this->getOneMockedMetadata($className = Order::class, false);

        /** @var Order $order */
        $this->fixture->registerAsManaged(
            $order = $className::fromArray($oldData = [
                'id' => $oldOrderId = uniqid(),
                'billingAddress' => [
                    'id' => $oldAddressId = uniqid()
                ],
                'lineItems' => $lineItems = [
                    ['id' => $lineItemId1 = uniqid()],
                    ['id' => $lineItemId2 = uniqid()]
                ]
            ]),
            uniqid(),
            5
        );

        $order
            ->setId($newOrderId = uniqid())
            ->getBillingAddress()->setStreetName($streeName = uniqid());

        $order->getLineItems()->add(LineItem::fromArray(['id' => $lineItemId3 = uniqid()]));
        unset($order->getLineItems()[0]);

        $this->actionBuilderProcessor
            ->expects($this->once())
            ->method('createUpdateActions')
            ->with(
                $this->isInstanceOf(ClassMetadataInterface::class),
                [
                    'id' => $newOrderId,
                    'lineItems' => [
                        0 => null,
                        2 => [
                            'id' => $lineItemId3
                        ]
                    ],
                    'billingAddress' => [
                        'streetName' => $streeName
                    ]
                ],
                $oldData,
                $order
            )
            ->willThrowException(new RuntimeException('Controlled stop.'));

        $this->fixture->scheduleSave($order);
        $this->fixture->flush();
    }

    /**
     * Checks if the changes are extracted correctly.
     *
     * @return void
     */
    public function testExtractChangesFullWithProduct()
    {
        $this->expectException(RuntimeException::class);

        $this->getOneMockedMetadata($className = Product::class, false);

        /** @var Product $product */
        $this->fixture->registerAsManaged(
            $product = $className::fromArray($oldData = [
                'id' => $oldId = uniqid(),
                'masterData' => [
                    'current' => [
                        'categories' => [
                            [
                                'typeId' => 'category',
                                'id' => $category1Id = uniqid()
                            ],
                            [
                                'typeId' => 'category',
                                'id' => $category2Id = uniqid()
                            ],
                        ],
                        'masterVariant' => [
                            'id' => 1,
                            'attributes' => [
                                [
                                    'name' => 'arrayAdd',
                                    'value' => []
                                ],
                                [
                                    'name' => 'arrayAddPartly',
                                    'value' => [1]
                                ],
                                [
                                    'name' => 'arrayChange',
                                    'value' => [1,2,3]
                                ],
                                [
                                    'name' => 'arrayEmpty',
                                    'value' => [
                                        uniqid(),
                                        uniqid()
                                    ]
                                ],
                                [
                                    'name' => 'date',
                                    'value' => new DateTime()
                                ],
                                [
                                    'name' => 'float',
                                    'value' => 1.1
                                ],
                                [
                                    'name' => 'floatInt',
                                    'value' => (float) 1
                                ],
                                [
                                    'name' => 'manufacturer',
                                    'value' => uniqid()
                                ],
                                [
                                    'name' => 'null',
                                    'value' => 0,
                                ],
                                [
                                    'name' => 'nested',
                                    'value' => [
                                        [
                                            'name' => 'nestedNumber',
                                            'value' => 5,
                                        ],
                                        [
                                            'name' => 'nestedString',
                                            'value' => uniqid(),
                                        ]
                                    ],
                                ],
                                [
                                    'name' => 'number',
                                    'value' => 1,
                                ],
                                [
                                    'name' => 'price',
                                    'value' => [
                                        'currencyCode' => 'EUR',
                                        'centAmount' => 10010
                                    ]
                                ]
                            ]
                        ],
                        'name' => ['de' => $oldGermanName = uniqid(), 'fr' => uniqid()],
                        'variants' => [
                            [
                                'id' => 2,
                                'attributes' => [
                                    [
                                        'name' => 'manufacturer',
                                        'value' => uniqid()
                                    ]
                                ]
                            ]
                        ]
                    ]
                ],
                'taxCategory' => [
                    'typeId' => 'tax-category',
                    'id' => uniqid()
                ]
            ]),
            uniqid(),
            5
        );

        $productCatalogData = $product->setId($newId = uniqid())->getMasterData()->getCurrent();
        $categories = $productCatalogData->getCategories();

        $productCatalogData
            ->setName(LocalizedString::fromArray(['de' => $oldGermanName, 'en' => $newEnglishName = uniqid()]));

        unset($categories[1]);
        $categories->add(CategoryReference::ofId($category3Id = uniqid()));

        $productCatalogData
            ->getMasterVariant()
            ->getAttributes()
            ->getByName('arrayAdd')
            ->setValue($newAddedArray = [uniqid(), uniqid()]);

        $productCatalogData
            ->getMasterVariant()
            ->getAttributes()
            ->getByName('arrayAddPartly')
            ->setValue([1,2,3]);

        $productCatalogData
            ->getMasterVariant()
            ->getAttributes()
            ->getByName('arrayChange')
            ->setValue([2,3]);

        $productCatalogData
            ->getMasterVariant()
            ->getAttributes()
            ->getByName('arrayEmpty')
            ->setValue([]);

        $productCatalogData
            ->getMasterVariant()
            ->getAttributes()
            ->getByName('float')
            ->setValue(1.5);

        $productCatalogData
            ->getMasterVariant()
            ->getAttributes()
            ->getByName('floatInt')
            ->setValue(1);

        $productCatalogData
            ->getMasterVariant()
            ->getAttributes()
            ->getByName('null')
            ->setValue(0);

        $productCatalogData
            ->getMasterVariant()
            ->getAttributes()
            ->getByName('price')
            ->setValue(Money::fromArray(['currencyCode' => 'EUR', 'centAmount' => $newAmount = 5050]));

        $productCatalogData
            ->getMasterVariant()
            ->getAttributes()
            ->add(Attribute::fromArray(['name' => $newAttrName = uniqid(), 'value' => $newAttrValue = []]));

        $productCatalogData
            ->getVariants()
            ->getById(2)
            ->getAttributes()
            ->getByName('manufacturer')
            ->setValue($newManId = uniqid());

        $this->actionBuilderProcessor
            ->expects($this->once())
            ->method('createUpdateActions')
            ->with(
                $this->isInstanceOf(ClassMetadataInterface::class),
                [
                    'id' => $newId,
                    'masterData' => [
                        'current' => [
                            'categories' => [
                                1 => null,
                                2 => [
                                    'typeId' => 'category',
                                    'id' => $category3Id
                                ]
                            ],
                            'masterVariant' => [
                                'attributes' => [
                                    [
                                        'value' => $newAddedArray
                                    ],
                                    [
                                        'value' => [1,2,3,],
                                    ],
                                    [
                                        'value' => [2,3, null,],
                                    ],
                                    [
                                        'value' => [null,null,],
                                    ],
                                    5 => [
                                        'value' => 1.5
                                    ],
                                    11 => [
                                        'value' => [
                                            'centAmount' => $newAmount
                                        ]
                                    ],
                                    [
                                        'name' => $newAttrName,
                                        'value' => $newAttrValue
                                    ]
                                ]
                            ],
                            'name' => [
                                'en' => $newEnglishName,
                                'fr' => null
                            ],
                            'variants' => [
                                [
                                    'attributes' => [
                                        [
                                            'value' => $newManId
                                        ]
                                    ]
                                ],
                            ]
                        ]
                    ]
                ],
                $oldData,
                $product
            )
            ->willThrowException(new RuntimeException('Controlled stop.'));

        $this->fixture->scheduleSave($product);
        $this->fixture->flush();
    }

    /**
     * Checks the correct class instance.
     *
     * @return void
     */
    public function testInstance()
    {
        static::assertInstanceOf(UnitOfWorkInterface::class, $this->fixture);
    }

    /**
     * Checks if a managed object is registered correctly.
     *
     * @return Order The registered order.
     */
    public function testRegisterAsManaged(): Order
    {
        $this->getOneMockedMetadata(Order::class, false);

        static::assertCount(0, $this->fixture, 'Start count failed.');

        static::assertSame(
            $this->fixture,
            $this->fixture->registerAsManaged($order = new Order(), $orderId = uniqid(), 5),
            'Fluent interface failed.'
        );

        static::assertTrue($this->fixture->contains($order), 'Object should be contained in the uow.');

        static::assertCount(1, $this->fixture, 'The object should be saved.');

        static::assertSame(
            0,
            $this->fixture->countNewObjects(),
            'The object should not be saved as new.'
        );

        static::assertSame(
            1,
            $this->fixture->countManagedObjects(),
            'The object should be saved as managed.'
        );

        $this->fixture->registerAsManaged($order, uniqid(), 6);

        static::assertCount(1, $this->fixture, 'The object should be saved only once.');

        static::assertSame(
            1,
            $this->fixture->countManagedObjects(),
            'The object should be saved as managed only once.'
        );

        static::assertSame($order, $this->fixture->tryGetById($orderId));

        return $order;
    }

    /**
     * Checks if a managed object is registered correctly.
     *
     * @return void
     */
    public function testRegisterAsManagedNew()
    {
        $this->getOneMockedMetadata(Order::class, false);

        static::assertCount(0, $this->fixture, 'Start count failed.');

        static::assertSame(
            $this->fixture,
            $this->fixture->registerAsManaged($order = new Order()),
            'Fluent interface failed.'
        );

        static::assertTrue($this->fixture->contains($order), 'Object should be contained in the uow.');

        static::assertCount(1, $this->fixture, 'The object should be saved.');

        static::assertSame(
            1,
            $this->fixture->countNewObjects(),
            'The object should be saved as new.'
        );

        static::assertSame(
            0,
            $this->fixture->countManagedObjects(),
            'The object should not be saved as managed.'
        );

        $this->fixture->registerAsManaged($order);

        static::assertCount(1, $this->fixture, 'The object should be saved only once.');

        static::assertSame(
            1,
            $this->fixture->countNewObjects(),
            'The object should be saved as new only once.'
        );
    }

    /**
     * Checks if the remove is handled correctly.
     *
     * @return void
     */
    public function testScheduleRemove()
    {
        $this->prepareRemovalOfOneOrder();

        $this->fixture->setClient($this->getClientWithResponses(
            function (): Response {
                return new Response(
                    200,
                    [
                        'x-served-config' => 'sphere-projects-ws-1.0',
                        'server' => 'nginx',
                        'content-type' => 'application/json; charset=utf-8',
                        'content-encoding' => 'gzip',
                        'date' => 'Mon, 10 Apr 2017 20:21:03 GMT',
                        'access-control-max-age' => '299',
                        'x-served-by' => 'api-pt-reverent-engelbart.sphere.prod.commercetools.de',
                        'x-correlation-id' => 'projects-bob-058c-4c13-a372-3fa2a4ddbe23',
                        'transfer-encoding' => 'chunked',
                        'access-control-allow-origin' => '*',
                        'connection' => 'close',
                        'access-control-allow-headers' => 'Accept, Authorization, Content-Type, Origin, User-Agent',
                        'access-control-allow-methods' => 'GET, POST, DELETE, OPTIONS'
                    ],
                    file_get_contents(
                        __DIR__ . DIRECTORY_SEPARATOR . 'Resources/stubs/order_delete_success_response.json'
                    )
                );
            }
        ));

        $this->fixture->flush();

        static::assertSame(
            0,
            $this->fixture->countRemovals(),
            'The object should be removed.'
        );
    }

    /**
     * Checks if the not-found remove is handled correctly.
     *
     * @return void
     */
    public function testScheduleRemoveFailNotFound()
    {
        $this->expectException(NotFoundException::class);

        $this->prepareRemovalOfOneOrder(false);

        $this->fixture->setClient($this->getClientWithResponses(
            function (): Response {
                return new Response(
                    404,
                    [
                        'x-served-config' => 'sphere-projects-ws-1.0',
                        'server' => 'nginx',
                        'content-type' => 'application/json; charset=utf-8',
                        'content-encoding' => 'gzip',
                        'date' => 'Mon, 10 Apr 2017 20:21:03 GMT',
                        'access-control-max-age' => '299',
                        'x-served-by' => 'api-pt-reverent-engelbart.sphere.prod.commercetools.de',
                        'x-correlation-id' => 'projects-bob-058c-4c13-a372-3fa2a4ddbe23',
                        'transfer-encoding' => 'chunked',
                        'access-control-allow-origin' => '*',
                        'connection' => 'close',
                        'access-control-allow-headers' => 'Accept, Authorization, Content-Type, Origin, User-Agent',
                        'access-control-allow-methods' => 'GET, POST, DELETE, OPTIONS'
                    ],
                    file_get_contents(
                        __DIR__ . DIRECTORY_SEPARATOR . 'Resources/stubs/order_delete_notfound_response.json'
                    )
                );
            }
        ));

        $this->fixture->flush();

        static::assertSame(
            0,
            $this->fixture->countRemovals(),
            'The object should be removed.'
        );
    }

    /**
     * Checks if correct exception will be thrown for connection errors
     *
     * @return void
     */
    public function testConnectionError()
    {
        $this->expectException(OdmConnectException::class);

        $this->prepareRemovalOfOneOrder(false);

        $this->fixture->setClient($this->getClientWithResponses(
            function () {
                return new ConnectException('foo', $this->createMock(RequestInterface::class));
            }
        ));

        $this->fixture->flush();

        static::assertSame(
            0,
            $this->fixture->countRemovals(),
            'The object should be removed.'
        );
    }

    /**
     * Checks if the remove is handled correctly, even if the order is registered before.
     *
     * @depends testRegisterAsManaged
     * @param Order $order
     *
     * @return void
     */
    public function testScheduleRemovePrevManaged(Order $order)
    {
        $this->prepareRemovalOfOneOrder(true, $order);

        $this->fixture->setClient($this->getClientWithResponses(
            function (): Response {
                return new Response(
                    200,
                    [
                        'x-served-config' => 'sphere-projects-ws-1.0',
                        'server' => 'nginx',
                        'content-type' => 'application/json; charset=utf-8',
                        'content-encoding' => 'gzip',
                        'date' => 'Mon, 10 Apr 2017 20:21:03 GMT',
                        'access-control-max-age' => '299',
                        'x-served-by' => 'api-pt-reverent-engelbart.sphere.prod.commercetools.de',
                        'x-correlation-id' => 'projects-bob-058c-4c13-a372-3fa2a4ddbe23',
                        'transfer-encoding' => 'chunked',
                        'access-control-allow-origin' => '*',
                        'connection' => 'close',
                        'access-control-allow-headers' => 'Accept, Authorization, Content-Type, Origin, User-Agent',
                        'access-control-allow-methods' => 'GET, POST, DELETE, OPTIONS'
                    ],
                    file_get_contents(
                        __DIR__ . DIRECTORY_SEPARATOR . 'Resources/stubs/order_delete_success_response.json'
                    )
                );
            }
        ));

        $this->fixture->flush();

        static::assertSame(
            0,
            $this->fixture->countRemovals(),
            'The object should be removed.'
        );

        static::assertSame(
            0,
            $this->fixture->countManagedObjects(),
            'The should be removed as managed.'
        );

        static::assertCount(0, $this->fixture, 'There should be no countable entity.');
    }

    /**
     * Checks if the new object is saved.
     *
     * @param bool $withDetach Is the detach used?
     *
     * @return void
     */
    public function testScheduleSaveNew(bool $withDetach = false)
    {
        $type = new ProductType([
            'createdAt' => new DateTime(),
            'lastModifiedAt' => new DateTime(),
            'name' => $typeName = uniqid(),
            'version' => uniqid()
        ]);

        $typeMetadata = $this->getOneMockedMetadata($className = get_class($type), false);

        $typeMetadata
            ->method('getDraft')
            ->willReturn($draftClassName = ProductTypeDraft::class);

        $typeMetadata
            ->method('getFieldNames')
            ->willReturn(array_keys($type->fieldDefinitions()));

        $this->documentManager
            ->expects($this->once())
            ->method('createRequest')
            ->with(
                $className,
                DocumentManager::REQUEST_TYPE_CREATE,
                $this->callback(function (ProductTypeDraft $draftObject) use ($draftClassName, $typeName) {
                    static::assertInstanceOf($draftClassName, $draftObject, 'Wrong draft instance.');

                    // Are the standard fields removed?
                    static::assertSame(
                        ['name' => $typeName],
                        $draftObject->toArray(),
                        'The default data was not removed.'
                    );

                    return true;
                })
            )
            ->willReturn(new ProductTypeCreateRequest(new ProductTypeDraft(['name' => $typeName])));

        $this
            // TODO: Fix the redundant calls
            ->mockAndCheckInvokerCall($type, Events::PRE_PERSIST, $typeMetadata, 0)
            ->mockAndCheckInvokerCall($type, Events::POST_REGISTER, $typeMetadata, 1)
            ->mockAndCheckInvokerCall($type, Events::POST_REGISTER, $typeMetadata, 2)
            ->mockAndCheckInvokerCall($type, Events::POST_PERSIST, $typeMetadata, 3);

        if ($withDetach) {
            $this->mockAndCheckInvokerCall($type, Events::POST_DETACH, $typeMetadata, 4);
        }

        $this->fixture->scheduleSave($type);

        if ($withDetach) {
            $this->fixture->detachDeferred($type);
        }

        static::assertSame(
            0,
            $this->fixture->countManagedObjects(),
            'There should be a new managed object.'
        );

        static::assertSame(
            1,
            $this->fixture->countNewObjects(),
            'There should be a new object.'
        );

        $this->fixture->setClient($this->getClientWithResponses(
            function (): Response {
                return new Response(
                    201,
                    [
                        'x-served-config' => 'sphere-projects-ws-1.0',
                        'server' => 'nginx',
                        'content-type' => 'application/json; charset=utf-8',
                        'content-encoding' => 'gzip',
                        'date' => 'Mon, 10 Apr 2017 20:21:03 GMT',
                        'access-control-max-age' => '299',
                        'x-served-by' => 'api-pt-reverent-engelbart.sphere.prod.commercetools.de',
                        'x-correlation-id' => 'projects-bob-058c-4c13-a372-3fa2a4ddbe23',
                        'transfer-encoding' => 'chunked',
                        'access-control-allow-origin' => '*',
                        'connection' => 'close',
                        'access-control-allow-headers' => 'Accept, Authorization, Content-Type, Origin, User-Agent',
                        'access-control-allow-methods' => 'GET, POST, DELETE, OPTIONS'
                    ],
                    file_get_contents(
                        __DIR__ . DIRECTORY_SEPARATOR .
                        'Resources/stubs/product-type_create_success_response.json'
                    )
                );
            }
        ));

        $this->fixture->flush();

        if (!$withDetach) {
            static::assertSame(
                1,
                $this->fixture->countManagedObjects(),
                'There should be a managed object.'
            );
        } else {
            static::assertSame(
                0,
                $this->fixture->countManagedObjects(),
                'There should be no managed object cause of detaching.'
            );
        }

        static::assertSame(
            0,
            $this->fixture->countNewObjects(),
            'There should be no new object.'
        );

        if (!$withDetach) {
            static::assertInstanceOf(
                ProductType::class,
                $this->fixture->tryGetById('a58213d7-c5c6-4fd0-9b9e-635785fa8d4f'),
                'The object was not registered correctly.'
            );
        }
    }

    /**
     * Checks if the new object is saved but detached afterwards.
     *
     * @return void
     */
    public function testScheduleSaveNewWithDetach()
    {
        $this->testScheduleSaveNew(true);
    }
}
