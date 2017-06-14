<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\Tests\ActionBuilder\Product;

use BestIt\CommercetoolsODM\ActionBuilder\ActionBuilderAbstract;
use BestIt\CommercetoolsODM\ActionBuilder\Product\ProductActionBuilder;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

/**
 * Tests ProductActionBuilder.
 * @author lange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\Tests\ActionBuilder\Product
 */
class ProductActionBuilderTest extends TestCase
{
    /**
     * The test class.
     * @var ProductActionBuilder|PHPUnit_Framework_MockObject_MockObject
     */
    private $fixture;

    /**
     * Sets up the test.
     * @return void
     */
    protected function setUp()
    {
        $this->fixture = $this->getMockForAbstractClass(ProductActionBuilder::class);
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
