<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\Tests\ActionBuilder\Cart;

use BestIt\CommercetoolsODM\ActionBuilder\Cart\SetLineItemExternalPrice;
use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Commercetools\Core\Model\Cart\Cart;
use Commercetools\Core\Model\Cart\LineItem;
use Commercetools\Core\Model\Cart\LineItemCollection;
use Commercetools\Core\Model\Common\Money;
use Commercetools\Core\Model\Common\Price;
use Exception;
use PHPUnit\Framework\TestCase;

/**
 * Tests for the set line item external price action of cart update request.
 *
 * @author Michel Chowanski <michel.chowanski@bestit-online.de>
 * @package BestIt\CommercetoolsODM\Tests\ActionBuilder\Cart
 */
class SetLineItemExternalPriceTest extends TestCase
{
    /**
     * Test that the created actions contains the correct external price.
     *
     * @throws Exception
     *
     * @return void
     */
    public function testThatActionsContainsCorrectExternalPrice()
    {
        $fixture = new SetLineItemExternalPrice();

        $lineItemId = (string) random_int(1000, 9999);

        $fixture->setLastFoundMatch([0, 0]);

        $changedValue = [
            'price' => random_int(1000, 9999),
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
            ->setPrice($price)
            ->setQuantity(4);

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

        self::assertArrayHasKey(0, $actions);
        self::assertSame($lineItemId, $actions[0]->get('lineItemId'));
        self::assertEquals($priceValue->toArray(), $actions[0]->get('externalPrice')->toArray());
    }
}
