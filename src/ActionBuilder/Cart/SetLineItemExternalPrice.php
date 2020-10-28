<?php

namespace BestIt\CommercetoolsODM\ActionBuilder\Cart;

use BestIt\CommercetoolsODM\Helper\PriceHelperTrait;
use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Commercetools\Core\Model\Cart\Cart;
use Commercetools\Core\Model\Cart\ExternalLineItemTotalPrice;
use Commercetools\Core\Model\Cart\LineItem;
use Commercetools\Core\Request\AbstractAction;
use Commercetools\Core\Request\Carts\Command\CartSetLineItemPriceAction;
use Commercetools\Core\Request\Carts\Command\CartSetLineItemTotalPriceAction;

/**
 * Builds the action to change cart item external price which don't updated by recalculate actions
 *
 * @author Michel Chowanski <michel.chowanski@bestit-online.de>
 * @package BestIt\CommercetoolsODM\ActionBuilder\Cart
 */
class SetLineItemExternalPrice extends CartActionBuilder
{
    use PriceHelperTrait;

    /**
     * Constant for external price mode
     *
     * @var string
     */
    const MODE_CONSTANT = 'Commercetools\Core\Model\Cart\LineItem::PRICE_MODE_EXTERNAL_PRICE';

    /**
     * A PCRE to match the hierarchical field path without delimiter.
     *
     * @var string
     */
    protected $complexFieldFilter = 'lineItems/([^/]+)';

    /**
     * Creates the update action for the given class and data.
     *
     * @param mixed $changedValue
     * @param ClassMetadataInterface $metadata
     * @param array $changedData
     * @param array $oldData
     * @param Cart $sourceObject
     * @param string $subFieldName If you work on attributes etc. this is the name of the specific attribute.
     *
     * @return AbstractAction[]
     */
    public function createUpdateActions(
        $changedValue,
        ClassMetadataInterface $metadata,
        array $changedData,
        array $oldData,
        $sourceObject,
        string $subFieldName = ''
    ): array {
        $actions = [];

        // Only process if price was changed
        if (!isset($changedValue['price'])) {
            return $actions;
        }

        $offset = $this->getLastFoundMatch()[1];

        /** @var LineItem $lineItem */
        $lineItem = $sourceObject->getLineItems()->getAt($offset);
        $lineItemId = $lineItem->getId();

        // Do not process on added items
        if (!$lineItemId) {
            return $actions;
        }

        if (defined(static::MODE_CONSTANT)) {
            switch ($lineItem->getPriceMode()) {
                case LineItem::PRICE_MODE_EXTERNAL_PRICE:
                    $actions[] = CartSetLineItemPriceAction::fromArray([
                        'lineItemId' => $lineItemId,
                        'externalPrice' => $this->getCorrectPrice(
                            $lineItem->getPrice()->toArray(),
                            $lineItem->getQuantity()
                        ),
                    ]);

                    break;
                case LineItem::PRICE_MODE_EXTERNAL_TOTAL:
                    $actions[] = CartSetLineItemTotalPriceAction::fromArray([
                        'lineItemId' => $lineItemId,
                        'externalTotalPrice' => ExternalLineItemTotalPrice::fromArray([
                            'price' => $lineItem->getPrice()->getValue(),
                            'totalPrice' => $lineItem->getTotalPrice()
                        ]),
                    ]);

                    break;
            }
        }

        return $actions;
    }
}
