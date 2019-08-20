<?php

namespace BestIt\CommercetoolsODM\Tests\ActionBuilder\Product;

use BestIt\CommercetoolsODM\ActionBuilder\Product\ProductActionBuilder;
use BestIt\CommercetoolsODM\ActionBuilder\Product\SetKey;
use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use BestIt\CommercetoolsODM\Tests\ActionBuilder\SupportTestTrait;
use Commercetools\Core\Model\Product\Product;
use Commercetools\Core\Request\Products\Command\ProductSetKeyAction;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

/**
 * Test for key action builder
 *
 * @author Michel Chowanski <michel.chowanski@bestit-online.de>
 * @package BestIt\CommercetoolsODM\Tests\ActionBuilder\Product
 */
class SetKeyTest extends TestCase
{
    use SupportTestTrait;

    /**
     * The test class.
     *
     * @var SetKey|PHPUnit_Framework_MockObject_MockObject
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
            ['key', Product::class, true],
            ['/key', Product::class],
            ['/key/', Product::class],
            ['key/', Product::class],
        ];
    }

    /**
     * Sets up the test.
     *
     * @return void
     */
    public function setUp()
    {
        $this->fixture = new SetKey();
    }

    /**
     * Checks if the key can be removed.
     *
     * @return void
     */
    public function testCreateUpdateActionsEmpty()
    {
        $actions = $this->fixture->createUpdateActions(
            [],
            $this->createMock(ClassMetadataInterface::class),
            [],
            [],
            new Product()
        );

        /** @var $action ProductSetKeyAction */
        static::assertCount(1, $actions);
        static::assertInstanceOf(ProductSetKeyAction::class, $action = $actions[0]);
        static::assertNull($action->getKey());
    }

    /**
     * Checks if the key can be changed.
     *
     * @return void
     */
    public function testCreateUpdateActionsFilled()
    {
        $actions = $this->fixture->createUpdateActions(
            $key = uniqid(),
            $this->createMock(ClassMetadataInterface::class),
            [],
            [],
            new Product()
        );

        /** @var $action ProductSetKeyAction */
        static::assertCount(1, $actions);
        static::assertInstanceOf(ProductSetKeyAction::class, $action = $actions[0]);
        static::assertSame($key, $action->getKey());
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
