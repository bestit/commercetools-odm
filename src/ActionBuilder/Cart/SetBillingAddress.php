<?php

namespace BestIt\CommercetoolsODM\ActionBuilder\Cart;

use BestIt\CommercetoolsODM\ActionBuilder\ActionBuilderAbstract;
use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Commercetools\Core\Model\Cart\Cart;
use Commercetools\Core\Model\Common\Address;
use Commercetools\Core\Request\AbstractAction;
use Commercetools\Core\Request\Carts\Command\CartSetBillingAddressAction;

/**
 * Build the set billing address action.
 *
 * @author Tim Kellner <tim.kellner@bestit-online.de>
 * @package BestIt\CommercetoolsODM\ActionBuilder\Cart
 */
class SetBillingAddress extends CartActionBuilder
{
    /**
     * Field name for the billing address.
     *
     * @var string
     */
    protected $fieldName = 'billingAddress';

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

        if ($changedValue && count($changedValue)) {
            $action = CartSetBillingAddressAction::of();

            $address = array_merge(
                $oldData['billingAddress'] ?? [],
                $changedValue
            );
            $action->setAddress(Address::fromArray($address));

            $actions[] = $action;
        }

        return $actions;
    }
}
