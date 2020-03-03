<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\Tests\ActionBuilder\Category;

use BestIt\CommercetoolsODM\ActionBuilder\Category\CategoryActionBuilder;
use BestIt\CommercetoolsODM\ActionBuilder\Category\ChangeOrderHint;
use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use BestIt\CommercetoolsODM\Tests\ActionBuilder\SupportTestTrait;
use Commercetools\Core\Model\Category\Category;
use Commercetools\Core\Request\Categories\Command\CategoryChangeOrderHintAction;
use PHPUnit\Framework\TestCase;

/**
 * Class ChangeOrderHintTest
 *
 * @cstegory Tests
 * @package BestIt\CommercetoolsODM\Tests\ActionBuilder\Category
 * @subpackage ActionBuilder\Category
 */
class ChangeOrderHintTest extends TestCase
{
    use SupportTestTrait;

    /**
     * The tested class.
     *
     * @var ChangeOrderHint
     */
    protected $fixture = null;

    /**
     * Sets up the test.
     *
     * @return void
     */
    protected function setUp()
    {
        $this->fixture = new ChangeOrderHint();
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
            ['orderHint', Category::class, true],
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
            $orderHint = '5',
            $this->createMock(ClassMetadataInterface::class),
            [],
            [
                (string) random_int(0, 100),
            ],
            new Category()
        );

        static::assertCount(1, $actions, 'Wrong action count.');

        /** @var $action CategoryChangeOrderHintAction */
        static::assertInstanceOf(
            CategoryChangeOrderHintAction::class,
            $action = $actions[0],
            'Wrong instance.'
        );

        static::assertSame(
            $orderHint,
            $action->getOrderHint(),
            'Wrong order hint result.'
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
