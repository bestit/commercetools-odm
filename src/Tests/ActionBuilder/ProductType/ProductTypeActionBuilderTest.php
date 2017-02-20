<?php

namespace BestIt\CommercetoolsODM\Tests\ActionBuilder\Product;

use BestIt\CommercetoolsODM\ActionBuilder\ActionBuilderAbstract;
use BestIt\CommercetoolsODM\ActionBuilder\ProductType\ProductTypeActionBuilder;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

/**
 * Tests ProductTypeActionBuilder.
 * @author lange <lange@bestit-online.de>
 * @category Tests
 * @package BestIt\CommercetoolsODM
 * @subpackage ActionBuilder\Product
 * @version $id$
 */
class ProductTypeActionBuilderTest extends TestCase
{
    /**
     * The test class.
     * @var ProductTypeActionBuilder|PHPUnit_Framework_MockObject_MockObject
     */
    protected $fixture = null;

    /**
     * Sets up the test.
     * @return void
     */
    public function setUp()
    {
        $this->fixture = static::getMockForAbstractClass(ProductTypeActionBuilder::class);
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
