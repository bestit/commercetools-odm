<?php

namespace BestIt\CommercetoolsODM\Tests\ActionBuilder\Cart;

use BestIt\CommercetoolsODM\ActionBuilder\Cart\SetBillingAddress;
use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Commercetools\Core\Model\Cart\Cart;
use Commercetools\Core\Request\Carts\Command\CartSetBillingAddressAction;
use PHPUnit\Framework\TestCase;

/**
 * Test for the set billing address builder.
 *
 * @author Tim Kellner <tim.kellner@bestit-online.de>
 * @package BestIt\CommercetoolsODM\Tests\ActionBuilder\Cart
 */
class SetBillingAddressTest extends TestCase
{
    /**
     * The tested class.
     *
     * @var SetBillingAddress|void $fixture
     */
    private $fixture;

    /**
     * Set the fixture to SetBillingAddress.
     */
    public function setUp()
    {
        $this->fixture = new SetBillingAddress();
    }

    /**
     * Test that the createUpdateActions function add the needed commercetools actions.
     */
    public function testCreateUpdateActions()
    {
        $actions = $this->fixture->createUpdateActions(
            ['billingAddress'],
            $this->createMock(ClassMetadataInterface::class),
            [
                'billingAddress' => $billingAddress = []
            ],
            [
                'billingAddress' => []
            ],
            $this->createMock(Cart::class)
        );

        self::assertArrayHasKey(0, $actions);
        self::assertInstanceOf(CartSetBillingAddressAction::class, $actions[0]);
    }

    /**
     * Test that this class watch for changes in the correct field.
     */
    public function testTheWatchedField()
    {
        $reflectionObject = new \ReflectionObject($this->fixture);
        $reflectionProperty = $reflectionObject->getProperty('fieldName');
        $reflectionProperty->setAccessible(true);
        self::assertEquals('billingAddress', $reflectionProperty->getValue($this->fixture));
    }

    /**
     * Test that the correct class is used.
     */
    public function testUsedClass()
    {
        $reflectionObject = new \ReflectionObject($this->fixture);
        $reflectionProperty = $reflectionObject->getProperty('modelClass');
        $reflectionProperty->setAccessible(true);
        self::assertEquals(Cart::class, $reflectionProperty->getValue($this->fixture));
    }
}
