<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\Tests\ActionBuilder\ShopplingList;

use BestIt\CommercetoolsODM\ActionBuilder\ShoppingList\SetLineItemCustomType;
use BestIt\CommercetoolsODM\ActionBuilder\ShoppingList\ShoppingListActionBuilder;
use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use BestIt\CommercetoolsODM\Tests\ActionBuilder\SupportTestTrait;
use Commercetools\Core\Model\Product\Product;
use Commercetools\Core\Model\ShoppingList\LineItem;
use Commercetools\Core\Model\ShoppingList\LineItemCollection;
use Commercetools\Core\Model\ShoppingList\ShoppingList;
use Commercetools\Core\Request\ShoppingLists\Command\ShoppingListSetLineItemCustomTypeAction;
use PHPUnit\Framework\TestCase;

/**
 * Test for the shopping list set line item custom field action
 *
 * @author André Varelmann <andre.varelmann@bestit-online.de>
 * @package BestIt\CommercetoolsODM\Tests\ActionBuilder\ShopplingList
 */
class SetLineItemCustomTypeTest extends TestCase
{
    use SupportTestTrait;

    /**
     * The tested class.
     *
     * @var SetLineItemCustomType|null
     */
    protected $fixture;

    /**
     * Sets up the test.
     *
     * @return void
     */
    protected function setUp()
    {
        $this->fixture = new SetLineItemCustomType();
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
            ['lineItems/0/custom', ShoppingList::class, true],
            ['lineItems/0/custom/type', ShoppingList::class],
            ['lineItems/0/custom/field', ShoppingList::class],
            ['lineItems/', ShoppingList::class],
            ['lineItems/0/custom', Product::class],
        ];
    }

    /**
     * Checks the instance type.
     *
     * @return void
     */
    public function testInstance()
    {
        static::assertInstanceOf(ShoppingListActionBuilder::class, $this->fixture);
    }

    /**
     * Test the create update actions
     *
     * @return void
     */
    public function testCreateUpdateActions()
    {
        $this->fixture->setLastFoundMatch([0, 0]);

        $lineItem = LineItem::of()->setId($lineItemId = uniqid());
        $lineItems = LineItemCollection::of()
            ->add($lineItem);

        $actions = $this->fixture->createUpdateActions(
            [
                'fields' => ['foo' => 'bar'],
                'type' => ['key' => $key = 'foobar'],
            ],
            $this->createMock(ClassMetadataInterface::class),
            [],
            [],
            $sourceObject = ShoppingList::of()->setLineItems($lineItems)
        );

        static::assertCount(1, $actions);
        static::assertInstanceOf(ShoppingListSetLineItemCustomTypeAction::class, $actions[0]);
        static::assertSame($lineItemId, $actions[0]->getLineItemId($lineItemId));
        static::assertEquals($key, $actions[0]->getType()->getKey());
        static::assertEquals(['foo' => 'bar'], $actions[0]->getFields()->toArray());
    }

    /**
     * Test the create update actions without existing lineItem
     *
     * @return void
     */
    public function testCreateUpdateActionsWithoutExistingLineItem()
    {
        $this->fixture->setLastFoundMatch([0, 0]);

        $lineItem = LineItem::of();
        $lineItems = LineItemCollection::of()
            ->add($lineItem);

        $actions = $this->fixture->createUpdateActions(
            [
                'fields' => ['foo' => 'bar'],
                'type' => ['key' => $key = 'foobar'],
            ],
            $this->createMock(ClassMetadataInterface::class),
            [],
            [],
            $sourceObject = ShoppingList::of()->setLineItems($lineItems)
        );

        static::assertEquals([], $actions);
    }

    /**
     * Test the create update actions without changed fields
     *
     * @return void
     */
    public function testCreateUpdateActionsWithoutChangedFields()
    {
        $this->fixture->setLastFoundMatch([0, 0]);

        $lineItem = LineItem::of();
        $lineItems = LineItemCollection::of()
            ->add($lineItem);

        $actions = $this->fixture->createUpdateActions(
            [
                'type' => ['key' => $key = 'foobar'],
            ],
            $this->createMock(ClassMetadataInterface::class),
            [],
            [],
            $sourceObject = ShoppingList::of()->setLineItems($lineItems)
        );

        static::assertEquals([], $actions);
    }
}
