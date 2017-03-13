<?php

namespace BestIt\CommercetoolsODM\ActionBuilder\Customer;

use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Commercetools\Core\Model\Common\Address;
use Commercetools\Core\Request\Customers\Command\CustomerAddAddressAction;

/**
 * Adds an address.
 * @author lange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM
 * @subpackage ActionBuilder\Customer
 * @version $id$
 */
class AddAddress extends CustomerActionBuilder
{
    /**
     * Matches to the address element.
     * @var string
     */
    protected $complexFieldFilter = '^addresses/\d*$';

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
        $actions = [];

        if ((!array_key_exists('id', $changedValue)) || (!$changedValue['id'])) {
            $actions[] = CustomerAddAddressAction::ofAddress(Address::fromArray($changedValue));
        }

        return $actions;
    }
}
