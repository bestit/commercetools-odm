<?php

namespace BestIt\CommercetoolsODM\Tests\ActionBuilder\Cart;

use BestIt\CommercetoolsODM\ActionBuilder\Cart\AddLineItem;
use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use BestIt\CommercetoolsODM\Tests\ActionBuilder\SupportTestTrait;
use Commercetools\Core\Model\Cart\Cart;
use Commercetools\Core\Model\CustomField\CustomFieldObjectDraft;
use Commercetools\Core\Request\Carts\Command\CartAddLineItemAction;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

/**
 * Tests AddLineItem
 * @author chowanski <michel.chowanski@bestit-online.de>
 * @category Tests
 * @package BestIt\CommercetoolsODM
 * @subpackage ActionBuilder\Cart
 * @version $id$
 */
class AddLineItemTest extends TestCase
{
    use SupportTestTrait;

    /**
     * The test class.
     * @var AddLineItem|PHPUnit_Framework_MockObject_MockObject
     */
    protected $fixture = null;

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
            ['lineItems/productId', Cart::class, true],
            ['lineIte/bob/', Cart::class],
            ['lineItems', Cart::class],
        ];
    }

    /**
     * Sets up the test.
     * @return void
     */
    public function setUp()
    {
        $this->fixture = new AddLineItem();
    }

    /**
     * Checks if a no action will created if missing product id (= no new product)
     * @covers CartAddLineItemAction::createUpdateActions()
     * @return void
     */
    public function testMissingProductId()
    {
        $cart = new Cart();

        $this->fixture->setLastFoundMatch([uniqid(), $field = uniqid()]);

        /** @var CartAddLineItemAction[] $actions */
        $actions = $this->fixture->createUpdateActions(
            $value = uniqid(),
            static::createMock(ClassMetadataInterface::class),
            [],
            [],
            $cart
        );

        static::assertCount(0, $actions);
    }

    /**
     * Checks if a simple action is created.
     * @covers CartAddLineItemAction::createUpdateActions()
     * @return void
     */
    public function testCreateAddActions()
    {
        $cart = new Cart();

        $value = [
            'productId' => '444',
            'variant' => [
                'id' => 1
            ],
            'quantity' => 2,
            'custom' => [
                'type' => [
                    'typeId' => 'type',
                    'key' => 'specific-key'
                ],
                'fields' => [
                    'fieldname' => 'fieldvalue'
                ]
            ]
        ];


        $this->fixture->setLastFoundMatch([uniqid(), $field = uniqid()]);

        /** @var CartAddLineItemAction[] $actions */
        $actions = $this->fixture->createUpdateActions(
            $value,
            static::createMock(ClassMetadataInterface::class),
            [],
            [],
            $cart
        );

        static::assertCount(1, $actions);
        static::assertInstanceOf(CartAddLineItemAction::class, $actions[0]);
        static::assertSame($value['productId'], $actions[0]->getProductId());
        static::assertSame($value['variant']['id'], $actions[0]->getVariantId());
        static::assertSame($value['quantity'], $actions[0]->getQuantity());
        static::assertEquals(
            CustomFieldObjectDraft::fromArray($value['custom'])->toArray(),
            $actions[0]->getCustom()->toArray()
        );
    }

    /**
     * Checks if a action contains optional channel
     * @covers CartAddLineItemAction::createUpdateActions()
     * @return void
     */
    public function testAddChannel()
    {
        $cart = new Cart();

        $value = [
            'productId' => '444',
            'variant' => [
                'id' => 1
            ],
            'quantity' => 2,
            'distributionChannel' => [
                'id' => '7878'
            ]
        ];

        $this->fixture->setLastFoundMatch([uniqid(), $field = uniqid()]);

        /** @var CartAddLineItemAction[] $actions */
        $actions = $this->fixture->createUpdateActions(
            $value,
            static::createMock(ClassMetadataInterface::class),
            [],
            [],
            $cart
        );

        static::assertCount(1, $actions);
        static::assertInstanceOf(CartAddLineItemAction::class, $actions[0]);
        static::assertSame($value['distributionChannel']['id'], $actions[0]->getDistributionChannel()->getId());
    }
}
