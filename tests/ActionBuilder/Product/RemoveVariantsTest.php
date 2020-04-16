<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\Tests\ActionBuilder\Product;

use BestIt\CommercetoolsODM\ActionBuilder\Product\RemoveVariants;
use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use BestIt\CommercetoolsODM\Tests\ActionBuilder\SupportTestTrait;
use Commercetools\Core\Model\Product\Product;
use Commercetools\Core\Request\Products\Command\ProductRemoveVariantAction;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

/**
 * @package BestIt\CommercetoolsODM\Tests\ActionBuilder\Product
 */
class RemoveVariantsTest extends TestCase
{
    use SupportTestTrait;

    /**
     * The test class.
     *
     * @var RemoveVariants|PHPUnit_Framework_MockObject_MockObject
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
            ['masterData/current/variants', Product::class, true],
            ['masterData/staged/variants', Product::class, true],
            ['masterData/current/variant', Product::class],
            ['masterData/staged/variant', Product::class],
            ['variants', Product::class],
        ];
    }

    /**
     * Sets up the test.
     *
     * @return void
     */
    public function setUp()
    {
        $this->fixture = new RemoveVariants();
    }

    /**
     * @return void
     */
    public function testItJustReturnsAnEmptyArrayIfTheInputIsNotAnArray()
    {
        $actions = $this->fixture->createUpdateActions(
            'input',
            $this->createMock(ClassMetadataInterface::class),
            [],
            [],
            new Product()
        );

        $this->assertEmpty($actions);
    }

    /**
     * @return void
     */
    public function testItCreatesTheProductAddVariantActionsForAddedVariants()
    {
        $skuToBeRemoved = 'sku';
        $indexOfVariantToBeRemoved = 1;

        $actions = $this->fixture->createUpdateActions(
            [
                $indexOfVariantToBeRemoved => null,
                2 => [
                    'id' => 2,
                    'prices' => [],
                    'attributes' => [],
                ],
                3 => [
                    'sku' => 'some_sku',
                    'prices' => [],
                    'attributes' => [],
                ],
            ],
            $this->createMock(ClassMetadataInterface::class),
            [],
            [],
            new Product([
                'masterData' => [
                    'current' => [
                        'variants' => [
                            $indexOfVariantToBeRemoved => [
                                'sku' => $skuToBeRemoved,
                            ],
                        ],
                    ],
                ],
            ])
        );

        $this->assertCount(1, $actions);

        /** @var ProductRemoveVariantAction $action */
        $action = $actions[0];

        $this->assertInstanceOf(ProductRemoveVariantAction::class, $action);
        $this->assertSame($action->getSku(), $skuToBeRemoved);
    }

    /**
     * Checks the instance of the builder.
     *
     * @return void
     */
    public function testInstance()
    {
        static::assertInstanceOf(RemoveVariants::class, $this->fixture);
    }

    /**
     * @return void
     */
    public function testPriorityIs1()
    {
        $this->assertSame(1, $this->fixture->getPriority());
    }
}
