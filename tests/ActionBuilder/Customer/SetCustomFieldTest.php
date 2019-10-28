<?php

namespace BestIt\CommercetoolsODM\Tests\ActionBuilder\Customer;

use BestIt\CommercetoolsODM\ActionBuilder\Customer\CustomerActionBuilder;
use BestIt\CommercetoolsODM\ActionBuilder\Customer\SetCustomField;
use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use BestIt\CommercetoolsODM\Tests\ActionBuilder\SupportTestTrait;
use Commercetools\Core\Model\Common\DateTimeDecorator;
use Commercetools\Core\Model\Customer\Customer;
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
 * @package BestIt\CommercetoolsODM\Tests\ActionBuilder\ProductType
 * @subpackage ActionBuilder\Customer
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
            ['custom/fields/bob', Customer::class, true],
            ['custom/bob/', Customer::class],
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
        $customer = new Customer();

        $this->fixture->setLastFoundMatch([uniqid(), $field = uniqid()]);

        $actions = $this->fixture->createUpdateActions(
            $value = new DateTime(),
            $this->createMock(ClassMetadataInterface::class),
            [],
            [],
            $customer
        );

        $this->assertCount(1, $actions);
        $this->assertInstanceOf(SetCustomFieldAction::class, $actions[0]);
        $this->assertSame($field, $actions[0]->getName());
        $this->assertInstanceOf(DateTimeDecorator::class, $actions[0]->getValue());
    }

    /**
     * Checks if a simple action is created.
     *
     * @return void
     */
    public function testCreateUpdateActionsNull()
    {
        $customer = new Customer();

        $this->fixture->setLastFoundMatch([uniqid(), $field = uniqid()]);

        $actions = $this->fixture->createUpdateActions(
            null,
            $this->createMock(ClassMetadataInterface::class),
            [],
            [],
            $customer
        );

        $this->assertCount(1, $actions);
        $this->assertInstanceOf(SetCustomFieldAction::class, $actions[0]);
        $this->assertSame($field, $actions[0]->getName());
        $this->assertNull($actions[0]->getValue());
    }

    /**
     * Checks if a simple action is created.
     *
     * @return void
     */
    public function testCreateUpdateActionsScalar()
    {
        $customer = new Customer();

        $this->fixture->setLastFoundMatch([uniqid(), $field = uniqid()]);

        $actions = $this->fixture->createUpdateActions(
            $value = uniqid(),
            $this->createMock(ClassMetadataInterface::class),
            [],
            [],
            $customer
        );

        $this->assertCount(1, $actions);
        $this->assertInstanceOf(SetCustomFieldAction::class, $actions[0]);
        $this->assertSame($field, $actions[0]->getName());
        $this->assertSame($value, $actions[0]->getValue());
    }

    /**
     * Checks if a simple action is created.
     *
     * @return void
     */
    public function testCreateUpdateActionsArray()
    {
        $customer = new Customer();

        $this->fixture->setLastFoundMatch([uniqid(), $field = uniqid()]);

        $actions = $this->fixture->createUpdateActions(
            [$value1 = uniqid(), null, $value2 = uniqid()],
            $this->createMock(ClassMetadataInterface::class),
            [],
            [],
            $customer
        );

        static::assertCount(1, $actions);
        static::assertInstanceOf(SetCustomFieldAction::class, $actions[0]);
        static::assertSame($field, $actions[0]->getName());

        // Keys should be kept to avoid damaged associative array's
        static::assertSame([0 => $value1, 2 => $value2], $actions[0]->getValue());
    }

    /**
     * Checks the instance.
     *
     * @return void
     */
    public function testInstance()
    {
        $this->assertInstanceOf(CustomerActionBuilder::class, $this->fixture);
    }
}
