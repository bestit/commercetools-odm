<?php

namespace BestIt\CommercetoolsODM\ActionBuilder\Customer;

use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Commercetools\Core\Request\AbstractAction;
use Commercetools\Core\Request\Customers\Command\CustomerRemoveShippingAddressAction;

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
     * @param mixed $sourceObject
     * @return AbstractAction[]
     * @todo Not tested yet.
     */
    public function createUpdateActions(
        $changedValue,
        ClassMetadataInterface $metadata,
        array $changedData,
        array $oldData,
        $sourceObject
    ): array {
        $actions = [];
        $fieldName = $this->getFieldName();

        if ($oldData && array_key_exists($fieldName, $oldData) && is_array($oldData[$fieldName])) {
            foreach ($oldData[$fieldName] as $id) {
                if ((!is_array($changedValue)) || (array_search($id, $changedValue) === false)) {
                    $actions[] = (new CustomerRemoveShippingAddressAction())->setAddressId($id);
                }
            }
        }

        return $actions;
    }
}
