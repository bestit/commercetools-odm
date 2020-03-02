<?php

namespace BestIt\CommercetoolsODM\Tests\ActionBuilder\ProductType;

use BestIt\CommercetoolsODM\ActionBuilder\ProductType\ChangeName;
use BestIt\CommercetoolsODM\ActionBuilder\ProductType\ProductTypeActionBuilder;
use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use BestIt\CommercetoolsODM\Tests\ActionBuilder\SupportTestTrait;
use Commercetools\Core\Model\Product\Product;
use Commercetools\Core\Model\ProductType\ProductType;
use Commercetools\Core\Request\ProductTypes\Command\ProductTypeChangeNameAction;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

/**
 * Test for name action builder
 *
 * @author Michel Chowanski <michel.chowanski@bestit-online.de>
 * @package BestIt\CommercetoolsODM\Tests\ActionBuilder\ProductType
 */
class ChangeNameTest extends TestCase
{
    use SupportTestTrait;

    /**
     * The test class.
     *
     * @var ChangeName|PHPUnit_Framework_MockObject_MockObject
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
            ['name', ProductType::class, true],
            ['/name', ProductType::class],
            ['name', Product::class],
            ['name/', ProductType::class],
        ];
    }

    /**
     * Sets up the test.
     *
     * @return void
     */
    public function setUp()
    {
        $this->fixture = new ChangeName();
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

        /** @var $action ProductTypeChangeNameAction */
        static::assertCount(1, $actions);
        static::assertInstanceOf(ProductTypeChangeNameAction::class, $action = $actions[0]);
        static::assertNull($action->getName());
    }

    /**
     * Checks if the key can be changed.
     *
     * @return void
     */
    public function testCreateUpdateActionsFilled()
    {
        $actions = $this->fixture->createUpdateActions(
            $name = uniqid(),
            $this->createMock(ClassMetadataInterface::class),
            [],
            [],
            new ProductType()
        );

        /** @var $action ProductTypeChangeNameAction */
        static::assertCount(1, $actions);
        static::assertInstanceOf(ProductTypeChangeNameAction::class, $action = $actions[0]);
        static::assertSame($name, $action->getName());
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
