<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\Tests\ActionBuilder\Product;

use BestIt\CommercetoolsODM\ActionBuilder\Product\AddVariants;
use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use BestIt\CommercetoolsODM\Tests\ActionBuilder\SupportTestTrait;
use Commercetools\Core\Model\Channel\ChannelReference;
use Commercetools\Core\Model\Common\LocalizedString;
use Commercetools\Core\Model\Common\Money;
use Commercetools\Core\Model\Product\Product;
use Commercetools\Core\Request\Products\Command\ProductAddVariantAction;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

/**
 * @package BestIt\CommercetoolsODM\Tests\ActionBuilder\Product
 */
class AddVariantsTest extends TestCase
{
    use SupportTestTrait;

    /**
     * The test class.
     *
     * @var AddVariants|PHPUnit_Framework_MockObject_MockObject
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
        $this->fixture = new AddVariants();
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
        $sku = 'sku';
        $prices = [
            [
                'value' => Money::ofCurrencyAndAmount('EUR', 100)->toArray(),
                'country' => 'DE',
                'channel' => ChannelReference::ofId('id')->toArray(),
            ],
        ];
        $attributes = [
            [
                'name' => 'attrName',
                'value' => 'attrValue',
            ],
        ];
        $images = [
            [
                'url' => 'https://example.net/test.jpg',
                'label' => 'Image label',
            ],
        ];
        $assets = [
            [
                'key' => 'some_key',
                'name' => new LocalizedString([
                    'de' => 'Some Name',
                ]),
            ],
        ];

        $actions = $this->fixture->createUpdateActions(
            [
                1 => null,
                2 => [
                    'id' => 2,
                    'prices' => [],
                    'attributes' => [],
                ],
                3 => [
                    'prices' => [],
                    'attributes' => [],
                ],
                4 => [
                    'sku' => $sku,
                    'prices' => $prices,
                    'attributes' => $attributes,
                    'images' => $images,
                    'assets' => $assets,
                ],
            ],
            $this->createMock(ClassMetadataInterface::class),
            [],
            [],
            new Product()
        );

        $this->assertCount(1, $actions);

        /** @var ProductAddVariantAction $action */
        $action = $actions[0];

        $this->assertInstanceOf(ProductAddVariantAction::class, $action);
        $this->assertSame($action->getSku(), $sku);
        $this->assertSame($action->getPrices()->toArray(), $prices);
        $this->assertSame($action->getAttributes()->toArray(), $attributes);
        $this->assertSame($action->getImages()->toArray(), $images);
        $this->assertSame($action->getAssets()->toArray(), $assets);
    }

    /**
     * Checks the instance of the builder.
     *
     * @return void
     */
    public function testInstance()
    {
        static::assertInstanceOf(AddVariants::class, $this->fixture);
    }
}
