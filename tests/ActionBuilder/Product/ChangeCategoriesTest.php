<?php

namespace BestIt\CommercetoolsODM\Tests\ActionBuilder\Product;

use BestIt\CommercetoolsODM\ActionBuilder\Product\ChangeCategories;
use BestIt\CommercetoolsODM\ActionBuilder\Product\ProductActionBuilder;
use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use BestIt\CommercetoolsODM\Tests\ActionBuilder\SupportTestTrait;
use Commercetools\Core\Model\Product\Product;
use Commercetools\Core\Request\Products\Command\ProductAddToCategoryAction;
use Commercetools\Core\Request\Products\Command\ProductChangeCategoriesAction;
use Commercetools\Core\Request\Products\Command\ProductRemoveFromCategoryAction;
use PHPUnit\Framework\TestCase;

/**
 * Class ChangeCategoriesTest
 * @author blange <lange@bestit-online.de>
 * @cstegory Tests
 * @package BestIt\CommercetoolsODM
 * @subpackage ActionBuilder\Product
 * @version $id$
 */
class ChangeCategoriesTest extends TestCase
{
    use SupportTestTrait;

    /**
     * The tested class.
     * @var ChangeCategories
     */
    protected $fixture = null;

    /**
     * Returns assertions for the create call.
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
     * Returns an array with the assertions for the upport method.
     *
     * The First Element is the field path, the second element is the reference class and the optional third value
     * indicates the return value of the support method.
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
     * @reteurn void
     */
    protected function setUp()
    {
        $this->fixture = new ChangeCategories();
    }

    /**
     * Checks if the action is rendered correctly.
     * @dataProvider getCreateAssertions
     * @param string $path
     * @param bool $staged
     * @return void
     */
    public function testCreateUpdateActions(string $path, bool $staged = true)
    {
        $this->fixture->supports($path, Product::class);

        $actions = $this->fixture->createUpdateActions(
            [
                [
                    'id' => $newCatId1 = uniqid(),
                ],
                null,
                [
                    'id' => $newCatId2 = uniqid()
                ]
            ],
            static::createMock(ClassMetadataInterface::class),
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
                            ]
                        ]
                    ]
                ]
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
     * Checks the instance type.
     * @return void
     */
    public function testInstance()
    {
        static::assertInstanceOf(ProductActionBuilder::class, $this->fixture);
    }
}
