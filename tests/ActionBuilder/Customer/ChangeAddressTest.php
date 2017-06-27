<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\Tests\ActionBuilder\ProductType;

use BestIt\CommercetoolsODM\ActionBuilder\Customer\ChangeAddress;
use BestIt\CommercetoolsODM\ActionBuilder\Customer\CustomerActionBuilder;
use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use BestIt\CommercetoolsODM\Tests\ActionBuilder\SupportTestTrait;
use Commercetools\Core\Model\Customer\Customer;
use Commercetools\Core\Request\Customers\Command\CustomerChangeAddressAction;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

/**
 * Test the ChangeAddress-Builder.
 * @author blange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\Tests\ActionBuilder\ProductType
 */
class ChangeAddressTest extends TestCase
{
    use SupportTestTrait;

    /**
     * The test class.
     * @var ChangeAddress|PHPUnit_Framework_MockObject_MockObject|null
     */
    protected $fixture;

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
    protected function setUp()
    {
        $this->fixture = new ChangeAddress();
    }

    /**
     * Checks if new addresses are not added.
     * @return void
     */
    public function testCreateUpdateActionsIgnoreNewAddress()
    {
        $customer = new Customer();

        $this->fixture->setLastFoundMatch([uniqid(), $field = uniqid()]);

        $actions = $this->fixture->createUpdateActions(
            $addressMock = [
                'company' => uniqid()
            ],
            $this->createMock(ClassMetadataInterface::class),
            [],
            [
                'addresses' => []
            ],
            $customer
        );

        static::assertCount(0, $actions);
    }


    /**
     * Checks if the address is changed.
     * @return void
     */
    public function testCreateUpdateActionsWithChangedAddress()
    {
        $customer = new Customer();

        $this->fixture->setLastFoundMatch([uniqid(), 0]);

        $actions = $this->fixture->createUpdateActions(
            $addressMock = ['company' => uniqid('', true)],
            $this->createMock(ClassMetadataInterface::class),
            [],
            [
                'addresses' => [
                    ['id' => $addressId = uniqid('', true)]
                ]
            ],
            $customer
        );

        /** @var CustomerChangeAddressAction $action */
        static::assertCount(1, $actions);
        static::assertInstanceOf(CustomerChangeAddressAction::class, $action = $actions[0]);
        static::assertSame($addressId, $action->getAddressId());
        static::assertSame($addressMock + ['id' => $addressId], $action->getAddress()->toArray());
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
