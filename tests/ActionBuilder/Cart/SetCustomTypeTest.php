<?php

namespace BestIt\CommercetoolsODM\Tests\ActionBuilder\Cart;

use BestIt\CommercetoolsODM\ActionBuilder\Cart\CartActionBuilder;
use BestIt\CommercetoolsODM\ActionBuilder\Cart\SetCustomType;
use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use BestIt\CommercetoolsODM\Tests\ActionBuilder\SupportTestTrait;
use Commercetools\Core\Model\Cart\Cart;
use Commercetools\Core\Model\CustomField\CustomFieldObject;
use Commercetools\Core\Model\CustomField\FieldContainer;
use Commercetools\Core\Model\Type\TypeReference;
use Commercetools\Core\Request\CustomField\Command\SetCustomTypeAction;
use DateTime;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

/**
 * Tests SetCustomTypeTest
 *
 * @author chowanski <chowanski@bestit-online.de>
 * @category Tests
 * @package BestIt\CommercetoolsODM\Tests\ActionBuilder\Cart
 * @subpackage ActionBuilder\Cart
 */
class SetCustomTypeTest extends TestCase
{
    use SupportTestTrait;

    /**
     * The test class.
     *
     * @var SetCustomType|PHPUnit_Framework_MockObject_MockObject
     */
    protected $fixture;

    /**
     * @inheritdoc
     */
    public function getSupportAssertions(): array
    {
        return [
            ['custom/fields/bob', Cart::class, false],
            ['custom', Cart::class, true],
            ['lineItems/custom/fields/bob', Cart::class]
        ];
    }

    /**
     * Create cart with custom type and run / assert test
     *
     * @param array $changedData
     *
     * @return void
     */
    private function runAction(array $changedData)
    {
        $expectedFields = array_merge(['non-changed' => 'foobar'], $changedData['fields']);

        $cart = new Cart();
        $customField = new CustomFieldObject();
        $customField->setType(TypeReference::ofKey($changedData['type']['key']));
        $customField->setFields(
            FieldContainer::fromArray(['non-changed' => 'foobar'])
        );
        $cart->setCustom($customField);

        /** @var SetCustomTypeAction[] $actions */
        $actions = $this->fixture->createUpdateActions(
            $changedData,
            $this->createMock(ClassMetadataInterface::class),
            [],
            [],
            $cart
        );

        static::assertCount(1, $actions);
        static::assertInstanceOf(SetCustomTypeAction::class, $actions[0]);
        static::assertSame($changedData['type'], $actions[0]->getType()->toArray());
        static::assertSame($expectedFields, $actions[0]->getFields()->toArray());
    }

    /**
     * Sets up the test.
     *
     * @return void
     */
    protected function setUp()
    {
        $this->fixture = new SetCustomType();
    }

    /**
     * Checks if a simple action is created.
     *
     * @return void
     */
    public function testCreateUpdateActionsScalar()
    {
        $changedData = [
            'type' => [
                'typeId' => 'type',
                'key' => 'FOOBAR'
            ],
            'fields' => [
                'paymentType' => 'invoice'
            ]
        ];

        $this->runAction($changedData);
    }

    /**
     * Checks if a simple action with mutiple fields is created.
     *
     * @return void
     */
    public function testCreateUpdateActionsMultipleScalar()
    {
        $changedData = [
            'type' => [
                'typeId' => 'type',
                'key' => 'FOOBAR'
            ],
            'fields' => [
                'paymentType' => 'invoice',
                'shippingType' => 'dhl'
            ]
        ];

        $this->runAction($changedData);
    }

    /**
     * Checks if a simple action with integer field is created.
     *
     * @return void
     */
    public function testCreateUpdateActionsInteger()
    {
        $changedData = [
            'type' => [
                'typeId' => 'type',
                'key' => 'FOOBAR'
            ],
            'fields' => [
                'fieldname' => 5,
            ]
        ];

        $this->runAction($changedData);
    }

    /**
     * Checks if a simple action with float field is created.
     *
     * @return void
     */
    public function testCreateUpdateActionsFloat()
    {
        $changedData = [
            'type' => [
                'typeId' => 'type',
                'key' => 'FOOBAR'
            ],
            'fields' => [
                'fieldname' => 5.44,
            ]
        ];

        $this->runAction($changedData);
    }

    /**
     * Checks if a simple action with object field is created.
     *
     * @return void
     */
    public function testCreateUpdateActionsObject()
    {
        $changedData = [
            'type' => [
                'typeId' => 'type',
                'key' => 'FOOBAR'
            ],
            'fields' => [
                'fieldname' => new DateTime(),
            ]
        ];

        $this->runAction($changedData);
    }

    /**
     * Checks the instance.
     *
     * @return void
     */
    public function testInstance()
    {
        static::assertInstanceOf(CartActionBuilder::class, $this->fixture);
    }
}
