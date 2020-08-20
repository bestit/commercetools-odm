<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\Tests\UnitOfWork;

use BestIt\CommercetoolsODM\DocumentManagerInterface;
use BestIt\CommercetoolsODM\Mapping\Annotations\Field;
use BestIt\CommercetoolsODM\Mapping\ClassMetadata;
use BestIt\CommercetoolsODM\UnitOfWork\ChangeManager;
use Commercetools\Core\Model\Cart\LineItem;
use Commercetools\Core\Model\Category\CategoryReference;
use Commercetools\Core\Model\Common\Attribute;
use Commercetools\Core\Model\Common\LocalizedString;
use Commercetools\Core\Model\Common\Money;
use Commercetools\Core\Model\CustomObject\CustomObject;
use Commercetools\Core\Model\Order\Order;
use Commercetools\Core\Model\Product\Product;
use DateTime;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionException;
use function uniqid;

/**
 * Class ChangeManagerTest
 *
 * @author blange <bjoern.lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\Tests\UnitOfWork
 */
class ChangeManagerTest extends TestCase
{
    /**
     * The injected document manager.
     *
     * @var DocumentManagerInterface|null|MockObject
     */
    private $documentManager;

    /**
     * The tested class.
     *
     * @var ChangeManager|null
     */
    private $fixture;

    /**
     * Checks if a model can be registered.
     *
     * @return Product
     */
    private function checkSimpleRegistration(): Product
    {
        $this->fixture->registerStatus($model = new Product());

        static::assertTrue($this->fixture->contains($model), 'The model should be managed with the manager.');
        static::assertSame(1, count($this->fixture), 'There should be one model.');

        return $model;
    }

    /**
     * Sets up the test.
     *
     * @return void
     */
    protected function setUp()
    {
        $this->fixture = new ChangeManager(
            $this->documentManager = $this->createMock(DocumentManagerInterface::class)
        );
    }

    /**
     * Checks if false is return if the change manager does not contain the given product.
     *
     * @return void
     */
    public function testContainsDefault()
    {
        static::assertFalse($this->fixture->contains(new Product()));
    }

    /**
     * Checks the default return without any childs.
     *
     * @return void
     */
    public function testCountDefault()
    {
        static::assertSame(0, count($this->fixture));
    }

    /**
     * Checks if there is a correct count with multiple registered objects.
     *
     * @return void
     */
    public function testCountMultiples()
    {
        $this->fixture->registerStatus(new Product());
        $this->fixture->registerStatus(new Product());

        static::assertSame(2, count($this->fixture), 'there should be two models in the manager.');
    }

    /**
     * Checks if a given model can be detached.
     *
     * @return void
     */
    public function testDetach()
    {
        $model = $this->checkSimpleRegistration();

        $this->fixture->detach($model);

        static::assertSame(0, count($this->fixture));
    }

    /**
     * Checks if the changes are extracted correctly.
     *
     * @return void
     */
    public function testGetChangesChangesFullWithCustomObjectAndValueTypeChange()
    {
        /** @var Order $customObject */
        $this->fixture->registerStatus(
            $customObject = CustomObject::fromArray($oldData = [
                'container' => $container = uniqid(),
                'key' => $key = uniqid(),
                'value' => '[\"foobar\"]',
            ]),
            uniqid(),
            5
        );

        $customObject->setValue($newValue = [['sku' => uniqid()]]);

        static::assertSame(
            [
                'value' => $newValue,
            ],
            $this->fixture->getChanges($customObject)
        );
    }

    /**
     * Checks if changes are extracted from a non standard object.
     *
     * @throws ReflectionException
     *
     * @return void
     */
    public function testGetChangesNonCtObject()
    {
        $this->documentManager
            ->method('getClassMetadata')
            ->with(TestCustomEntity::class)
            ->will($this->returnValue($metadata = new ClassMetadata(TestCustomEntity::class)));

        $metadata
            ->setFieldMappings(['addresses' => $field = new Field()])
            ->setReflectionClass(new ReflectionClass(TestCustomEntity::class));

        $model = new TestCustomEntity();

        $model->setAddresses([uniqid()]);

        /** @var Order $customObject */
        $this->fixture->registerStatus($model);

        $model->setAddresses($newAddress = ['new-address']);

        static::assertSame(
            [
                'addresses' => $newAddress,
            ],
            $this->fixture->getChanges($model)
        );
    }

    /**
     * Checks the default return.
     *
     * @return void
     */
    public function testGetChangesDefault()
    {
        $model = $this->checkSimpleRegistration();

        static::assertSame([], $this->fixture->getChanges($model));
    }

    /**
     * Checks if the changes are extracted correctly.
     *
     * @return void
     */
    public function testGetChangesFullWithOrder()
    {
        /** @var Order $order */
        $this->fixture->registerStatus(
            $order = Order::fromArray($oldData = [
                'id' => $oldOrderId = uniqid(),
                'billingAddress' => [
                    'id' => $oldAddressId = uniqid(),
                ],
                'lineItems' => $lineItems = [
                    ['id' => $lineItemId1 = uniqid()],
                    ['id' => $lineItemId2 = uniqid()],
                ],
            ]),
            uniqid(),
            5
        );

        $order
            ->setId($newOrderId = uniqid())
            ->getBillingAddress()->setStreetName($streeName = uniqid());

        $order->getLineItems()->add(LineItem::fromArray(['id' => $lineItemId3 = uniqid()]));
        unset($order->getLineItems()[0]);

        static::assertSame(
            [
                'id' => $newOrderId,
                'billingAddress' => [
                    'streetName' => $streeName,
                ],
                'lineItems' => [
                    2 => [
                        'id' => $lineItemId3,
                    ],
                    0 => null,
                ],
            ],
            $this->fixture->getChanges($order)
        );
    }

    /**
     * Checks if the changes are extracted correctly.
     *
     * @return void
     */
    public function testGetChangesFullWithProduct()
    {
        /** @var Product $product */
        $this->fixture->registerStatus(
            $product = Product::fromArray($oldData = [
                'id' => $oldId = uniqid(),
                'masterData' => [
                    'current' => [
                        'categories' => [
                            [
                                'typeId' => 'category',
                                'id' => $category1Id = uniqid(),
                            ],
                            [
                                'typeId' => 'category',
                                'id' => $category2Id = uniqid(),
                            ],
                        ],
                        'masterVariant' => [
                            'id' => 1,
                            'attributes' => [
                                [
                                    'name' => 'arrayAdd',
                                    'value' => [],
                                ],
                                [
                                    'name' => 'arrayAddPartly',
                                    'value' => [1],
                                ],
                                [
                                    'name' => 'arrayChange',
                                    'value' => [1, 2, 3],
                                ],
                                [
                                    'name' => 'arrayEmpty',
                                    'value' => [
                                        uniqid(),
                                        uniqid(),
                                    ],
                                ],
                                [
                                    'name' => 'date',
                                    'value' => new DateTime(),
                                ],
                                [
                                    'name' => 'float',
                                    'value' => 1.1,
                                ],
                                [
                                    'name' => 'floatInt',
                                    'value' => (float) 1,
                                ],
                                [
                                    'name' => 'manufacturer',
                                    'value' => uniqid(),
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
                                        ],
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
                                        'centAmount' => 10010,
                                    ],
                                ],
                            ],
                        ],
                        'name' => ['de' => $oldGermanName = uniqid(), 'fr' => uniqid()],
                        'variants' => [
                            [
                                'id' => 2,
                                'attributes' => [
                                    [
                                        'name' => 'manufacturer',
                                        'value' => uniqid(),
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                'taxCategory' => [
                    'typeId' => 'tax-category',
                    'id' => uniqid(),
                ],
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
            ->setValue([1, 2, 3]);

        $productCatalogData
            ->getMasterVariant()
            ->getAttributes()
            ->getByName('arrayChange')
            ->setValue([2, 3]);

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

        static::assertSame(
            [
                'id' => $newId,
                'masterData' => [
                    'current' => [
                        'categories' => [
                            2 => [
                                'typeId' => 'category',
                                'id' => $category3Id,
                            ],
                            1 => null,
                        ],
                        'masterVariant' => [
                            'attributes' => [
                                [
                                    'value' => $newAddedArray,
                                ],
                                [
                                    'value' => [1, 2, 3,],
                                ],
                                [
                                    'value' => [2, 3, null,],
                                ],
                                [
                                    'value' => [null, null,],
                                ],
                                5 => [
                                    'value' => 1.5,
                                ],
                                11 => [
                                    'value' => [
                                        'centAmount' => $newAmount,
                                    ],
                                ],
                                [
                                    'name' => $newAttrName,
                                    'value' => $newAttrValue,
                                ],
                            ],
                        ],
                        'name' => [
                            'en' => $newEnglishName,
                            'fr' => null,
                        ],
                        'variants' => [
                            [
                                'attributes' => [
                                    [
                                        'value' => $newManId,
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            $this->fixture->getChanges($product)
        );
    }

    /**
     * Checks the default return of the method.
     *
     * @return void
     */
    public function testIsChangedDefault()
    {
        $model = $this->checkSimpleRegistration();

        static::assertFalse($this->fixture->isChanged($model), 'The model should not be changed.');
    }

    /**
     * Checks the return value with a real change.
     *
     * @return void
     */
    public function testIsChangedTrue()
    {
        $model = $this->checkSimpleRegistration();

        $model->setKey(uniqid());

        static::assertTrue($this->fixture->isChanged($model), 'The model should be changed.');
    }

    /**
     * Checks if models can be added?
     *
     * @return void
     */
    public function testRegisterStatus()
    {
        $this->checkSimpleRegistration();
    }

    /**
     * Checks that no doubled can be added to the manager.
     *
     * @return void
     */
    public function testRegisterStatusNoDoubles()
    {
        $model = $this->checkSimpleRegistration();

        $this->fixture->registerStatus($model);

        static::assertSame(1, count($this->fixture), 'There should be just one model.');
    }
}
