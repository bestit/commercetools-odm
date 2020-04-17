<?php

namespace BestIt\CommercetoolsODM\Tests\ActionBuilder\Product;

use BestIt\CommercetoolsODM\ActionBuilder\Product\ProductActionBuilder;
use BestIt\CommercetoolsODM\ActionBuilder\Product\SetSKU;
use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use BestIt\CommercetoolsODM\Tests\ActionBuilder\SupportTestTrait;
use Commercetools\Core\Model\Product\Product;
use Commercetools\Core\Request\Products\Command\ProductSetSkuAction;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

/**
 * Checks if the sku action is built.
 *
 * @author blange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\Tests\ActionBuilder\Product
 * @subpackage ActionBuilder\Product
 */
class SetSKUTest extends TestCase
{
    use SupportTestTrait;

    /**
     * The test class.
     *
     * @var SetSKU|PHPUnit_Framework_MockObject_MockObject
     */
    protected $fixture = null;

    /**
     * Returns assertions for the create call.
     *
     * @return array
     */
    public function getCreateAssertions(): array
    {
        return [
            ['masterData/current/masterVariant/sku', '', false, 1],
            ['masterData/staged/masterVariant/sku', ''],
            ['masterData/current/variants/0/sku', 0, false, 2],
            ['masterData/current/variants/5/sku', 5, false, 7],
        ];
    }

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
            ['masterData/current/masterVariant/sku', Product::class, true],
            ['masterData/current/variants/1/sku', Product::class, true],
            ['masterData/current/variants/100/sku', Product::class, true],
            ['masterData/staged/masterVariant/sku', Product::class, true],
            ['masterData/staged/variants/1/sku', Product::class, true],
            ['masterData/staged/variants/100/sku', Product::class, true],
            ['masterData/current/masterVariant', Product::class],
            ['masterData/current/variants/foo/sku', Product::class],
            ['masterData/staged/variants/foo/sku', Product::class],
        ];
    }

    /**
     * Sets up the test.
     *
     * @return void
     */
    public function setUp()
    {
        $this->fixture = new SetSKU();
    }

    /**
     * Checks if the action is rendered correctly.
     *
     * @dataProvider getCreateAssertions
     *
     * @param string $path
     * @param string|int $variantIndex
     * @param bool $staged
     * @param int $variantId
     *
     * @return void
     */
    public function testCreateUpdateActions(string $path, $variantIndex, bool $staged = true, int $variantId = 1)
    {
        $this->fixture->supports($path, Product::class);

        $actions = $this->fixture->createUpdateActions(
            $sku = uniqid(),
            $this->createMock(ClassMetadataInterface::class),
            [],
            [],
            new Product([
                'masterData' => [
                    'current' => [
                        'variants' => [
                            $variantIndex => [
                                'id' => $variantId,
                            ],
                        ],
                    ],
                ],
            ])
        );

        /** @var $action ProductSetSkuAction */
        static::assertCount(1, $actions, 'Wrong action count.');
        static::assertInstanceOf(ProductSetSkuAction::class, $action = $actions[0], 'Wrong instance.');
        static::assertSame($sku, $action->getSku(), 'Wrong sku.');
        static::assertSame($staged, $action->getStaged(), 'Wrong staged status.');
        static::assertSame($variantId, $action->getVariantId(), 'Wrong variant id.');
    }

    /**
     * Checks if the action is rendered correctly.
     *
     * @return void
     */
    public function testSetSkuActionIsNotReturnedIfTheVariantDoesNotExist()
    {
        $variantId = 5;

        $this->fixture->setLastFoundMatch([
            'masterData',
            'current',
            'variants',
            $variantId
        ]);

        $actions = $this->fixture->createUpdateActions(
            $sku = uniqid(),
            $this->createMock(ClassMetadataInterface::class),
            [],
            [],
            new Product([
                'masterData' => [
                    'current' => [
                        'variants' => [
                        ],
                    ],
                ],
            ])
        );

        static::assertEmpty($actions, 'Wrong action count.');
    }

    /**
     * Checks the instance of the builder.
     *
     * @return void
     */
    public function testInstance()
    {
        static::assertInstanceOf(ProductActionBuilder::class, $this->fixture);
    }
}
