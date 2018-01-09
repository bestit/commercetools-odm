<?php

namespace BestIt\CommercetoolsODM\Tests\ActionBuilder\ProductType;

use BestIt\CommercetoolsODM\ActionBuilder\Customer\SetFirstName;
use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use BestIt\CommercetoolsODM\Tests\ActionBuilder\SupportTestTrait;
use Commercetools\Core\Model\Customer\Customer;
use Commercetools\Core\Request\Customers\Command\CustomerSetFirstNameAction;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

/**
 * Class SetFirstNameTest
 * @author blange <lange@bestit-online.de>
 * @category Tests
 * @package BestIt\CommercetoolsODM
 * @subpackage ActionBuilder\ProductType
 * @version $id$
 */
class SetFirstNameTest extends TestCase
{
    use SupportTestTrait;

    /**
     * The test class.
     * @var SetFirstName|PHPUnit_Framework_MockObject_MockObject
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
            ['firstName', Customer::class, true],
            ['firstname', Customer::class],
            ['addresses/z', Customer::class],
        ];
    }

    /**
     * Sets up the test.
     * @return void
     */
    public function setUp()
    {
        $this->fixture = new SetFirstName();
    }

    /**
     * Checks if the action is created correctly.
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
        static::assertInstanceOf(CustomerSetFirstNameAction::class, $action = $actions[0]);
        static::assertSame($mock, $action->getFirstName());
    }
}
