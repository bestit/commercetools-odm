<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\ActionBuilder\Cart;

use BestIt\CommercetoolsODM\ActionBuilder\ActionBuilderAbstract;
use BestIt\CommercetoolsODM\Helper\PriceHelperTrait;
use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Commercetools\Core\Model\Cart\Cart;
use Commercetools\Core\Model\Cart\LineItem;
use Commercetools\Core\Model\Channel\ChannelReference;
use Commercetools\Core\Request\AbstractAction;
use Commercetools\Core\Request\Carts\Command\CartAddLineItemAction;

/**
 * Builds the action to add cart item
 * @author blange <lange@bestit-online.de>
 * @author chowanski <chowanski@bestit-online.de>
 * @package BestIt\CommercetoolsODM\ActionBuilder\Cart
 */
class AddLineItem extends CartActionBuilder
{
    use PriceHelperTrait;

    /**
     * A PCRE to match the hierarchical field path without delimiter.
     * @var string
     */
    protected $complexFieldFilter = '^lineItems/[^/]+';

    /**
     * Creates the update action for the given class and data.
     * @param mixed $changedValue
     * @param ClassMetadataInterface $metadata
     * @param array $changedData
     * @param array $oldData
     * @param Cart $sourceObject
     * @param string $subFieldName If you work on attributes etc. this is the name of the specific attribute.
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
        // Process only on new items
        if (!isset($changedValue['productId']) || !$changedValue['productId']) {
            return [];
        }

        $cartAddLineItemAction = [
            'productId' => $changedValue['productId'],
            'variantId' => $changedValue['variant']['id'],
            'quantity' => $changedValue['quantity']
        ];

        if (array_key_exists('custom', $changedValue)) {
            $cartAddLineItemAction['custom'] = $changedValue['custom'];
        }

        if (array_key_exists('priceMode', $changedValue)
            && defined('Commercetools\Core\Model\Cart\LineItem::PRICE_MODE_EXTERNAL_PRICE')
            && $changedValue['priceMode'] === LineItem::PRICE_MODE_EXTERNAL_PRICE
            && array_key_exists('price', $changedValue)
        ) {
            $cartAddLineItemAction['externalPrice'] = $this->getCorrectPrice(
                $changedValue['price'],
                $changedValue['quantity']
            );
        }

        $action = CartAddLineItemAction::fromArray($cartAddLineItemAction);

        if (isset($changedValue['distributionChannel'])) {
            if (isset($changedValue['distributionChannel']['id'])) {
                $action->setDistributionChannel(ChannelReference::ofId($changedValue['distributionChannel']['id']));
            } elseif (isset($changedValue['distributionChannel']['key'])) {
                $action->setDistributionChannel(ChannelReference::ofKey($changedValue['distributionChannel']['key']));
            }
        }

        return [$action];
    }
}
