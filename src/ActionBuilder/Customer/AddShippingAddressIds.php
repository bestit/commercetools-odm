<?php

namespace BestIt\CommercetoolsODM\ActionBuilder\Customer;

use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Commercetools\Core\Model\Customer\Customer;
use Commercetools\Core\Request\Customers\Command\CustomerAddShippingAddressAction;

/**
 * Adds an shipping address id.
 * @author lange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM
 * @subpackage ActionBuilder\Customer
 * @version $id$
 */
class AddShippingAddressIds extends CustomerActionBuilder
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

        if ($changedValue && is_array($changedValue)) {
            $addresses = $sourceObject->getAddresses();
            $fieldName = $this->getFieldName();

            foreach ($changedValue as $id) {
                if ((!array_key_exists($fieldName, $oldData)) || (!is_array($oldData[$fieldName])) ||
                    (!in_array($id, $oldData[$fieldName])) && ($addresses->getById($id))
                ) {
                    $actions[] = (new CustomerAddShippingAddressAction())->setAddressId($id);
                }
            }
        }

        return $actions;
    }
}
