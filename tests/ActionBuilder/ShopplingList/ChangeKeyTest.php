<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\Tests\ActionBuilder\ShoppingList;

use BestIt\CommercetoolsODM\ActionBuilder\ShoppingList\ChangeKey;
use BestIt\CommercetoolsODM\ActionBuilder\ShoppingList\ShoppingListActionBuilder;
use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use BestIt\CommercetoolsODM\Tests\ActionBuilder\SupportTestTrait;
use Commercetools\Core\Model\ShoppingList\ShoppingList;
use Commercetools\Core\Request\ShoppingLists\Command\ShoppingListSetKeyAction;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

/**
 * Test ChangeKey.
 *
 * @author blange <bjoern.lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\Tests\ActionBuilder\ShoppingList
 */
class ChangeKeyTest extends TestCase
{
    use SupportTestTrait;

    /**
     * @var ChangeKey|PHPUnit_Framework_MockObject_MockObject The test class.
     */
    protected $fixture;


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
            ['key', ShoppingList::class, true],
            ['keys', ShoppingList::class],
        ];
    }

    /**
     * Sets up the test.
     *
     * @return void
     */
    public function setUp()
    {
        $this->fixture = new ChangeKey();
    }

    /**
     * Checks if the action is rendered correctly.
     *
     * @return void
     */
    public function testCreateUpdateActions()
    {
        $actions = $this->fixture->createUpdateActions(
            $key = uniqid(),
            $this->createMock(ClassMetadataInterface::class),
            [],
            [],
            new ShoppingList()
        );

        /** @var $action ShoppingListSetKeyAction */
        static::assertCount(1, $actions, 'Wrong action count.');
        static::assertInstanceOf(ShoppingListSetKeyAction::class, $action = $actions[0], 'Wrong instance.');
        static::assertSame($key, $action->getKey(), 'Wrong key.');
    }

    /**
     * Checks the instance of the builder.
     *
     * @return void
     */
    public function testInstance()
    {
        static::assertInstanceOf(ShoppingListActionBuilder::class, $this->fixture);
    }
}
