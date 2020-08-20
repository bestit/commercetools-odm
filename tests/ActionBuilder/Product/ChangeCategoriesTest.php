<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\Tests\ActionBuilder\Product;

use BestIt\CommercetoolsODM\ActionBuilder\Product\ChangeCategories;
use BestIt\CommercetoolsODM\ActionBuilder\Product\ProductActionBuilder;
use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use BestIt\CommercetoolsODM\Tests\ActionBuilder\SupportTestTrait;
use Commercetools\Core\Model\Product\Product;
use Commercetools\Core\Request\Products\Command\ProductAddToCategoryAction;
use Commercetools\Core\Request\Products\Command\ProductRemoveFromCategoryAction;
use PHPUnit\Framework\TestCase;

/**
 * Class ChangeCategoriesTest
 *
 * @author blange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\Tests\ActionBuilder\Product
 */
class ChangeCategoriesTest extends TestCase
{
    use SupportTestTrait;

    /**
     * The tested class.
     *
     * @var ChangeCategories|null
     */
    protected $fixture;

    /**
     * Returns assertions for the create call.
     *
     * @return array
     */
    public function getCreateAssertions(): array
    {
        return [
            ['masterData/current/categories', false],
            ['masterData/staged/categories'],
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
            ['masterData/current/categories', Product::class, true],
            ['masterData/staged/categories', Product::class, true],
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
     * @return void
     */
    protected function setUp()
    {
        $this->fixture = new ChangeCategories();
    }

    /**
     * Checks if old ids are removed and new ones added.
     *
     * @dataProvider getCreateAssertions
     *
     * @param string $path
     * @param bool $staged
     *
     * @return void
     */
    public function testCreateUpdateActionsWithOldData(string $path, bool $staged = true)
    {
        $this->fixture->supports($path, Product::class);

        $actions = $this->fixture->createUpdateActions(
            [
                [
                    'id' => $newCatId1 = uniqid(),
                ],
                null,
                [
                    'id' => $newCatId2 = uniqid(),
                ],
            ],
            $this->createMock(ClassMetadataInterface::class),
            [],
            [
                'masterData' => [
                    $staged ? 'staged' : 'current' => [
                        'categories' => [
                            [
                                'typeId' => 'category',
                                'id' => $oldCatId1 = uniqid(),
                            ],
                            [
                                'typeId' => 'category',
                                'id' => $oldCatId2 = uniqid(),
                            ],
                            [
                                'typeId' => 'category',
                                'id' => $oldCatId3 = uniqid(),
                            ],
                        ],
                    ],
                ],
            ],
            new Product()
        );

        static::assertCount(5, $actions, 'Wrong action count.');

        $map = [
            // Delete query, deleted/added id
            [true, $oldCatId1],
            [false, $newCatId1],
            [true, $oldCatId2],
            [true, $oldCatId3],
            [false, $newCatId2],
        ];

        foreach ($map as $actionIndex => $actionRules) {
            static::assertInstanceOf(
                $actionRules[0] ? ProductRemoveFromCategoryAction::class : ProductAddToCategoryAction::class,
                $actions[$actionIndex],
                'Wrong instance ' . $actionIndex
            );

            static::assertSame(
                $actionRules[1],
                $actions[$actionIndex]->getCategory()->getId(),
                'Wrong id ' . $actionIndex
            );

            static::assertSame($staged, $actions[$actionIndex]->getStaged(), 'Wrong staged ' . $actionIndex);
        }
    }

    /**
     * Checks if new ones are added.
     *
     * @dataProvider getCreateAssertions
     *
     * @param string $path
     * @param bool $staged
     *
     * @return void
     */
    public function testCreateUpdateActionsWithoutOldData(string $path, bool $staged = true)
    {
        $this->fixture->supports($path, Product::class);

        $checkCats = [];
        $actions = $this->fixture->createUpdateActions(
            [
                [
                    'id' => $checkCats[] = uniqid(),
                ],
                null,
                [
                    'id' => $checkCats[] = uniqid(),
                ],
            ],
            $this->createMock(ClassMetadataInterface::class),
            [],
            [
                'masterData' => [
                    $staged ? 'staged' : 'current' => [
                        'categories' => [],
                    ],
                ],
            ],
            new Product()
        );

        static::assertCount(2, $actions, 'Wrong action count.');

        foreach ($checkCats as $index => $checkCatId) {
            static::assertInstanceOf(
                ProductAddToCategoryAction::class,
                $actions[$index],
                'Wrong instance for ' . $checkCatId
            );

            static::assertSame(
                $checkCatId,
                $actions[$index]->getCategory()->getId(),
                'Wrong id for ' . $checkCatId
            );

            static::assertSame($staged, $actions[$index]->getStaged(), 'Wrong staged for ' . $checkCatId);
        }
    }

    /**
     * Checks if new ones are added using keys.
     *
     * @dataProvider getCreateAssertions
     *
     * @param string $path
     * @param bool $staged
     *
     * @return void
     */
    public function testCreateUpdateActionsWithoutOldDataAndKeys(string $path, bool $staged = true)
    {
        $this->fixture->supports($path, Product::class);

        $checkCats = [];
        $actions = $this->fixture->createUpdateActions(
            [
                [
                    'id' => null,
                    'key' => $checkCats[] = uniqid(),
                ],
                null,
                [
                    'key' => $checkCats[] = uniqid(),
                ],
            ],
            $this->createMock(ClassMetadataInterface::class),
            [],
            [
                'masterData' => [
                    $staged ? 'staged' : 'current' => [
                        'categories' => [],
                    ],
                ],
            ],
            new Product()
        );

        static::assertCount(2, $actions, 'Wrong action count.');

        foreach ($checkCats as $index => $checkCatKey) {
            static::assertInstanceOf(
                ProductAddToCategoryAction::class,
                $actions[$index],
                'Wrong instance for ' . $checkCatKey
            );

            static::assertSame(
                $checkCatKey,
                $actions[$index]->getCategory()->getKey(),
                'Wrong id for ' . $checkCatKey
            );

            static::assertSame($staged, $actions[$index]->getStaged(), 'Wrong staged for ' . $checkCatKey);
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
