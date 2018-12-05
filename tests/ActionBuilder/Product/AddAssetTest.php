<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\Tests\ActionBuilder\Product;

use BestIt\CommercetoolsODM\ActionBuilder\Product\AddAssets;
use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use BestIt\CommercetoolsODM\Tests\ActionBuilder\SupportTestTrait;
use Commercetools\Core\Model\Product\Product;
use Commercetools\Core\Request\Products\Command\ProductAddAssetAction;
use PHPUnit\Framework\TestCase;
use function uniqid;

/**
 * Class AddAssetsTest.
 *
 * @author blange <bjoern.lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\Tests\ActionBuilder\Product
 * @todo Add more unit tests.
 */
class AddAssetsTest extends TestCase
{
    use SupportTestTrait;

    /**
     * Returns an array with the assertions for the support method.
     *
     * The First Element is the field path, the second element is the reference class and the optional third value
     * indicates the return value of the support method.
     * @return array
     */
    public function getSupportAssertions(): array
    {
        return [
            ['masterData/current/masterVariant/assets', Product::class, true],
            ['masterData/current/variants/1/assets', Product::class, true],
            ['masterData/current/variants/10/assets', Product::class, true],
            ['masterData/current/variants/f/assets', Product::class],
            ['masterData/staged/masterVariant/assets', Product::class, true],
            ['masterData/staged/variants/1/assets', Product::class, true],
            ['masterData/staged/variants/10/assets', Product::class, true],
            ['masterData/staged/variants/f/assets', Product::class],
            ['masterData/current/variants/1/sku', Product::class],
            ['masterData/current/variants/100/sku', Product::class],
            ['masterData/staged/masterVariant/sku', Product::class],
            ['masterData/staged/variants/1/sku', Product::class],
            ['masterData/staged/variants/100/sku', Product::class],
        ];
    }

    /**
     * Sets up the test.
     *
     * @reteurn void
     */
    protected function setUp()
    {
        $this->fixture = new AddAssets();
    }

    /**
     * Checks if the update action is rendered correctly.
     *
     * @return void
     */
    public function testCreateUpdateActionsAddTwo()
    {
        $this->fixture->setLastFoundMatch([uniqid(), 'current', 'masterVariant', null]);

        $updateActions = $this->fixture->createUpdateActions(
            [
                $asset1 = [
                    'sources' => [
                        [
                            'contentType' => 'application/pdf',
                            'uri' => uniqid(),
                        ],
                    ],
                    'name' => ['de' => 'asset1'],
                    'description' => ['de' => 'asset1'],
                    'tags' => ['asset1',],
                ],
                $ignoreAssetBecauseWithId = [
                    'id' => uniqid(),
                    'sources' => [
                        [
                            'contentType' => 'application/pdf',
                            'uri' => uniqid(),
                        ],
                    ],
                    'name' => ['de' => 'ignoreAssetBecauseWithId'],
                    'description' => ['de' => 'ignoreAssetBecauseWithId'],
                    'tags' => ['ignoreAssetBecauseWithId',],
                ],
                $asset2 = [
                    'sources' => [
                        [
                            'contentType' => 'application/pdf',
                            'uri' => uniqid(),
                        ],
                    ],
                    'name' => ['de' => 'asset2'],
                    'description' => ['de' => 'asset2'],
                    'tags' => ['asset2',],
                ],
            ],
            $this->createMock(ClassMetadataInterface::class),
            [],
            [],
            new Product()
        );

        static::assertCount(2, $updateActions, 'To many updates created');

        static::assertInstanceOf(
            ProductAddAssetAction::class,
            $action = $updateActions[0],
            'Wrong instance (1).'
        );

        static::assertSame(1, $action->get('variantId'), 'Wrong variant id (1).');

        static::assertSame($asset1, $action->get('asset')->toArray(), 'Wrong asset (1).');

        static::assertInstanceOf(
            ProductAddAssetAction::class,
            $action = $updateActions[1],
            'Wrong instance (2).'
        );

        static::assertSame(1, $action->get('variantId'), 'Wrong variant id (2).');

        static::assertSame($asset2, $action->get('asset')->toArray(), 'Wrong asset (2).');
    }
}
