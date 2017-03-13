<?php

namespace BestIt\CommercetoolsODM\Tests\ActionBuilder\ProductType;

use BestIt\CommercetoolsODM\ActionBuilder\Customer\CustomerActionBuilder;
use BestIt\CommercetoolsODM\ActionBuilder\Customer\AddAddress;
use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use BestIt\CommercetoolsODM\Tests\ActionBuilder\SupportTestTrait;
use Commercetools\Core\Model\Customer\Customer;
use Commercetools\Core\Request\Customers\Command\CustomerAddAddressAction;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

/**
 * Tests AddAddress.
 * @author lange <lange@bestit-online.de>
 * @category Tests
 * @package BestIt\CommercetoolsODM
 * @subpackage ActionBuilder\ProductType
 * @version $id$
 */
class AddAddressTest extends TestCase
{
    use SupportTestTrait;

    /**
     * The test class.
     * @var AddAddress|PHPUnit_Framework_MockObject_MockObject
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
            ['addresses/1', Customer::class, true],
            ['addresses/1/', Customer::class],
            ['addresses/1/bob', Customer::class],
            ['addresses/z', Customer::class],
        ];
    }

    /**
     * Sets up the test.
     * @return void
     */
    public function setUp()
    {
        $this->fixture = new AddAddress();
    }

    /**
     * Checks if the old address is ignored.
     * @covers AddAddress::createUpdateActions()
     * @return void
     */
    public function testCreateUpdateActionsIgnoreOldAddress()
    {
        $customer = new Customer();

        $this->fixture->setLastFoundMatch([uniqid(), $field = uniqid()]);

        $actions = $this->fixture->createUpdateActions(
            [
                'id' => uniqid(),
                'company' => uniqid()
            ],
            static::createMock(ClassMetadataInterface::class),
            [],
            [],
            $customer
        );

        static::assertCount(0, $actions);
    }

    /**
     * Checks if the address is added.
     * @covers AddAddress::createUpdateActions()
     * @return void
     */
    public function testCreateUpdateActionsWithNewAddress()
    {
        $customer = new Customer();

        $this->fixture->setLastFoundMatch([uniqid(), $field = uniqid()]);

        $actions = $this->fixture->createUpdateActions(
            $addressMock = [
                'company' => uniqid()
            ],
            static::createMock(ClassMetadataInterface::class),
            [],
            [],
            $customer
        );

        /** @var CustomerAddAddressAction $action */
        static::assertCount(1, $actions);
        static::assertInstanceOf(CustomerAddAddressAction::class, $action = $actions[0]);
        static::assertSame($addressMock, $action->getAddress()->toArray());
    }

    /**
     * Checks the instance.
     * @return void
     */
    public function testInstance()
    {
        static::assertInstanceOf(CustomerActionBuilder::class, $this->fixture);
    }
}
