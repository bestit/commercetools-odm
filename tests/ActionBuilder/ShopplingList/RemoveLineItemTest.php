<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\Tests\ActionBuilder\ShoppingList;

use BestIt\CommercetoolsODM\ActionBuilder\ShoppingList\RemoveLineItem;
use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use BestIt\CommercetoolsODM\Tests\ActionBuilder\SupportTestTrait;
use Commercetools\Core\Model\ShoppingList\ShoppingList;
use Commercetools\Core\Request\ShoppingLists\Command\ShoppingListRemoveLineItemAction;
use PHPUnit\Framework\TestCase;
use function uniqid;

/**
 * Checks if elements can be removed from the shopping list.
 * 
 * @author blange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\Tests\ActionBuilder\ShoppingList
 */
class RemoveLineItemTest extends TestCase
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
            ['lineItems', ShoppingList::class, true],
            ['bob/lineItems', ShoppingList::class],
            ['lineItems/5/dfgdfg', ShoppingList::class],
        ];
    }

    /**
     * Sets up the test.
     *
     * @return void
     */
    public function setUp()
    {
        $this->fixture = new RemoveLineItem();
    }

    /**
     * Checks if no actions are rendered if there is no matching change.
     *
     * @return void
     */
    public function testCreateUpdateActionsNoMatch()
    {
        $actions = $this->fixture->createUpdateActions(
            [],
            $this->createMock(ClassMetadataInterface::class),
            [],
            [
                'id' => $oldId = uniqid(),
                'lineItems' => []
            ],
            ShoppingList::fromArray(['id' => $oldId, 'lineItems' => []])
        );

        static::assertCount(0, $actions);
    }

    /**
     * Checks if there are action when the list is changed (numeric array keys are persistent).
     *
     * @return void
     */
    public function testCreateUpdateActionsWithListChange()
    {
        $actions = $this->fixture->createUpdateActions(
            [],
            $this->createMock(ClassMetadataInterface::class),
            [],
            [
                'id' => $oldId = uniqid(),
                'lineItems' => [
                    [
                        'id' => 'removed-id-1'
                    ],
                    [
                        'id' => 'removed-id-2'
                    ]
                ]
            ],
            ShoppingList::fromArray(['id' => $oldId, 'lineItems' => [
                2 => [
                    'id' => 'left-id'
                ]
            ]])
        );

        static::assertCount(2, $actions);

        /** @var $action ShoppingListRemoveLineItemAction */
        static::assertInstanceOf(ShoppingListRemoveLineItemAction::class, $action = $actions[0]);
        static::assertSame('removed-id-1', $action->getLineItemId());

        /** @var $action ShoppingListRemoveLineItemAction */
        static::assertInstanceOf(ShoppingListRemoveLineItemAction::class, $action = $actions[1]);
        static::assertSame('removed-id-2', $action->getLineItemId());
    }

    /**
     * Checks if there are action when the list is replaced (array keys are restarted.)
     *
     * @return void
     */
    public function testCreateUpdateActionsWithNewList()
    {
        $actions = $this->fixture->createUpdateActions(
            [],
            $this->createMock(ClassMetadataInterface::class),
            [],
            [
                'id' => $oldId = uniqid(),
                'lineItems' => [
                    [
                        'id' => 'removed-id-1'
                    ],
                    [
                        'id' => 'removed-id-2'
                    ]
                ]
            ],
            ShoppingList::fromArray(['id' => $oldId, 'lineItems' => [
                [
                    'id' => 'left-id'
                ]
            ]])
        );

        static::assertCount(2, $actions);

        /** @var $action ShoppingListRemoveLineItemAction */
        static::assertInstanceOf(ShoppingListRemoveLineItemAction::class, $action = $actions[0]);
        static::assertSame('removed-id-1', $action->getLineItemId());

        /** @var $action ShoppingListRemoveLineItemAction */
        static::assertInstanceOf(ShoppingListRemoveLineItemAction::class, $action = $actions[1]);
        static::assertSame('removed-id-2', $action->getLineItemId());
    }
}
