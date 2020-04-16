<?php

namespace BestIt\CommercetoolsODM\Tests\ActionBuilder\Product;

use BestIt\CommercetoolsODM\ActionBuilder\Product\ChangeName;
use BestIt\CommercetoolsODM\ActionBuilder\Product\ChangePrices;
use BestIt\CommercetoolsODM\ActionBuilder\Product\ProductActionBuilder;
use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use BestIt\CommercetoolsODM\Tests\ActionBuilder\SupportTestTrait;
use Commercetools\Core\Model\Common\PriceCollection;
use Commercetools\Core\Model\Product\Product;
use Commercetools\Core\Model\Product\ProductCatalogData;
use Commercetools\Core\Model\Product\ProductData;
use Commercetools\Core\Model\Product\ProductVariant;
use Commercetools\Core\Request\Products\Command\ProductChangeNameAction;
use PHPUnit\Framework\TestCase;

/**
 * Class ChangePricesTest
 *
 * @package BestIt\CommercetoolsODM\Tests\ActionBuilder\Product
 */
class ChangePricesTest extends TestCase
{
    /**
     * The tested class.
     *
     * @var ChangeName
     */
    protected $fixture = null;

    /**
     * Sets up the test.
     *
     * @return void
     */
    protected function setUp()
    {
        $this->fixture = new ChangePrices();
    }

    /**
     * @return void
     */
    public function testItReturnsAnEmptyArrayIfOffsetIsSetButThePriceIsNull()
    {
        $index = 1;

        $priceCollection = new PriceCollection();
        $priceCollection->setAt($index, null);

        $product = Product::fromArray([
            'masterData' => [
                'staged' => [
                    'masterVariant' => [
                        'id' => 1,
                    ],
                ],
            ],
        ]);

        $product->getMasterData()->getStaged()->getMasterVariant()->setPrices($priceCollection);

        $this->fixture->setLastFoundMatch([
            '',
            'staged',
            'masterVariant',
            '',
        ]);

        $changePriceActions = $this->fixture->createUpdateActions(
            [$index => []],
            $this->createMock(ClassMetadataInterface::class),
            [],
            [],
            $product
        );

        $this->assertEmpty($changePriceActions);
    }

    /**
     * @return void
     */
    public function testItReturnsNothingIfTheVariantCouldNotBeFound()
    {
        $this->fixture->setLastFoundMatch([
            '',
            'staged',
            'variants',
            $variantIndex = 0,
        ]);

        $product = Product::fromArray([
            'masterData' => [
                'staged' => [
                    'masterVariant' => [
                        'id' => 1,
                    ],
                    'variants' => [],
                ],
                'current' => [
                    'masterVariant' => [
                        'id' => 1,
                    ],
                    'variants' => [],
                ],
            ],
        ]);


        $changePriceActions = $this->fixture->createUpdateActions(
            [],
            $this->createMock(ClassMetadataInterface::class),
            [],
            [],
            $product
        );

        $this->assertEmpty($changePriceActions);
    }

    /**
     * @return void
     */
    public function testPriorityIs3()
    {
        $this->assertSame(3, $this->fixture->getPriority());
    }
}
