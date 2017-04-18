<?php

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
use BestIt\CommercetoolsODM\Exception\NotFoundException;
use BestIt\CommercetoolsODM\Helper\EventManagerAwareTrait;
use BestIt\CommercetoolsODM\Helper\ListenerInvokerAwareTrait;
use BestIt\CommercetoolsODM\Mapping\Annotations\Field;
use BestIt\CommercetoolsODM\Mapping\ClassMetadata;
use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use BestIt\CommercetoolsODM\Tests\UnitOfWork\TestCustomEntity;
use BestIt\CommercetoolsODM\UnitOfWork;
use BestIt\CommercetoolsODM\UnitOfWorkInterface;
use Commercetools\Core\Client;
use Commercetools\Core\Client\OAuth\Manager;
use Commercetools\Core\Client\OAuth\Token;
use Commercetools\Core\Model\Cart\LineItem;
use Commercetools\Core\Model\Category\CategoryReference;
use Commercetools\Core\Model\Common\Address;
use Commercetools\Core\Model\Common\AddressCollection;
use Commercetools\Core\Model\Common\Attribute;
use Commercetools\Core\Model\Common\JsonObject;
use Commercetools\Core\Model\Common\LocalizedString;
use Commercetools\Core\Model\Common\Money;
use Commercetools\Core\Model\Customer\Customer;
use Commercetools\Core\Model\Order\Order;
use Commercetools\Core\Model\Product\Product;
use Commercetools\Core\Model\ProductType\ProductType;
use Commercetools\Core\Model\ProductType\ProductTypeDraft;
use Commercetools\Core\Request\Orders\OrderDeleteRequest;
use Commercetools\Core\Request\ProductTypes\ProductTypeCreateRequest;
use DateTime;
use Doctrine\Common\EventManager;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use ReflectionClass;
use RuntimeException;

/**
 * Class UnitOfWorkTest
 * @author blange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM
 * @version $id$
 */
class UnitOfWorkTest extends TestCase
{
    /**
     * The used document manager.
     * @var ActionBuilderProcessorInterface|PHPUnit_Framework_MockObject_MockObject
     */
    private $actionBuilderProcessor = null;

    /**
     * The used document manager.
     * @var DocumentManagerInterface|PHPUnit_Framework_MockObject_MockObject
     */
    private $documentManager = null;

    /**
     * The fixture.
     * @var UnitOfWork
     */
    private $fixture = null;

    /**
     * The used document manager.
     * @var ListenersInvoker|PHPUnit_Framework_MockObject_MockObject
     */
    private $listenerInvoker = null;

    /**
     * The cache for the client request history.
     * @var ArrayObject|null
     */
    private $requestCache = null;

    /**
     * Returns a client with mocked responses.
     * @param array ...$responses
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    protected function getClientWithResponses(...$responses)
    {
        foreach ($responses as &$response) {
            if (is_callable($response)) {
                $response = $response();
            }
        }

        $client = $this->getTestClient($responses);

        return $client;
    }

    /**
     * Adds a mocked call for metadata for the given model.
     * @param string $model
     * @param bool $once Is the metadata only reguested once.
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    private function getOneMockedMetadata(string $model, bool $once = true): PHPUnit_Framework_MockObject_MockObject
    {
        $this->documentManager
            ->expects($once ? static::once() : static::any())
            ->method('getClassMetadata')
            ->with($model)
            ->willReturn($classMetadata = static::createMock(ClassMetadataInterface::class));

        $classMetadata
            ->method('getName')
            ->willReturn($model);

        $classMetadata
            ->method('isCTStandardModel')
            ->willReturn(is_a($model, JsonObject::class, true));

        return $classMetadata;
    }

    /**
     * Returns tTe cache for the client request history.
     * @return ArrayObject
     */
    public function getRequestCache(): ArrayObject
    {
        if (!$this->requestCache) {
            $this->setRequestCache(new ArrayObject());
        }

        return $this->requestCache;
    }

    /**
     * Ceates a test client with the given responses.
     * @param array $responses
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    protected function getTestClient(array $responses)
    {
        $authMock = $this->createPartialMock(Manager::class, ['getToken']);
        $authMock
            ->method('getToken')
            ->will($this->returnValue(new Token(uniqid())));

        $client = $this->createPartialMock(Client::class, ['getOauthManager']);
        $client
            ->method('getOauthManager')
            ->will($this->returnValue($authMock));

        $mock = new MockHandler($responses);

        $handlerStack = HandlerStack::create($mock);

        $requestCache = $this->getRequestCache();
        $handlerStack->push(Middleware::history($requestCache));

        $client->getHttpClient(['handler' => $handlerStack]);
        $client->getOauthManager()->getHttpClient(['handler' => $handlerStack]);

        return $client;
    }

    /**
     * Returns the used traits.
     * @return array
     */
    public static function getUsedTraits(): array
    {
        return [
            [ActionBuilderProcessorAwareTrait::class],
            [ClientAwareTrait::class],
            [EventManagerAwareTrait::class],
            [ListenerInvokerAwareTrait::class]
        ];
    }

    /**
     * Mocks an listener invoker call for the given object.
     * @param object $order
     * @param string $lifeCycleEventName
     * @param ClassMetadataInterface $orderMetadata
     * @return UnitOfWorkTest
     */
    private function mockAndCheckInvokerCall(
        $order,
        string $lifeCycleEventName,
        ClassMetadataInterface $orderMetadata,
        $expected = 'any'
    ): UnitOfWorkTest {
        $this->listenerInvoker
            ->expects(is_string($expected) ? static::$expected() : static::at($expected))
            ->method('invoke')
            ->with(
                static::callback(function (LifecycleEventArgs $eventArgs) use ($order) {
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
     * @param bool $success
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
            ->expects(static::once())
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
     * @return void
     */
    public function setUp()
    {
        $this->fixture = new UnitOfWork(
            $this->actionBuilderProcessor = static::createMock(ActionBuilderProcessorInterface::class),
            $this->documentManager = static::createMock(DocumentManagerInterface::class),
            static::createMock(EventManager::class),
            $this->listenerInvoker = static::createMock(ListenersInvoker::class)
        );

        $this->setRequestCache(new ArrayObject());
    }

    /**
     * Sets the cache for the client request history.
     * @param ArrayObject $requestCache
     * @return UnitOfWorkTest
     */
    public function setRequestCache(ArrayObject $requestCache): UnitOfWorkTest
    {
        $this->requestCache = $requestCache;
        return $this;
    }

    /**
     * Checks the default return for the count function.
     * @return void
     */
    public function testCountDefault()
    {
        static::assertCount(0, $this->fixture);
    }

    /**
     * Checks the default return for the count method.
     * @return void
     */
    public function testCountManagedObjectsDefault()
    {
        static::assertSame(0, $this->fixture->countManagedObjects());
    }

    /**
     * Checks the default return for the count method.
     * @return void
     */
    public function testCountRemovalsDefault()
    {
        static::assertSame(0, $this->fixture->countRemovals());
    }

    /**
     * Checks if an array for a custom entity is parsed correctly.
     * @covers UnitOfWork::createDocument()
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
     * @covers UnitOfWork::createDocument()
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
     * The deferred detach should remove the entity on flush, even if there is no change.
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
     * @return void
     */
    public function testDetachEmpty()
    {
        static::assertCount(0, $this->fixture, 'There should be no entity.');

        $this->fixture->detach(new Order());

        static::assertCount(0, $this->fixture, 'There should be no entity. (control value)');
    }

    /**
     * Checks if the changes are extracted correctly.
     * @covers UnitOfWork::extractChanges()
     * @return void
     */
    public function testExtractChangesFullWithProduct()
    {
        static::expectException(RuntimeException::class);

        $metadata = $this->getOneMockedMetadata($className = Product::class, false);

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
                                    'name' => 'manufacturer',
                                    'value' => uniqid()
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
            ->expects(static::once())
            ->method('createUpdateActions')
            ->with(
                static::isInstanceOf(ClassMetadataInterface::class),
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
                                    1 => [
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
     * Checks if the changes are extracted correctly.
     * @covers UnitOfWork::extractChanges()
     * @return void
     */
    public function testExtractChangesFullWithOrder()
    {
        static::expectException(RuntimeException::class);

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
            ->expects(static::once())
            ->method('createUpdateActions')
            ->with(
                static::isInstanceOf(ClassMetadataInterface::class),
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
     * Checks the correct class instance.
     * @return void
     */
    public function testInstance()
    {
        static::assertInstanceOf(UnitOfWorkInterface::class, $this->fixture);
    }

    /**
     * Checks if a managed object is registered correctly.
     * @return Order The registered order.
     */
    public function testRegisterAsManaged(): Order
    {
        $this->getOneMockedMetadata(Order::class);

        static::assertCount(0, $this->fixture, 'Start count failed.');

        static::assertSame(
            $this->fixture,
            $this->fixture->registerAsManaged($order = new Order(), $orderId = uniqid(), 5),
            'Fluent interface failed.'
        );

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
     * @return void
     */
    public function testRegisterAsManagedNew()
    {
        static::assertCount(0, $this->fixture, 'Start count failed.');

        static::assertSame(
            $this->fixture,
            $this->fixture->registerAsManaged($order = new Order()),
            'Fluent interface failed.'
        );

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
     * Checks that an registered object is returned.
     * @depends testRegisterAsManaged
     * @param Order $order
     * @return void
     */
    public function testDetach(Order $order)
    {
        $this->fixture->detach($order);

        static::assertCount(0, $this->fixture, 'There should be no entity.');
    }

    /**
     * Checks if the remove is handled correctly.
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
     * Checks if the remove is handled correctly, even if the order is registered before.
     * @depends testRegisterAsManaged
     * @param Order $order
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
     * Checks if the not-found remove is handled correctly.
     */
    public function testScheduleRemoveFailNotFound()
    {
        static::expectException(NotFoundException::class);

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
     * Checks if the new object is saved.
     * @return void
     */
    public function testScheduleSaveNew($withDetach = false)
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
            ->expects(static::once())
            ->method('createRequest')
            ->with(
                $className,
                DocumentManager::REQUEST_TYPE_CREATE,
                static::callback(function (ProductTypeDraft $draftObject) use ($draftClassName, $typeName) {
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
            ->mockAndCheckInvokerCall($type, Events::PRE_PERSIST, $typeMetadata, 0)
            ->mockAndCheckInvokerCall($type, Events::POST_PERSIST, $typeMetadata, 1);

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
     * @return void
     */
    public function testScheduleSaveNewWithDetach()
    {
        $this->testScheduleSaveNew(true);
    }

    /**
     * Checks if the trait is implemented.
     * @dataProvider getUsedTraits
     * @param string $trait
     */
    public function testTraits(string $trait)
    {
        static::assertContains($trait, class_uses($this->fixture));
    }
}
