<?php

namespace BestIt\CommercetoolsODM\Tests\ActionBuilder\Customer;

use BestIt\CommercetoolsODM\ActionBuilder\Customer\SetCompanyName;
use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use BestIt\CommercetoolsODM\Tests\ActionBuilder\SupportTestTrait;
use Commercetools\Core\Model\Customer\Customer;
use Commercetools\Core\Request\Customers\Command\CustomerSetCompanyNameAction;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

/**
 * Class SetCompanyNameTest
 *
 * @author André Varelmann <andre.varelmann@bestit-online.de>
 * @category Tests
 * @package BestIt\CommercetoolsODM\Tests\ActionBuilder\Customer
 * @subpackage ActionBuilder\Customer
 */
class SetCompanyNameTest extends TestCase
{
    use SupportTestTrait;

    /**
     * The test class.
     *
     * @var SetCompanyName|PHPUnit_Framework_MockObject_MockObject
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
            ['companyName', Customer::class, true],
            ['companyname', Customer::class],
            ['addresses/z', Customer::class],
        ];
    }

    /**
     * Sets up the test.
     *
     * @return void
     */
    public function setUp()
    {
        $this->fixture = new SetCompanyName();
    }

    /**
     * Checks if the action is created correctly.
     *
     * @return void
     */
    public function testCreateUpdateActions()
    {
        $actions = $this->fixture->createUpdateActions(
            $mock = uniqid(),
            $this->createMock(ClassMetadataInterface::class),
            [],
            [],
            uniqid()
        );

        static::assertCount(1, $actions);
        static::assertInstanceOf(CustomerSetCompanyNameAction::class, $action = $actions[0]);
        static::assertSame($mock, $action->getCompanyName());
    }
}
