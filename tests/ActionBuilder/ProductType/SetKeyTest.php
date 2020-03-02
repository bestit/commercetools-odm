<?php

namespace BestIt\CommercetoolsODM\Tests\ActionBuilder\ProductType;

use BestIt\CommercetoolsODM\ActionBuilder\ProductType\ProductTypeActionBuilder;
use BestIt\CommercetoolsODM\ActionBuilder\ProductType\SetKey;
use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use BestIt\CommercetoolsODM\Tests\ActionBuilder\SupportTestTrait;
use Commercetools\Core\Model\Product\Product;
use Commercetools\Core\Model\ProductType\ProductType;
use Commercetools\Core\Request\ProductTypes\Command\ProductTypeSetKeyAction;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

/**
 * Test for key action builder
 *
 * @author Michel Chowanski <michel.chowanski@bestit-online.de>
 * @package BestIt\CommercetoolsODM\Tests\ActionBuilder\ProductType
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
            ['key', ProductType::class, true],
            ['/key', ProductType::class],
            ['key', Product::class],
            ['key/', ProductType::class],
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
            new ProductType()
        );

        /** @var $action ProductTypeSetKeyAction */
        static::assertCount(1, $actions);
        static::assertInstanceOf(ProductTypeSetKeyAction::class, $action = $actions[0]);
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
            new ProductType()
        );

        /** @var $action ProductTypeSetKeyAction */
        static::assertCount(1, $actions);
        static::assertInstanceOf(ProductTypeSetKeyAction::class, $action = $actions[0]);
        static::assertSame($key, $action->getKey());
    }

    /**
     * Checks the instance of the builder.
     *
     * @return void
     */
    public function testInstance()
    {
        static::assertInstanceOf(ProductTypeActionBuilder::class, $this->fixture);
    }
}
