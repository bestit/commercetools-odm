<?php

namespace BestIt\CommercetoolsODM\Tests\ActionBuilder\ProductType;

use BestIt\CommercetoolsODM\ActionBuilder\Customer\CustomerActionBuilder;
use BestIt\CommercetoolsODM\ActionBuilder\Customer\AddShippingAddressIds;
use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use BestIt\CommercetoolsODM\Tests\ActionBuilder\SupportTestTrait;
use Commercetools\Core\Model\Common\AddressCollection;
use Commercetools\Core\Model\Customer\Customer;
use Commercetools\Core\Request\Customers\Command\CustomerAddShippingAddressAction;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

/**
 * Tests AddShippingAddressIds.
 * @author lange <lange@bestit-online.de>
 * @category Tests
 * @package BestIt\CommercetoolsODM
 * @subpackage ActionBuilder\ProductType
 * @version $id$
 */
class AddShippingAddressIdsTest extends TestCase
{
    use SupportTestTrait;

    /**
     * The test class.
     * @var AddShippingAddressIds|PHPUnit_Framework_MockObject_MockObject
     */
    protected $fixture = null;

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
            ['shippingAddressIds', Customer::class, true],
            ['shippingAddressIds/1/', Customer::class],
            ['shippingAddressId', Customer::class]
        ];
    }

    /**
     * Sets up the test.
     * @return void
     */
    public function setUp()
    {
        $this->fixture = new AddShippingAddressIds();
    }

    /**
     * Checks if new addresses are added.
     * @return void
     */
    public function testCreateUpdateActionsIgnoreOldAddresses()
    {
        $customer = new Customer();

        $this->fixture->setLastFoundMatch([uniqid(), $field = uniqid()]);

        $actions = $this->fixture->createUpdateActions(
            [
                $oldId1 = uniqid('', true),
                $oldId2 = uniqid('', true),
            ],
            $this->createMock(ClassMetadataInterface::class),
            [],
            ['shippingAddressIds' => [$oldId1, $oldId2]],
            $customer
        );

        static::assertCount(0, $actions);
    }

    /**
     * Checks if new addresses are added.
     * @return void
     */
    public function testCreateUpdateActionsAddOnlyNewAddresses()
    {
        $customer = new Customer();

        $customer->setAddresses(AddressCollection::fromArray([
            ['id' => $newId1 = uniqid('', true)],
            ['id' => $newId2 = uniqid('', true)],
        ]));

        $this->fixture->setLastFoundMatch([uniqid(), $field = uniqid()]);

        $actions = $this->fixture->createUpdateActions(
            [
                $newId1,
                $newId2,
                $oldId = uniqid('', true)
            ],
            $this->createMock(ClassMetadataInterface::class),
            [],
            [
                'shippingAddressIds' => [
                    $oldId
                ]
            ],
            $customer
        );

        static::assertCount(2, $actions);
        static::assertInstanceOf(CustomerAddShippingAddressAction::class, $action1 = $actions[0]);
        static::assertInstanceOf(CustomerAddShippingAddressAction::class, $action2 = $actions[1]);
        static::assertSame($newId1, $action1->getAddressId());
        static::assertSame($newId2, $action2->getAddressId());
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
