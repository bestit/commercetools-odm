<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\Tests\ActionBuilder\Category;

use BestIt\CommercetoolsODM\ActionBuilder\Category\CategoryActionBuilder;
use BestIt\CommercetoolsODM\ActionBuilder\Category\ChangeParent;
use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use BestIt\CommercetoolsODM\Tests\ActionBuilder\SupportTestTrait;
use Commercetools\Core\Model\Category\Category;
use Commercetools\Core\Request\Categories\Command\CategoryChangeParentAction;
use PHPUnit\Framework\TestCase;

/**
 * Class ChangeParentTest
 *
 * @cstegory Tests
 * @package BestIt\CommercetoolsODM\Tests\ActionBuilder\Category
 * @subpackage ActionBuilder\Category
 */
class ChangeParentTest extends TestCase
{
    use SupportTestTrait;

    /**
     * The tested class.
     *
     * @var ChangeParent
     */
    protected $fixture = null;

    /**
     * Sets up the test.
     *
     * @return void
     */
    protected function setUp()
    {
        $this->fixture = new ChangeParent();
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
            ['parent', Category::class, true],
            ['orderHint', Category::class, false],
            ['name', Category::class, false],
            ['slug', Category::class, false],
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
                'key' => $key = uniqid(),
                'id' => null,
            ],
            $this->createMock(ClassMetadataInterface::class),
            [],
            [
                'parent' => [
                    'typeId' => 'category',
                    'id' => '10482701-4fcf-4e57-a61a-0906a34987c5',
                ],
            ],
            new Category()
        );

        static::assertCount(1, $actions, 'Wrong action count.');

        /** @var $action CategoryChangeParentAction */
        static::assertInstanceOf(
            CategoryChangeParentAction::class,
            $action = $actions[0],
            'Wrong instance.'
        );

        static::assertSame(
            $key,
            $action->getParent()->getKey(),
            'Wrong parent key result.'
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
