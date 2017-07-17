<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\ActionBuilder\Customer;

use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Commercetools\Core\Model\Customer\Customer;
use Commercetools\Core\Request\AbstractAction;
use Commercetools\Core\Request\Customers\Command\CustomerRemoveShippingAddressAction;

/**
 * Removes shipping address ids.
 * @author blange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\ActionBuilder\Customer
 */
class RemoveShippingAddressIds extends CustomerActionBuilder
{
    /**
     * The field for the customer.
     * @var string
     */
    protected $fieldName = 'shippingAddressIds';

    /**
     * Creates the update actions for the given class and data.
     * @param mixed $changedValue
     * @param ClassMetadataInterface $metadata
     * @param array $changedData
     * @param array $oldData
     * @param Customer $sourceObject
     * @return AbstractAction[]
     */
    public function createUpdateActions(
        $changedValue,
        ClassMetadataInterface $metadata,
        array $changedData,
        array $oldData,
        $sourceObject
    ): array {
        $actions = [];
        $addresses = $sourceObject->getAddresses();
        $shippingAddressIds = $sourceObject->getShippingAddressIds();

        foreach ($oldData[$this->getFieldName()] ?? [] as $id) {
            // If the address is removed completely, then we do not need to remove the shipping address id
            if ((!in_array($id, $shippingAddressIds)) && ($addresses->getById($id))) {
                $actions[] = (new CustomerRemoveShippingAddressAction())->setAddressId($id);
            }
        }

        return $actions;
    }
}
