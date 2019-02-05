<?php

namespace BestIt\CommercetoolsODM\Tests\ActionBuilder\Customer;

use BestIt\CommercetoolsODM\ActionBuilder\ActionBuilderAbstract;
use BestIt\CommercetoolsODM\ActionBuilder\Customer\CustomerActionBuilder;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

/**
 * Tests CustomerActionBuilder.
 *
 * @author lange <lange@bestit-online.de>
 * @category Tests
 * @package BestIt\CommercetoolsODM\Tests\ActionBuilder\Customer
 * @subpackage ActionBuilder\Customer
 */
class CustomerActionBuilderTest extends TestCase
{
    /**
     * The test class.
     *
     * @var CustomerActionBuilder|PHPUnit_Framework_MockObject_MockObject
     */
    protected $fixture = null;

    /**
     * Sets up the test.
     *
     * @return void
     */
    public function setUp()
    {
        $this->fixture = static::getMockForAbstractClass(CustomerActionBuilder::class);
    }

    /**
     * Checks the instance of the builder.
     *
     * @return void
     */
    public function testInstance()
    {
        static::assertInstanceOf(ActionBuilderAbstract::class, $this->fixture);
    }
}
