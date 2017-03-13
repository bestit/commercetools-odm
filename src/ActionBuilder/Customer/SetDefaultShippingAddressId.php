<?php

namespace BestIt\CommercetoolsODM\ActionBuilder\Customer;

use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Commercetools\Core\Request\AbstractAction;
use Commercetools\Core\Request\Customers\Command\CustomerSetDefaultBillingAddressAction;
use Commercetools\Core\Request\Customers\Command\CustomerSetDefaultShippingAddressAction;

/**
 * Sets the default shipping address id.
 * @author blange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM
 * @subpackage ActionBuilder\Customer
 * @version $id$
 */
class SetDefaultShippingAddressId extends CustomerActionBuilder
{
    /**
     * The field for the customer.
     * @var string
     */
    protected $fieldName = 'defaultShippingAddressId';

    /**
     * Creates the update actions for the given class and data.
     * @param mixed $changedValue
     * @param ClassMetadataInterface $metadata
     * @param array $changedData
     * @param array $oldData
     * @param mixed $sourceObject
     * @return AbstractAction[]
     */
    public function createUpdateActions(
        $changedValue,
        ClassMetadataInterface $metadata,
        array $changedData,
        array $oldData,
        $sourceObject
    ): array {
        return [
            (new CustomerSetDefaultShippingAddressAction())->setAddressId($changedValue)
        ];
    }
}
