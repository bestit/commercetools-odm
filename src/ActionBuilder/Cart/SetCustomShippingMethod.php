<?php

namespace BestIt\CommercetoolsODM\ActionBuilder\Cart;

use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Commercetools\Core\Model\Cart\Cart;
use Commercetools\Core\Model\ShippingMethod\ShippingRate;
use Commercetools\Core\Model\TaxCategory\TaxCategoryReference;
use Commercetools\Core\Request\AbstractAction;
use Commercetools\Core\Request\Carts\Command\CartSetCustomShippingMethodAction;

/**
 * Set custom shipping method
 *
 * @author Michel Chowanski <chowanski@bestit-online.de>
 * @package BestIt\CommercetoolsODM\ActionBuilder\Cart
 */
class SetCustomShippingMethod extends CartActionBuilder
{
    /**
     * Key for setting a custom shipping method
     */
    const IS_CUSTOM = 'IsCustomShippingMethod';

    /**
     * A PCRE to match the hierarchical field path without delimiter.
     *
     * @var string
     */
    protected $complexFieldFilter = 'shippingInfo/(.*)$';

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

        if (($state = $changedData['shippingMethodState'] ?? null) === static::IS_CUSTOM) {
            $origin = $oldData['shippingInfo'];
            $info = [
                'shippingMethodName' => $changedData['shippingMethodName'] ?? $origin['shippingMethodName'],
                'shippingRate' => array_replace_recursive($origin['shippingRate'], $changedData['shippingRate'] ?? []),
                'taxCategory' => array_replace_recursive($origin['taxCategory'], $changedData['taxCategory'] ?? []),
            ];

            $action = CartSetCustomShippingMethodAction::of();
            $action->setShippingMethodName($info['shippingMethodName']);
            $action->setShippingRate(ShippingRate::fromArray($info['shippingRate']));
            $action->setTaxCategory(TaxCategoryReference::ofId($info['taxCategory']['id']));

            $actions[] = $action;
        }

        return $actions;
    }
}
