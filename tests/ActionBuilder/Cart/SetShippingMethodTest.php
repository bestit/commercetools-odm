<?php

namespace BestIt\CommercetoolsODM\Tests\ActionBuilder\Cart;

use BestIt\CommercetoolsODM\ActionBuilder\Cart\CartActionBuilder;
use BestIt\CommercetoolsODM\ActionBuilder\Cart\SetShippingMethod;
use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use BestIt\CommercetoolsODM\Tests\ActionBuilder\SupportTestTrait;
use Commercetools\Core\Model\Cart\Cart;
use Commercetools\Core\Request\Carts\Command\CartSetShippingMethodAction;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

/**
 * Tests SetShippingMethod
 * @author chowanski <chowanski@bestit-online.de>
 * @category Tests
 * @package BestIt\CommercetoolsODM
 * @subpackage ActionBuilder\Cart
 * @version $id$
 */
class SetShippingMethodTest extends TestCase
{
    use SupportTestTrait;

    /**
     * The test class.
     * @var SetShippingMethod|PHPUnit_Framework_MockObject_MockObject
     */
    protected $fixture;

    /**
     * @inheritdoc
     */
    public function getSupportAssertions(): array
    {
        return [
            ['shippingInfo', Cart::class],
            ['shippingInfo/shippingMethod/id', Cart::class, true],
            ['shippingInfo/shippingMethod', Cart::class]
        ];
    }

    /**
     * @inheritdoc
     */
    public function setUp()
    {
        $this->fixture = new SetShippingMethod();
    }

    /**
     * Checks if a simple action is created.
     * @covers SetShippingMethod::createUpdateActions()
     * @return void
     */
    public function testCreateUpdateActionsString()
    {
        $changedData = 'foobar';

        $cart = new Cart();

        /** @var CartSetShippingMethodAction[] $actions */
        $actions = $this->fixture->createUpdateActions(
            $changedData,
            static::createMock(ClassMetadataInterface::class),
            [],
            [],
            $cart
        );

        static::assertCount(1, $actions);
        static::assertInstanceOf(CartSetShippingMethodAction::class, $actions[0]);
        static::assertEquals($changedData, $actions[0]->getShippingMethod()->getId());
    }

    /**
     * Checks if a simple action is created.
     * @covers SetShippingMethod::createUpdateActions()
     * @return void
     */
    public function testCreateUpdateActionsNonString()
    {
        $changedData =['foo' => 'bar'];

        $cart = new Cart();

        /** @var CartSetShippingMethodAction[] $actions */
        $actions = $this->fixture->createUpdateActions(
            $changedData,
            static::createMock(ClassMetadataInterface::class),
            [],
            [],
            $cart
        );

        static::assertCount(0, $actions);
    }

    /**
     * Checks the instance.
     * @return void
     */
    public function testInstance()
    {
        static::assertInstanceOf(CartActionBuilder::class, $this->fixture);
    }
}
