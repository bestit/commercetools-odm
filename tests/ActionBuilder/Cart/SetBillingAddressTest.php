<?php

namespace BestIt\CommercetoolsODM\Tests\ActionBuilder\Cart;

use BestIt\CommercetoolsODM\ActionBuilder\Cart\SetBillingAddress;
use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use BestIt\CommercetoolsODM\Tests\ActionBuilder\SupportTestTrait;
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
    use SupportTestTrait;

    /**
     * @inheritdoc
     */
    public function getSupportAssertions(): array
    {
        return [
            ['billingAddress', Cart::class, true],
            ['billingAddress/id', Cart::class],
            ['billingAddress/streetName', Cart::class],
            ['billingAddress/streetNumber', Cart::class],
            ['billingAddress/postalCode', Cart::class],
            ['billingAddress/city', Cart::class],
            ['billingAddress/country', Cart::class],
            ['billingAddress/company', Cart::class],
            ['billingAddress/phone', Cart::class]
        ];
    }

    /**
     * Set the fixture to SetBillingAddress.
     *
     * @return void
     */
    public function setUp()
    {
        $this->fixture = new SetBillingAddress();
    }

    /**
     * Test that the createUpdateActions function add the needed commercetools actions.
     *
     * @return void
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
}
