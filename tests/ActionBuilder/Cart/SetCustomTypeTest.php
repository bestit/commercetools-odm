<?php

namespace BestIt\CommercetoolsODM\Tests\ActionBuilder\Cart;

use BestIt\CommercetoolsODM\ActionBuilder\Cart\SetCustomType;
use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use BestIt\CommercetoolsODM\Tests\ActionBuilder\SupportTestTrait;
use Commercetools\Core\Model\Cart\Cart;
use Commercetools\Core\Model\CustomField\CustomFieldObject;
use Commercetools\Core\Model\CustomField\FieldContainer;
use Commercetools\Core\Model\Type\TypeReference;
use Commercetools\Core\Request\CustomField\Command\SetCustomTypeAction;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use \DateTime;

/**
 * Tests SetCustomTypeTest
 * @author chowanski <chowanski@bestit-online.de>
 * @category Tests
 * @package BestIt\CommercetoolsODM
 * @subpackage ActionBuilder\Cart
 * @version $id$
 */
class SetCustomTypeTest extends TestCase
{
    use SupportTestTrait;

    /**
     * The test class.
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
     * @inheritdoc
     */
    public function setUp()
    {
        $this->fixture = new SetCustomType();
    }

    /**
     * Checks if a simple action is created.
     * @covers SetCustomType::createUpdateActions()
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
     * @covers SetCustomType::createUpdateActions()
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
     * @covers SetCustomType::createUpdateActions()
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
     * @covers SetCustomType::createUpdateActions()
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
     * @covers SetCustomType::createUpdateActions()
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
     * Create cart with custom type and run / assert test
     * @param array $changedData
     */
    private function runAction(array $changedData)
    {
        $cart = new Cart();
        $customField = new CustomFieldObject();
        $customField->setType(TypeReference::ofKey($changedData['type']['key']));
        $customField->setFields(FieldContainer::fromArray($changedData['fields']));
        $cart->setCustom($customField);

        /** @var SetCustomTypeAction[] $actions */
        $actions = $this->fixture->createUpdateActions(
            $changedData,
            static::createMock(ClassMetadataInterface::class),
            [],
            [],
            $cart
        );

        static::assertCount(1, $actions);
        static::assertInstanceOf(SetCustomTypeAction::class, $actions[0]);
        static::assertSame($changedData['type'], $actions[0]->getType()->toArray());
        static::assertSame($changedData['fields'], $actions[0]->getFields()->toArray());
    }
}
