<?php

namespace BestIt\CommercetoolsODM\Tests\ActionBuilder\ProductType;

use BestIt\CommercetoolsODM\ActionBuilder\ProductType\ChangeSearchableForAttr;
use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use BestIt\CommercetoolsODM\Tests\ActionBuilder\SupportTestTrait;
use Commercetools\Core\Model\Product\Product;
use Commercetools\Core\Model\ProductType\ProductType;
use Commercetools\Core\Request\ProductTypes\Command\ProductTypeChangeIsSearchableAction;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

class ChangeSearchableForAttrTest extends TestCase
{
    use SupportTestTrait;

    /**
     * The test class.
     * @var ChangeSearchableForAttr|PHPUnit_Framework_MockObject_MockObject
     */
    protected $fixture = null;

    /**
     * Returns an array with the assertions for the upport method.
     *
     * The First Element is the field path, the second element is the reference class and the optional third value
     * indicates the return value of the support method.
     * @return array
     */
    public function getSupportAssertions(): array
    {
        return [
            ['attributes/1/isSearchable', ProductType::class, true],
            ['attributes/10/isSearchable', ProductType::class, true],
            ['attributes/100/isSearchable', ProductType::class, true],
            ['attributes/5/isSearchable', ProductType::class, true],
            ['attributes/bob/isSearchable', ProductType::class],
            ['attributes/isSearchable', ProductType::class],
            ['attributss', ProductType::class],
            ['attributes', Product::class],
        ];
    }

    /**
     * Sets up the test.
     * @return void
     */
    public function setUp()
    {
        $this->fixture = new ChangeSearchableForAttr();
    }

    /**
     * Checks if the update is ignored, if there is no old attribute matching the requested settings.
     * @covers ChangeSearchableForAttr::createUpdateActions()
     * @return void
     */
    public function testCreateUpdateActionsOnNew()
    {
        $this->fixture->supports('attributes/0/isSearchable', ProductType::class);

        $actions = $this->fixture->createUpdateActions(
            true,
            static::createMock(ClassMetadataInterface::class),
            [],
            [],
            new ProductType()
        );

        /** @var ProductTypeChangeIsSearchableAction $action */
        static::assertCount(0, $actions);
    }

    /**
     * Checks if the update action is rendered correctly, on an old entry.
     * @covers ChangeSearchableForAttr::createUpdateActions()
     * @return void
     */
    public function testCreateUpdateActionsOnOld()
    {
        $this->fixture->supports('attributes/0/isSearchable', ProductType::class);

        $actions = $this->fixture->createUpdateActions(
            true,
            static::createMock(ClassMetadataInterface::class),
            [],
            [
                'attributes' => [
                    [
                        'name' => $name = uniqid()
                    ]
                ]
            ],
            new ProductType()
        );

        /** @var ProductTypeChangeIsSearchableAction $action */
        static::assertCount(1, $actions, 'Action count was wrong.');
        static::assertInstanceOf(ProductTypeChangeIsSearchableAction::class, $action = $actions[0], 'Wrong type.');
        static::assertSame($name, $action->getAttributeName(), 'Wrong attribute name.');
        static::assertTrue($action->getIsSearchable(), 'Wrong searchable value.');
    }

    /**
     * Checks the instance type for the action builder.
     * @return void
     */
    public function testType()
    {
        static::assertInstanceOf(ChangeSearchableForAttr::class, $this->fixture);
    }
}
