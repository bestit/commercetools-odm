<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\Tests\ActionBuilder\Category;

use BestIt\CommercetoolsODM\ActionBuilder\Category\CategoryActionBuilder;
use BestIt\CommercetoolsODM\ActionBuilder\Category\SetCustomField;
use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use BestIt\CommercetoolsODM\Tests\ActionBuilder\SupportTestTrait;
use Commercetools\Core\Model\Category\Category;
use Commercetools\Core\Request\CustomField\Command\SetCustomFieldAction;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

/**
 * Tests SetCustomField.
 *
 * @category Tests
 * @package BestIt\CommercetoolsODM\Tests\ActionBuilder\Category
 * @subpackage ActionBuilder\Category
 */
class SetCustomFieldTest extends TestCase
{
    use SupportTestTrait;

    /**
     * The test class.
     *
     * @var SetCustomField|PHPUnit_Framework_MockObject_MockObject
     */
    protected $fixture = null;

    /**
     * Sets up the test.
     *
     * @return void
     */
    public function setUp()
    {
        $this->fixture = new SetCustomField();
    }

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
            ['custom/fields/bob', Category::class, true],
            ['custom/bob/', Category::class, false],
        ];
    }

    /**
     * Checks if a simple action is created.
     *
     * @return void
     */
    public function testCreateUpdateActionsString()
    {
        $category = new Category();

        $this->fixture->setLastFoundMatch([uniqid(), $field = uniqid()]);

        $actions = $this->fixture->createUpdateActions(
            $value = 'some value',
            $this->createMock(ClassMetadataInterface::class),
            [],
            [],
            $category
        );

        $this->assertCount(1, $actions);
        $this->assertInstanceOf(SetCustomFieldAction::class, $actions[0]);
        $this->assertSame($field, $actions[0]->getName());
        $this->assertSame($value, $actions[0]->getValue());
    }

    /**
     * Checks if a simple action is created.
     *
     * @return void
     */
    public function testCreateUpdateActionsNull()
    {
        $category = new Category();

        $this->fixture->setLastFoundMatch([uniqid(), $field = uniqid()]);

        $actions = $this->fixture->createUpdateActions(
            null,
            $this->createMock(ClassMetadataInterface::class),
            [],
            [],
            $category
        );

        $this->assertCount(1, $actions);
        $this->assertInstanceOf(SetCustomFieldAction::class, $actions[0]);
        $this->assertSame($field, $actions[0]->getName());
        $this->assertNull($actions[0]->getValue());
    }

    /**
     * Checks if a simple action is created.
     *
     * @return void
     */
    public function testCreateUpdateActionsScalar()
    {
        $category = new Category();

        $this->fixture->setLastFoundMatch([uniqid(), $field = uniqid()]);

        $actions = $this->fixture->createUpdateActions(
            $value = uniqid(),
            $this->createMock(ClassMetadataInterface::class),
            [],
            [],
            $category
        );

        $this->assertCount(1, $actions);
        $this->assertInstanceOf(SetCustomFieldAction::class, $actions[0]);
        $this->assertSame($field, $actions[0]->getName());
        $this->assertSame($value, $actions[0]->getValue());
    }

    /**
     * Checks if a simple action is created.
     *
     * @return void
     */
    public function testCreateUpdateActionsArray()
    {
        $category = new Category();

        $this->fixture->setLastFoundMatch([uniqid(), $field = uniqid()]);

        $actions = $this->fixture->createUpdateActions(
            [$value1 = uniqid(), null, $value2 = uniqid()],
            $this->createMock(ClassMetadataInterface::class),
            [],
            [],
            $category
        );

        static::assertCount(1, $actions);
        static::assertInstanceOf(SetCustomFieldAction::class, $actions[0]);
        static::assertSame($field, $actions[0]->getName());

        // Keys should be kept to avoid damaged associative array's
        static::assertSame([0 => $value1, 2 => $value2], $actions[0]->getValue());
    }

    /**
     * Checks the instance.
     *
     * @return void
     */
    public function testInstance()
    {
        $this->assertInstanceOf(CategoryActionBuilder::class, $this->fixture);
    }
}
