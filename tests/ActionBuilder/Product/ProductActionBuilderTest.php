<?php

namespace BestIt\CommercetoolsODM\Tests\ActionBuilder\Product;

use BestIt\CommercetoolsODM\ActionBuilder\ActionBuilderAbstract;
use BestIt\CommercetoolsODM\ActionBuilder\Product\ProductActionBuilder;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

/**
 * Tests ProductActionBuilder.
 * @author lange <lange@bestit-online.de>
 * @category Tests
 * @package BestIt\CommercetoolsODM
 * @subpackage ActionBuilder\Product
 * @version $id$
 */
class ProductActionBuilderTest extends TestCase
{
    /**
     * The test class.
     * @var ProductActionBuilder|PHPUnit_Framework_MockObject_MockObject
     */
    protected $fixture = null;

    /**
     * Sets up the test.
     * @return void
     */
    public function setUp()
    {
        $this->fixture = static::getMockForAbstractClass(ProductActionBuilder::class);
    }

    /**
     * Checks the instance of the builder.
     * @return void
     */
    public function testInstance()
    {
        static::assertInstanceOf(ActionBuilderAbstract::class, $this->fixture);
    }
}
