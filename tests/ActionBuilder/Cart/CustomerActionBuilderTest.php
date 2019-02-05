<?php

namespace BestIt\CommercetoolsODM\Tests\ActionBuilder\Cart;

use BestIt\CommercetoolsODM\ActionBuilder\ActionBuilderAbstract;
use BestIt\CommercetoolsODM\ActionBuilder\Cart\CartActionBuilder;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

/**
 * Tests CartActionBuilder.
 *
 * @author lange <lange@bestit-online.de>
 * @category Tests
 * @package BestIt\CommercetoolsODM\Tests\ActionBuilder\Cart
 * @subpackage ActionBuilder\Cart
 */
class CartActionBuilderTest extends TestCase
{
    /**
     * The test class.
     *
     * @var CartActionBuilder|PHPUnit_Framework_MockObject_MockObject
     */
    protected $fixture = null;

    /**
     * Sets up the test.
     *
     * @return void
     */
    public function setUp()
    {
        $this->fixture = static::getMockForAbstractClass(CartActionBuilder::class);
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
