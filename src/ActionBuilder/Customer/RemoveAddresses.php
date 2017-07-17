<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\ActionBuilder\Customer;

use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Commercetools\Core\Model\Customer\Customer;
use Commercetools\Core\Request\AbstractAction;
use Commercetools\Core\Request\Customers\Command\CustomerRemoveAddressAction;

/**
 * Removes addresses from the customer.
 * @author blange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\ActionBuilder\Customer
 */
class RemoveAddresses extends AddressActionBuilder
{
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

        foreach ($oldData['addresses'] ?? [] as $addressArray) {
            if ($addressArray && ($addressId = $addressArray['id']) && (!$addresses->getById($addressId))) {
                $actions[] = CustomerRemoveAddressAction::ofAddressId($addressId);
            }
        }

        return $actions;
    }
}
