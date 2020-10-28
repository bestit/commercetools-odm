<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\ActionBuilder\Cart;

use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Commercetools\Core\Model\Cart\Cart;
use Commercetools\Core\Model\Cart\ExternalTaxAmountDraft;
use Commercetools\Core\Model\Cart\LineItem;
use Commercetools\Core\Model\TaxCategory\ExternalTaxRateDraft;
use Commercetools\Core\Request\Carts\Command\CartSetLineItemTaxAmountAction;

/**
 * @package BestIt\CommercetoolsODM\ActionBuilder\Cart
 */
class SetLineItemTax extends CartActionBuilder
{
    /**
     * A PCRE to match the hierarchical field path without delimiter.
     *
     * @var string
     */
    protected $complexFieldFilter = 'lineItems/([^/]+)';

    /**
     * @param mixed $changedValue
     * @param ClassMetadataInterface $metadata
     * @param array $changedData
     * @param array $oldData
     * @param mixed $sourceObject
     *
     * @return array
     */
    public function createUpdateActions($changedValue, ClassMetadataInterface $metadata, array $changedData, array $oldData, $sourceObject): array
    {
        $actions = [];

        $offset = $this->getLastFoundMatch()[1];
        $lineItem = $sourceObject->getLineItems()->getAt($offset);

        /** @var Cart $sourceObject */
        if (
            $lineItem->getPriceMode() !== LineItem::PRICE_MODE_EXTERNAL_TOTAL
            || $sourceObject->getTaxMode() !== Cart::TAX_MODE_EXTERNAL_AMOUNT
            || !isset($changedValue['taxRate'], $changedValue['taxedPrice'])
        ) {
            return $actions;
        }

        $lineItemId = $lineItem->getId();

        /** @var Cart $sourceObject */
        $actions[] = CartSetLineItemTaxAmountAction::fromArray([
            'lineItemId' => $lineItemId,
            'externalTaxAmount' => ExternalTaxAmountDraft::fromArray([
                'totalGross' => $changedValue['taxedPrice']['totalGross'],
                'taxRate' => ExternalTaxRateDraft::fromArray($changedValue['taxRate']),
            ]),
        ]);

        return $actions;
    }
}
