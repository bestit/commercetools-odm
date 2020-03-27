<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\Tests\ActionBuilder\ProductType;

use BestIt\CommercetoolsODM\ActionBuilder\CustomObject\UpdateCustomObject;
use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use BestIt\CommercetoolsODM\Tests\ActionBuilder\SupportTestTrait;
use Commercetools\Core\Model\CustomObject\CustomObject;
use Commercetools\Core\Request\CustomObjects\CustomObjectCreateRequest;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

/**
 * Test update custom object.
 *
 * @package BestIt\CommercetoolsODM\Tests\ActionBuilder\ProductType
 */
class UpdateActionTest extends TestCase
{
    use SupportTestTrait;

    /**
     * The test class.
     *
     * @var UpdateCustomObject|PHPUnit_Framework_MockObject_MockObject|null
     */
    protected $fixture;

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
            ['value', CustomObject::class, true],
            ['values', CustomObject::class],
            ['value/1/test', CustomObject::class],
        ];
    }

    /**
     * Sets up the test.
     *
     * @return void
     */
    protected function setUp()
    {
        $this->fixture = new UpdateCustomObject();
    }

    /**
     * @return void
     */
    public function testCustomObjectCreateRequestIsReturnedForNewValue()
    {
        $sourceObject = new CustomObject([
            'container' => 'container',
            'key' => 'key',
            'value' => [
                'some' => 'value',
            ],
        ]);

        $actions = $this->fixture->createUpdateActions(
            [
                'Description' => 'Foobar',
            ],
            $this->createMock(ClassMetadataInterface::class),
            [],
            [],
            $sourceObject
        );

        $this->assertCount(1, $actions);
        $customObjectCreateRequest = $actions[0];
        $this->assertInstanceOf(CustomObjectCreateRequest::class, $customObjectCreateRequest);
        $this->assertSame($sourceObject, $customObjectCreateRequest->getObject());
    }

    /**
     * @return void
     */
    public function testNoActionIsReturnedIfChangedValueIsEmpty()
    {
        $actions = $this->fixture->createUpdateActions(
            [],
            $this->createMock(ClassMetadataInterface::class),
            [],
            [],
            new CustomObject()
        );

        $this->assertEmpty($actions);
    }

    /**
     * Checks the instance.
     *
     * @return void
     */
    public function testInstance()
    {
        static::assertInstanceOf(UpdateCustomObject::class, $this->fixture);
    }
}
