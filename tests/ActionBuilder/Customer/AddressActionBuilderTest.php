<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\Tests\ActionBuilder\Customer;

use BestIt\CommercetoolsODM\ActionBuilder\Customer\AddressActionBuilder;
use BestIt\CommercetoolsODM\ActionBuilder\Customer\CustomerActionBuilder;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

/**
 * Tests AddressActionBuilder.
 *
 * @author lange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\Tests\ActionBuilder\Customer
 */
class AddressActionBuilderTest extends TestCase
{
    /**
     * The test class.
     *
     * @var AddressActionBuilder|PHPUnit_Framework_MockObject_MockObject|null
     */
    protected $fixture;

    /**
     * Sets up the test.
     *
     * @return void
     */
    protected function setUp()
    {
        $this->fixture = static::getMockForAbstractClass(AddressActionBuilder::class);
    }

    /**
     * Checks the instance of the builder.
     *
     * @return void
     */
    public function testInstance()
    {
        static::assertInstanceOf(CustomerActionBuilder::class, $this->fixture);
    }
}
