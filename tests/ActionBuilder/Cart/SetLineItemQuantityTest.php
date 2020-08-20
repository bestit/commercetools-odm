<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\Tests\ActionBuilder\Cart;

use BestIt\CommercetoolsODM\ActionBuilder\Cart\SetLineItemQuantity;
use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Commercetools\Core\Model\Cart\Cart;
use Commercetools\Core\Model\Cart\LineItem;
use Commercetools\Core\Model\Cart\LineItemCollection;
use Commercetools\Core\Model\Common\Money;
use Commercetools\Core\Model\Common\Price;
use PHPUnit\Framework\TestCase;

/**
 * Tests for the set line item quantity action of cart update request.
 *
 * @author Tim Kellner <tim.kellner@bestit-online.de>
 * @package BestIt\CommercetoolsODM\Tests\ActionBuilder\Cart
 */
class SetLineItemQuantityTest extends TestCase
{
    /**
     * Test that the created actions contains the correct external price.
     *
     * @return void
     */
    public function testThatActionsContainsCorrectExternalPrice()
    {
        $fixture = new SetLineItemQuantity();

        $lineItemId = (string) random_int(1000, 9999);

        $fixture->setLastFoundMatch([0, 0]);

        $changedValue = [
            'quantity' => $quantity = random_int(1000, 9999),
        ];

        $metaData = $this->createMock(ClassMetadataInterface::class);

        $changedData = [];

        $oldData = [];

        $priceValue = Money::of()
            ->setCurrencyCode($currencyCode = 'EUR')
            ->setCentAmount($centAmount = random_int(1000, 9999));

        $price = Price::of()
            ->setValue($priceValue);

        $lineItem = LineItem::of()
            ->setId($lineItemId)
            ->setPriceMode(LineItem::PRICE_MODE_EXTERNAL_PRICE)
            ->setPrice($price);

        $lineItems = LineItemCollection::of()
            ->add($lineItem);

        $sourceObject = Cart::of()
            ->setLineItems($lineItems);

        $actions = $fixture->createUpdateActions(
            $changedValue,
            $metaData,
            $changedData,
            $oldData,
            $sourceObject
        );

        // Test needed price change action exists before quantity change
        self::assertArrayHasKey(0, $actions);
        self::assertSame($lineItemId, $actions[0]->get('lineItemId'));
        self::assertEquals($priceValue->toArray(), $actions[0]->get('externalPrice')->toArray());

        // Test correct quantity and price change
        self::assertArrayHasKey(1, $actions);
        self::assertSame($lineItemId, $actions[1]->get('lineItemId'));
        self::assertSame($quantity, $actions[1]->get('quantity'));
        self::assertEquals($priceValue->toArray(), $actions[1]->get('externalPrice')->toArray());
    }
}
