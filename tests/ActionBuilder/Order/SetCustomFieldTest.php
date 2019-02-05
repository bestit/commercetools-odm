<?php

namespace BestIt\CommercetoolsODM\Tests\ActionBuilder\Order;

use BestIt\CommercetoolsODM\ActionBuilder\Order\OrderActionBuilder;
use BestIt\CommercetoolsODM\ActionBuilder\Order\SetCustomField;
use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use BestIt\CommercetoolsODM\Tests\ActionBuilder\SupportTestTrait;
use Commercetools\Core\Model\Common\DateTimeDecorator;
use Commercetools\Core\Model\Order\Order;
use Commercetools\Core\Model\Product\Product;
use Commercetools\Core\Request\CustomField\Command\SetCustomFieldAction;
use DateTime;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

/**
 * Tests SetCustomField.
 *
 * @author lange <lange@bestit-online.de>
 * @category Tests
 * @package BestIt\CommercetoolsODM\Tests\ActionBuilder\Order
 * @subpackage ActionBuilder\ProductType
 */
class SetCustomFieldTest extends TestCase
{
    use SupportTestTrait;

    /**
     * The test class.
     *
     * @var SetCustomField|PHPUnit_Framework_MockObject_MockObject
     */
    protected $fixture = null;

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
            ['custom/fields/bob', Order::class, true],
            ['custom/bob/', Order::class],
            ['custom/fields/bob', Product::class],
        ];
    }

    /**
     * Sets up the test.
     *
     * @return void
     */
    public function setUp()
    {
        $this->fixture = new SetCustomField();
    }

    /**
     * Checks if a simple action is created.
     *
     * @return void
     */
    public function testCreateUpdateActionsDatetime()
    {
        $order = new Order();

        $this->fixture->setLastFoundMatch([uniqid(), $field = uniqid()]);

        $actions = $this->fixture->createUpdateActions(
            $value = new DateTime(),
            $this->createMock(ClassMetadataInterface::class),
            [],
            [],
            $order
        );

        static::assertCount(1, $actions);
        static::assertInstanceOf(SetCustomFieldAction::class, $actions[0]);
        static::assertSame($field, $actions[0]->getName());
        static::assertInstanceOf(DateTimeDecorator::class, $actions[0]->getValue());
    }

    /**
     * Checks if a simple action is created.
     *
     * @return void
     */
    public function testCreateUpdateActionsScalar()
    {
        $order = new Order();

        $this->fixture->setLastFoundMatch([uniqid(), $field = uniqid()]);

        $actions = $this->fixture->createUpdateActions(
            $value = uniqid(),
            $this->createMock(ClassMetadataInterface::class),
            [],
            [],
            $order
        );

        static::assertCount(1, $actions);
        static::assertInstanceOf(SetCustomFieldAction::class, $actions[0]);
        static::assertSame($field, $actions[0]->getName());
        static::assertSame($value, $actions[0]->getValue());
    }

    /**
     * Checks the instance.
     *
     * @return void
     */
    public function testInstance()
    {
        static::assertInstanceOf(OrderActionBuilder::class, $this->fixture);
    }
}
