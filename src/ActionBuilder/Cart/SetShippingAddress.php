<?php

namespace BestIt\CommercetoolsODM\ActionBuilder\Cart;

use BestIt\CommercetoolsODM\ActionBuilder\ActionBuilderAbstract;
use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Commercetools\Core\Model\Cart\Cart;
use Commercetools\Core\Model\Common\Address;
use Commercetools\Core\Request\AbstractAction;
use Commercetools\Core\Request\Carts\Command\CartSetShippingAddressAction;

/**
 * Builds the action to change cart shipping address
 * @author chowanski <chowanski@bestit-online.de>
 * @package BestIt\CommercetoolsODM
 * @subpackage ActionBuilder\Cart
 * @version $id$
 */
class SetShippingAddress extends CartActionBuilder
{
    /**
     * @inheritdoc
     */
    protected $fieldName = 'shippingAddress';

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
        $actions = [];

        if ($changedValue && count($changedValue)) {
            $action = CartSetShippingAddressAction::of();

            $address = array_merge(
                $oldData['shippingAddress'] ?? [],
                $changedValue
            );
            $action->setAddress(Address::fromArray($address));

            $actions[] = $action;
        }

        return $actions;
    }
}
