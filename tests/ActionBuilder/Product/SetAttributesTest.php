<?php

namespace BestIt\CommercetoolsODM\Tests\ActionBuilder\Product;

use BestIt\CommercetoolsODM\ActionBuilder\Product\SetAttributes;
use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use BestIt\CommercetoolsODM\Tests\ActionBuilder\SupportTestTrait;
use Commercetools\Core\Model\Product\Product;
use Commercetools\Core\Request\Products\Command\ProductSetAttributeAction;
use PHPUnit\Framework\TestCase;

/**
 * Class SetAttributesTest
 * @author blange <lange@bestit-online.de>
 * @cstegory Tests
 * @package BestIt\CommercetoolsODM
 * @subpackage ActionBuilder\Product
 * @version $id$
 */
class SetAttributesTest extends TestCase
{
    use SupportTestTrait;

    /**
     * The tested class.
     * @var ChangeName
     */
    protected $fixture = null;

    /**
     * Returns assertions for the create call.
     * @return array
     */
    public function getCatalogs(): array
    {
        return [
            ['current'],
            ['staged', true]
        ];
    }

    /**
     * Returns an array with the assertions for the upport method.
     *
     * The First Element is the field path, the second element is the reference class and the optional third value
     * indicates the return value of the support method.
     * @return array
     */
    public function getSupportAssertions(): array
    {
        return [
            ['masterData/current/masterVariant/attributes', Product::class, true],
            ['masterData/current/variants/1/attributes', Product::class, true],
            ['masterData/current/variants/10/attributes', Product::class, true],
            ['masterData/current/variants/f/attributes', Product::class],
            ['masterData/staged/masterVariant/attributes', Product::class, true],
            ['masterData/staged/variants/1/attributes', Product::class, true],
            ['masterData/staged/variants/10/attributes', Product::class, true],
            ['masterData/staged/variants/f/attributes', Product::class],
            ['masterData/current/variants/1/sku', Product::class],
            ['masterData/current/variants/100/sku', Product::class],
            ['masterData/staged/masterVariant/sku', Product::class],
            ['masterData/staged/variants/1/sku', Product::class],
            ['masterData/staged/variants/100/sku', Product::class],
        ];
    }

    /**
     * Sets up the test.
     * @reteurn void
     */
    protected function setUp()
    {
        $this->fixture = new SetAttributes();
    }

    /**
     * Checks if the master variant attributes can be changed.
     * @dataProvider getCatalogs
     * @param string $container
     * @param bool $staged
     */
    public function testCreateUpdateActionsForMasterVariant(string $container, bool $staged = false)
    {
        $this->fixture->supports("masterData/{$container}/masterVariant/attributes", Product::class);

        $actions = $this->fixture->createUpdateActions(
            [
                [
                    'value' => $mockedValue = uniqid()
                ]
            ],
            static::createMock(ClassMetadataInterface::class),
            [],
            [
                'masterData' => [
                    $container => [
                        'masterVariant' => [
                            'attributes' => [
                                [
                                    'name' => $attrName = 'manufacturer',
                                    'value' => uniqid()
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            new Product()
        );

        static::assertCount(1, $actions, 'Wrong action count.');

        /** @var $action ProductSetAttributeAction */
        static::assertInstanceOf(
            ProductSetAttributeAction::class,
            $action = $actions[0],
            'Wrong instance.'
        );

        static::assertSame(1, $action->getVariantId(), 'Wrong variant id.');
        static::assertSame($attrName, $action->getName(), 'Wrong name.');
        static::assertSame($mockedValue, $action->getValue(), 'Wrong value');
        static::assertSame($staged, $action->getStaged(), 'Staged wrongly set.');
    }

    /**
     * Checks if variant attributes can be changed.
     * @dataProvider getCatalogs
     * @param string $container
     * @param bool $staged
     */
    public function testCreateUpdateActionsForVariantAttributes(string $container, bool $staged = false)
    {
        $this->fixture->supports(
            sprintf('masterData/%s/variants/%s/attributes', $container, $variantId = mt_rand(0, 1000)),
            Product::class
        );

        $actions = $this->fixture->createUpdateActions(
            [
                [
                    'value' => $mockedValue1 = uniqid()
                ],
                [
                    null
                ]
            ],
            static::createMock(ClassMetadataInterface::class),
            [],
            [
                'masterData' => [
                    $container => [
                        'variants' => [
                            $variantId => [
                                'id' => $variantId,
                                'attributes' => [
                                    [
                                        'name' => $attrName1 = 'manufacturer',
                                        'value' => uniqid()
                                    ],
                                    [
                                        'name' => $attrName2 = 'manufacturer',
                                        'value' => uniqid()
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            new Product()
        );

        static::assertCount(2, $actions, 'Wrong action count.');

        /** @var $action ProductSetAttributeAction */
        static::assertInstanceOf(
            ProductSetAttributeAction::class,
            $action = $actions[0],
            'Wrong instance.'
        );

        static::assertSame($variantId, $action->getVariantId(), 'Wrong variant id.');
        static::assertSame($attrName1, $action->getName(), 'Wrong name.');
        static::assertSame($mockedValue1, $action->getValue(), 'Wrong value');
        static::assertSame($staged, $action->getStaged(), 'Staged wrongly set.');

        /** @var $action ProductSetAttributeAction */
        static::assertInstanceOf(
            ProductSetAttributeAction::class,
            $action = $actions[1],
            'Wrong instance.'
        );

        static::assertSame($variantId, $action->getVariantId(), 'Wrong variant id.');
        static::assertSame($attrName2, $action->getName(), 'Wrong name.');
        static::assertSame(null, $action->getValue(), 'Wrong value');
        static::assertSame($staged, $action->getStaged(), 'Staged wrongly set.');
    }
}
