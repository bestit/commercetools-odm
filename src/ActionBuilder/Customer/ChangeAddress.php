<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\ActionBuilder\Customer;

use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Commercetools\Core\Model\Common\Address;
use Commercetools\Core\Request\AbstractAction;
use Commercetools\Core\Request\Customers\Command\CustomerChangeAddressAction;

/**
 * Changes an address.
 * @author lange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\ActionBuilder\Customer
 */
class ChangeAddress extends AddressActionBuilder
{
    /**
     * Creates the update actions for the given class and data.
     * @param array $changedValue
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
        list(, $addressIndex) = $this->getLastFoundMatch();

        $actions = [];

        if ((array_key_exists($addressIndex, $oldData['addresses'])) &&
            ($oldData['addresses'][$addressIndex]['id'])
        ) {
            $actions = [
                CustomerChangeAddressAction::ofAddressIdAndAddress(
                    $oldData['addresses'][$addressIndex]['id'],
                    Address::fromArray($changedValue + $oldData['addresses'][$addressIndex])
                )
            ];
        }

        return $actions;
    }
}
