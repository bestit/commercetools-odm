<?php

namespace BestIt\CommercetoolsODM\Tests\ActionBuilder\Product;

use BestIt\CommercetoolsODM\ActionBuilder\Product\ProductActionBuilder;
use BestIt\CommercetoolsODM\ActionBuilder\Product\SetPrices;
use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use BestIt\CommercetoolsODM\Tests\ActionBuilder\SupportTestTrait;
use Commercetools\Core\Model\Common\PriceDraft;
use Commercetools\Core\Model\Common\PriceDraftCollection;
use Commercetools\Core\Model\Product\Product;
use Commercetools\Core\Request\Products\Command\ProductSetPricesAction;
use PHPUnit\Framework\TestCase;

/**
 * Class SetPricesTest
 *
 * @author Michel Chowanski <michel.chowanski@bestit.de>
 * @cstegory Tests
 * @package BestIt\CommercetoolsODM\Tests\ActionBuilder\Product
 * @subpackage ActionBuilder\Product
 */
class SetPricesTest extends TestCase
{
    use SupportTestTrait;

    /**
     * The tested class.
     *
     * @var SetPrices
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
            // Action should match for master variant
            [
                'masterData/current/masterVariant/prices',
                1,
                [['value' => ['centAmount' => 200]]],
                1,
            ],

            // Action should match for normal variant
            [
                'masterData/current/variants/0/prices',
                2,
                [['value' => ['centAmount' => 333]], ['value' => ['centAmount' => 444]]],
                1,
            ],

            // Action should not match for invalid variant
            [
                'masterData/current/variants/7/prices',
                7,
                [['value' => ['centAmount' => 333]], ['value' => ['centAmount' => 444]]],
                0,
            ],
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
            ['masterData/current/masterVariant/prices', Product::class, true],
            ['masterData/current/variants/1/prices', Product::class, true],
            ['masterData/current/variants/100/prices', Product::class, true],
            ['masterData/staged/masterVariant/prices', Product::class, true],
            ['masterData/staged/variants/1/prices', Product::class, true],
            ['masterData/staged/variants/100/prices', Product::class, true],
            ['masterData/current/masterVariant', Product::class],
            ['masterData/current/variants/foo/prices', Product::class],
            ['masterData/staged/variants/foo/prices', Product::class],
        ];
    }

    /**
     * Sets up the test.
     *
     * @return void
     */
    protected function setUp()
    {
        $this->fixture = new SetPrices();
    }

    /**
     * Checks if the action is rendered correctly.
     *
     * @dataProvider getCreateAssertions
     *
     * @param string $path
     * @param int $variantId
     * @param array $prices
     *
     * @return void
     */
    public function testCreateUpdateActions(string $path, int $variantId, array $prices, int $totalActions)
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
                        'masterVariant' => [
                            'id' => 1,
                            'prices' => $prices,
                        ],
                        'variants' => [
                            [
                                'id' => $variantId,
                                'prices' => $prices,
                            ],
                        ],
                    ],
                ],
            ])
        );

        /** @var $action ProductSetPricesAction */
        static::assertCount($totalActions, $actions, 'Wrong action count.');

        if ($totalActions > 0) {
            static::assertInstanceOf(ProductSetPricesAction::class, $action = $actions[0], 'Wrong instance.');

            $expectedPrices = array_map(function (array $price) {
                return PriceDraft::fromArray($price);
            }, $prices);

            static::assertEquals(
                ProductSetPricesAction::ofVariantIdAndPrices(
                    $variantId,
                    PriceDraftCollection::fromArray($expectedPrices)
                ),
                $action
            );
        }
    }

    /**
     * Checks the instance type.
     *
     * @return void
     */
    public function testInstance()
    {
        static::assertInstanceOf(ProductActionBuilder::class, $this->fixture);
    }
}
