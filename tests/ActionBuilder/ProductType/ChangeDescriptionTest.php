<?php

namespace BestIt\CommercetoolsODM\Tests\ActionBuilder\ProductType;

use BestIt\CommercetoolsODM\ActionBuilder\ProductType\ChangeDescription;
use BestIt\CommercetoolsODM\ActionBuilder\ProductType\ProductTypeActionBuilder;
use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use BestIt\CommercetoolsODM\Tests\ActionBuilder\SupportTestTrait;
use Commercetools\Core\Model\Product\Product;
use Commercetools\Core\Model\ProductType\ProductType;
use Commercetools\Core\Request\ProductTypes\Command\ProductTypeChangeDescriptionAction;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

/**
 * Test for description action builder
 *
 * @author Michel Chowanski <michel.chowanski@bestit-online.de>
 * @package BestIt\CommercetoolsODM\Tests\ActionBuilder\ProductType
 */
class ChangeDescriptionTest extends TestCase
{
    use SupportTestTrait;

    /**
     * The test class.
     *
     * @var ChangeDescription|PHPUnit_Framework_MockObject_MockObject
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
            ['description', ProductType::class, true],
            ['/description', ProductType::class],
            ['description', Product::class],
            ['description/', ProductType::class],
        ];
    }

    /**
     * Sets up the test.
     *
     * @return void
     */
    public function setUp()
    {
        $this->fixture = new ChangeDescription();
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

        /** @var $action ProductTypeChangeDescriptionAction */
        static::assertCount(1, $actions);
        static::assertInstanceOf(ProductTypeChangeDescriptionAction::class, $action = $actions[0]);
        static::assertNull($action->getDescription());
    }

    /**
     * Checks if the key can be changed.
     *
     * @return void
     */
    public function testCreateUpdateActionsFilled()
    {
        $actions = $this->fixture->createUpdateActions(
            $description = uniqid(),
            $this->createMock(ClassMetadataInterface::class),
            [],
            [],
            new ProductType()
        );

        /** @var $action ProductTypeChangeDescriptionAction */
        static::assertCount(1, $actions);
        static::assertInstanceOf(ProductTypeChangeDescriptionAction::class, $action = $actions[0]);
        static::assertSame($description, $action->getDescription());
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
