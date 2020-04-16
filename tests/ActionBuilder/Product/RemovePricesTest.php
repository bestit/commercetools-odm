<?php

namespace BestIt\CommercetoolsODM\Tests\ActionBuilder\Product;

use BestIt\CommercetoolsODM\ActionBuilder\Product\RemovePrices;
use PHPUnit\Framework\TestCase;

/**
 * Class RemovePricesTest
 *
 * @package BestIt\CommercetoolsODM\Tests\ActionBuilder\Product
 */
class RemovePricesTest extends TestCase
{
    /**
     * The tested class.
     *
     * @var RemovePrices
     */
    protected $fixture = null;

    /**
     * Sets up the test.
     *
     * @return void
     */
    protected function setUp()
    {
        $this->fixture = new RemovePrices();
    }

    /**
     * @return void
     */
    public function testPriorityIs2()
    {
        $this->assertSame(2, $this->fixture->getPriority());
    }
}
