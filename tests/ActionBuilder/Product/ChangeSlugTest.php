<?php

namespace BestIt\CommercetoolsODM\Tests\ActionBuilder\Product;

use BestIt\CommercetoolsODM\ActionBuilder\Product\ChangeSlug;
use BestIt\CommercetoolsODM\ActionBuilder\Product\ProductActionBuilder;
use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use BestIt\CommercetoolsODM\Tests\ActionBuilder\SupportTestTrait;
use Commercetools\Core\Model\Product\Product;
use Commercetools\Core\Request\Products\Command\ProductChangeSlugAction;
use PHPUnit\Framework\TestCase;

/**
 * Class ChangeSlugTest
 *
 * @author blange <lange@bestit-online.de>
 * @cstegory Tests
 * @package BestIt\CommercetoolsODM\Tests\ActionBuilder\Product
 * @subpackage ActionBuilder\Product
 */
class ChangeSlugTest extends TestCase
{
    use SupportTestTrait;

    /**
     * The tested class.
     *
     * @var ChangeSlug
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
            ['masterData/current/slug', false],
            ['masterData/staged/slug'],
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
            ['masterData/current/slug', Product::class, true],
            ['masterData/staged/slug', Product::class, true],
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
        $this->fixture = new ChangeSlug();
    }

    /**
     * Checks if the action is rendered correctly.
     *
     * @dataProvider getCreateAssertions
     *
     * @param string $path
     * @param bool $staged
     *
     * @return void
     */
    public function testCreateUpdateActions(string $path, bool $staged = true)
    {
        $this->fixture->supports($path, Product::class);

        $actions = $this->fixture->createUpdateActions(
            [
                'de' => $newGer = uniqid(),
                'fr' => null,
                'en' => $newEn = uniqid(),
            ],
            $this->createMock(ClassMetadataInterface::class),
            [],
            [
                'masterData' => [
                    $staged ? 'staged' : 'current' => [
                        'slug' => [
                            'de' => uniqid(),
                            'fr' => uniqid(),
                            'en' => uniqid(),
                        ],
                    ],
                ],
            ],
            new Product()
        );

        /** @var $action ProductChangeSlugAction */
        static::assertCount(1, $actions, 'Wrong action count.');

        static::assertInstanceOf(
            ProductChangeSlugAction::class,
            $action = $actions[0],
            'Wrong instance.'
        );

        static::assertSame(
            ['de' => $newGer, 'en' => $newEn],
            $action->getSlug()->toArray(),
            'Wrong result array.'
        );

        static::assertSame(
            $staged,
            $action->getStaged(),
            'Staged wrongly set.'
        );
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
