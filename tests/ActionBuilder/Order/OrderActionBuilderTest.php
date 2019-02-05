<?php

namespace BestIt\CommercetoolsODM\Tests\ActionBuilder\Order;

use BestIt\CommercetoolsODM\ActionBuilder\ActionBuilderAbstract;
use BestIt\CommercetoolsODM\ActionBuilder\Order\OrderActionBuilder;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

/**
 * Tests OrderActionBuilder.
 *
 * @author lange <lange@bestit-online.de>
 * @category Tests
 * @package BestIt\CommercetoolsODM\Tests\ActionBuilder\Order
 * @subpackage ActionBuilder\Order
 */
class OrderActionBuilderTest extends TestCase
{
    /**
     * The test class.
     *
     * @var OrderActionBuilder|PHPUnit_Framework_MockObject_MockObject
     */
    protected $fixture = null;

    /**
     * Sets up the test.
     *
     * @return void
     */
    public function setUp()
    {
        $this->fixture = static::getMockForAbstractClass(OrderActionBuilder::class);
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
