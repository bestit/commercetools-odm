<?php

namespace BestIt\CommercetoolsODM\Tests\ActionBuilder\ProductType;

use BestIt\CommercetoolsODM\ActionBuilder\ProductType\ChangeOrderOfAttributes;
use BestIt\CommercetoolsODM\ActionBuilder\ProductType\ProductTypeActionBuilder;
use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use BestIt\CommercetoolsODM\Tests\ActionBuilder\SupportTestTrait;
use Commercetools\Core\Model\Product\Product;
use Commercetools\Core\Model\ProductType\ProductType;
use Commercetools\Core\Request\ProductTypes\Command\ProductTypeChangeAttributeOrderByNameAction;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

/**
 * Test for change order of attributes action builder
 *
 * @author Michel Chowanski <michel.chowanski@bestit-online.de>
 * @package BestIt\CommercetoolsODM\Tests\ActionBuilder\ProductType
 */
class ChangeOrderOfAttributesTest extends TestCase
{
    use SupportTestTrait;

    /**
     * The test class.
     *
     * @var ChangeOrderOfAttributes|PHPUnit_Framework_MockObject_MockObject
     */
    protected $fixture = null;

    /**
     * Returns an array with the assertions for the support method.
     *
     * The First Element is the field path, the second element is the reference class and the optional third value
     * indicates the return value of the support method.
     *
     * @return array
     */
    public function getSupportAssertions(): array
    {
        return [
            ['attributes', ProductType::class, true],
            ['/attributes', ProductType::class],
            ['attributes', Product::class],
            ['attributes/', ProductType::class],
        ];
    }

    /**
     * Sets up the test.
     *
     * @return void
     */
    public function setUp()
    {
        $this->fixture = new ChangeOrderOfAttributes();
    }

    /**
     * Checks new added values should be skipped
     *
     * @return void
     */
    public function testAddedValues()
    {
        $productType = new ProductType([
            'attributes' => [
                [
                    'name' => $attributeName1 = uniqid(),
                    'type' => ['name' => 'text']
                ]
            ]
        ]);

        $actions = $this->fixture->createUpdateActions(
            [],
            $this->createMock(ClassMetadataInterface::class),
            [],
            [
                'attributes' => [
                    [
                        'name' => $attributeName1,
                        'type' => ['name' => 'text']
                    ],
                    [
                        'name' => $attributeName2 = uniqid(),
                        'type' => ['name' => 'text']
                    ]
                ]
            ],
            $productType
        );

        static::assertCount(1, $actions);
    }

    /**
     * Checks removed values should be skipped
     *
     * @return void
     */
    public function testRemovedValues()
    {
        $productType = new ProductType([
            'attributes' => [
                [
                    'name' => $attributeName1 = uniqid(),
                    'type' => ['name' => 'text']
                ],
                [
                    'name' => $attributeName2 = uniqid(),
                    'type' => ['name' => 'text']
                ]
            ]
        ]);

        $actions = $this->fixture->createUpdateActions(
            [],
            $this->createMock(ClassMetadataInterface::class),
            [],
            [
                'attributes' => [
                    [
                        'name' => $attributeName1,
                        'type' => ['name' => 'text']
                    ]
                ]
            ],
            $productType
        );

        static::assertCount(1, $actions);
    }

    /**
     * Test attribute sorting
     *
     * @return void
     */
    public function testSortAttributes()
    {
        $productType = new ProductType([
            'attributes' => [
                [
                    'name' => $attributeName2 = uniqid(),
                    'type' => ['name' => 'text']
                ],
                [
                    'name' => $attributeName1 = uniqid(),
                    'type' => ['name' => 'text']
                ]
            ]
        ]);

        $actions = $this->fixture->createUpdateActions(
            [],
            $this->createMock(ClassMetadataInterface::class),
            [],
            [
                'attributes' => [
                    [
                        'name' => $attributeName1,
                        'type' => ['name' => 'text']
                    ],
                    [
                        'name' => $attributeName2,
                        'type' => ['name' => 'text']
                    ]
                ]
            ],
            $productType
        );

        $action = $actions[0];
        assert($action instanceof ProductTypeChangeAttributeOrderByNameAction);

        static::assertCount(1, $actions);
        static::assertEquals([$attributeName2, $attributeName1], $action->getAttributeNames());
    }

    /**
     * Test attribute array has no sort changes
     *
     * @return void
     */
    public function testNoChanges()
    {
        $productType = new ProductType([
            'attributes' => [
                [
                    'name' => $attributeName1 = uniqid(),
                    'type' => ['name' => 'text']
                ],
                [
                    'name' => $attributeName2 = uniqid(),
                    'type' => ['name' => 'text']
                ]
            ]
        ]);

        $actions = $this->fixture->createUpdateActions(
            [],
            $this->createMock(ClassMetadataInterface::class),
            [],
            [
                'attributes' => [
                    [
                        'name' => $attributeName1,
                        'type' => ['name' => 'text']
                    ],
                    [
                        'name' => $attributeName2,
                        'type' => ['name' => 'text']
                    ]
                ]
            ],
            $productType
        );

        static::assertCount(0, $actions);
    }

    /**
     * Checks the instance of the builder.
     *
     * @return void
     */
    public function testInstance()
    {
        static::assertInstanceOf(ProductTypeActionBuilder::class, $this->fixture);
    }

    public function testPriority()
    {
        static::assertEquals(-255, (new ChangeOrderOfAttributes())->getPriority());
    }
}
