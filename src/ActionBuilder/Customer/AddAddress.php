<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\ActionBuilder\Customer;

use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Commercetools\Core\Model\Common\Address;
use Commercetools\Core\Request\AbstractAction;
use Commercetools\Core\Request\Customers\Command\CustomerAddAddressAction;

/**
 * Adds an address.
 *
 * @author lange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\ActionBuilder\Customer
 */
class AddAddress extends AddressActionBuilder
{
    /**
     * Creates the update actions for the given class and data.
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
     *
     * @param array $changedValue
     * @param ClassMetadataInterface $metadata
     * @param array $changedData
     * @param array $oldData
     * @param mixed $sourceObject
     *
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

        list(, $addressIndex) = $this->getLastFoundMatch();

        if (is_array($changedValue) && (!array_key_exists($addressIndex, $oldData['addresses'])) &&
            (!array_key_exists('id', $changedValue))
        ) {
            $actions[] = CustomerAddAddressAction::ofAddress(Address::fromArray($changedValue));
        }

        return $actions;
    }
}
