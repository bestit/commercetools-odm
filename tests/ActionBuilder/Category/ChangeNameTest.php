<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\Tests\ActionBuilder\Category;

use BestIt\CommercetoolsODM\ActionBuilder\Category\CategoryActionBuilder;
use BestIt\CommercetoolsODM\ActionBuilder\Category\ChangeName;
use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use BestIt\CommercetoolsODM\Tests\ActionBuilder\SupportTestTrait;
use Commercetools\Core\Model\Category\Category;
use Commercetools\Core\Request\Categories\Command\CategoryChangeNameAction;
use PHPUnit\Framework\TestCase;

/**
 * Class ChangeNameTest
 *
 * @cstegory Tests
 * @package BestIt\CommercetoolsODM\Tests\ActionBuilder\Category
 * @subpackage ActionBuilder\Category
 */
class ChangeNameTest extends TestCase
{
    use SupportTestTrait;

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
        $this->fixture = new ChangeName();
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
            ['name', Category::class, true],
            ['slug', Category::class, false],
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
        $actions = $this->fixture->createUpdateActions(
            [
                'de' => $newGer = uniqid(),
                'fr' => null,
                'en' => $newEn = uniqid()
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
            new Category()
        );

        static::assertCount(1, $actions, 'Wrong action count.');

        /** @var $action CategoryChangeNameAction */
        static::assertInstanceOf(
            CategoryChangeNameAction::class,
            $action = $actions[0],
            'Wrong instance.'
        );

        static::assertSame(
            ['de' => $newGer, 'en' => $newEn],
            $action->getName()->toArray(),
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
