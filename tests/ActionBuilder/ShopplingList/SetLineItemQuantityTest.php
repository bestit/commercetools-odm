<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\Tests\ActionBuilder\ShoppingList;

use BestIt\CommercetoolsODM\ActionBuilder\ShoppingList\SetLineItemQuantity;
use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use BestIt\CommercetoolsODM\Tests\ActionBuilder\SupportTestTrait;
use Commercetools\Core\Model\ShoppingList\ShoppingList;
use Commercetools\Core\Request\ShoppingLists\Command\ShoppingListChangeLineItemQuantityAction;
use PHPUnit\Framework\TestCase;
use function uniqid;

/**
 * Test SetLineItemQuantity
 *
 * @author blange <bjoern.lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\Tests\ActionBuilder\ShoppingList
 */
class SetLineItemQuantityTest extends TestCase
{
    use SupportTestTrait;

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
            ['lineItems/100/quantity', ShoppingList::class, true],
            ['lineItems/cv/quantity', ShoppingList::class],
            ['bob/lineItems/100/quantity', ShoppingList::class],
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
        $this->fixture = new SetLineItemQuantity();
    }

    /**
     * Checks the success
     *
     * @return void
     */
    public function testCreateUpdateActionsSuccess()
    {
        $this->fixture->setLastFoundMatch([null, 0]);

        $actions = $this->fixture->createUpdateActions(
            5,
            $this->createMock(ClassMetadataInterface::class),
            [],
            $data = [
                'id' => $oldId = uniqid(),
                'lineItems' => [
                    [
                        'id' => 'id',
                        'quantity' => 3
                    ]
                ]
            ],
            ShoppingList::fromArray($data)
        );

        static::assertCount(1, $actions);

        /** @var $action ShoppingListChangeLineItemQuantityAction */
        static::assertInstanceOf(ShoppingListChangeLineItemQuantityAction::class, $action = $actions[0]);
        static::assertSame('id', $action->getLineItemId());
        static::assertSame(5, $action->getQuantity());
    }
}
