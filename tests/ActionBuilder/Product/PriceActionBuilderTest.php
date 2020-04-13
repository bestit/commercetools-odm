<?php

namespace BestIt\CommercetoolsODM\Tests\ActionBuilder\Product;

use BestIt\CommercetoolsODM\ActionBuilder\Product\PriceActionBuilder;
use BestIt\CommercetoolsODM\ActionBuilder\Product\ProductActionBuilder;
use BestIt\CommercetoolsODM\Tests\ActionBuilder\SupportTestTrait;
use Commercetools\Core\Model\Product\Product;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

/**
 * Tests PriceActionBuilder.
 *
 * @author lange <lange@bestit-online.de>
 * @category Tests
 * @package BestIt\CommercetoolsODM\Tests\ActionBuilder\Product
 * @subpackage ActionBuilder\Product
 */
class PriceActionBuilderTest extends TestCase
{
    use SupportTestTrait;

    /**
     * The test class.
     *
     * @var PriceActionBuilder|PHPUnit_Framework_MockObject_MockObject
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
            ['masterData/current/masterVariant/prices', Product::class, true],
            ['masterData/current/variants/0/prices', Product::class, true],
            ['masterData/staged/masterVariant/prices', Product::class, true],
            ['masterData/staged/variants/0/prices', Product::class, true],
            ['masterData/current/masterVariant/prices/0', Product::class],
            ['masterData/staging/masterVariant/prices/0', Product::class],
            ['masterData/staged/variants/4/prices/1', Product::class],
        ];
    }

    /**
     * Sets up the test.
     *
     * @return void
     */
    public function setUp()
    {
        $this->fixture = static::getMockForAbstractClass(PriceActionBuilder::class);
    }

    /**
     * Checks the constant for this class.
     *
     * @return void
     */
    public function testConstants()
    {
        static::assertSame('~', PriceActionBuilder::FILTER_DELIMITER);
    }

    /**
     * Checks the instance of the builder.
     *
     * @return void
     */
    public function testInstance()
    {
        static::assertInstanceOf(ProductActionBuilder::class, $this->fixture);
    }
}
