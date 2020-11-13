<?php

namespace BestIt\CommercetoolsODM\ActionBuilder\Cart;

use BestIt\CommercetoolsODM\ActionBuilder\ActionBuilderAbstract;
use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Commercetools\Core\Model\Cart\Cart;
use Commercetools\Core\Model\Common\Address;
use Commercetools\Core\Request\AbstractAction;
use Commercetools\Core\Request\Carts\Command\CartSetBillingAddressAction;
use Commercetools\Core\Request\Carts\Command\CartSetCustomerIdAction;

/**
 * Set Customer Id Action
 *
 * @author André Varelmann <andre.varelmann@bestit-online.de>
 * @package BestIt\CommercetoolsODM\ActionBuilder\Cart
 */
class SetCustomerId extends CartActionBuilder
{
    /**
     * Field name for the billing address.
     *
     * @var string
     */
    protected $fieldName = 'customerId';

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
        return [CartSetCustomerIdAction::fromArray([$this->getFieldName() => $changedValue])];
    }
}
