<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\Tests\ActionBuilder\Category;

use BestIt\CommercetoolsODM\ActionBuilder\Category\CategoryActionBuilder;
use BestIt\CommercetoolsODM\ActionBuilder\Category\ChangeSlug;
use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use BestIt\CommercetoolsODM\Tests\ActionBuilder\SupportTestTrait;
use Commercetools\Core\Model\Category\Category;
use Commercetools\Core\Model\Common\LocalizedString;
use Commercetools\Core\Request\Categories\Command\CategoryChangeSlugAction;
use PHPUnit\Framework\TestCase;

/**
 * Class ChangeSlugTest
 *
 * @cstegory Tests
 * @package BestIt\CommercetoolsODM\Tests\ActionBuilder\Category
 * @subpackage ActionBuilder\Category
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
     * Sets up the test.
     *
     * @return void
     */
    protected function setUp()
    {
        $this->fixture = new ChangeSlug();
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
            ['slug', Category::class, true],
            ['name', Category::class, false],
            ['orderHint', Category::class, false],
        ];
    }

    /**
     * Checks if the action is rendered correctly.
     *
     * @return void
     */
    public function testCreateUpdateActions()
    {
        $category = new Category();
        $category->setSlug(new LocalizedString([
            'de' => $newGer = uniqid(),
            'en' => $newEn = uniqid(),
        ]));

        $actions = $this->fixture->createUpdateActions(
            [
                'de' => $newGer,
            ],
            $this->createMock(ClassMetadataInterface::class),
            [],
            [
                'name' => [
                    'de' => uniqid(),
                    'fr' => uniqid(),
                    'en' => uniqid(),
                ],
            ],
            $category
        );

        static::assertCount(1, $actions, 'Wrong action count.');

        /** @var $action CategoryChangeSlugAction */
        static::assertInstanceOf(
            CategoryChangeSlugAction::class,
            $action = $actions[0],
            'Wrong instance.'
        );

        static::assertSame(
            ['de' => $newGer, 'en' => $newEn],
            $action->getSlug()->toArray(),
            'Wrong result array.'
        );
    }

    /**
     * Checks the instance type.
     *
     * @return void
     */
    public function testInstance()
    {
        static::assertInstanceOf(CategoryActionBuilder::class, $this->fixture);
    }
}
