<?php

namespace BestIt\CommercetoolsODM\Tests\ActionBuilder\ProductType;

use BestIt\CommercetoolsODM\ActionBuilder\Customer\CustomerActionBuilder;
use BestIt\CommercetoolsODM\ActionBuilder\Customer\SetDefaultShippingAddressId;
use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use BestIt\CommercetoolsODM\Tests\ActionBuilder\SupportTestTrait;
use Commercetools\Core\Model\Customer\Customer;
use Commercetools\Core\Model\Product\Product;
use Commercetools\Core\Request\Customers\Command\CustomerSetDefaultShippingAddressAction;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

/**
 * Class SetDefaultShippingAddressIdTest
 *
 * @author blange <lange@bestit-online.de>
 * @category Tests
 * @package BestIt\CommercetoolsODM\Tests\ActionBuilder\ProductType
 * @subpackage ActionBuilder\ProductType
 */
class SetDefaultShippingAddressIdTest extends TestCase
{
    use SupportTestTrait;

    /**
     * The test class.
     *
     * @var SetDefaultShippingAddressId|PHPUnit_Framework_MockObject_MockObject
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
            ['defaultShippingAddressId', Customer::class, true],
            ['defaultShippingAddressId/', Customer::class],
            ['defaultShippingAddressId/bob', Product::class],
        ];
    }

    /**
     * Sets up the test.
     *
     * @return void
     */
    public function setUp()
    {
        $this->fixture = new SetDefaultShippingAddressId();
    }


    /**
     * Checks if a simple action is created.
     *
     * @return void
     */
    public function testCreateUpdateActions()
    {
        $customer = new Customer();

        $actions = $this->fixture->createUpdateActions(
            $value = uniqid(),
            $this->createMock(ClassMetadataInterface::class),
            [],
            [],
            $customer
        );

        static::assertCount(1, $actions);
        static::assertInstanceOf(CustomerSetDefaultShippingAddressAction::class, $actions[0]);
        static::assertSame($value, $actions[0]->getAddressId());
    }

    /**
     * Checks the instance.
     *
     * @return void
     */
    public function testInstance()
    {
        static::assertInstanceOf(CustomerActionBuilder::class, $this->fixture);
    }
}
