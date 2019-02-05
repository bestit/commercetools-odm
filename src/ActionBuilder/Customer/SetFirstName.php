<?php

namespace BestIt\CommercetoolsODM\ActionBuilder\Customer;

use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Commercetools\Core\Request\AbstractAction;
use Commercetools\Core\Request\Customers\Command\CustomerSetFirstNameAction;

/**
 * Sets the first name on the customer.
 *
 * @author blange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\ActionBuilder\Customer
 * @subpackage ActionBuilder\Customer
 */
class SetFirstName extends CustomerActionBuilder
{
    /**
     * The field name.
     *
     * @var string
     */
    protected $fieldName = 'firstName';

    /**
     * Creates the update actions for the given class and data.
     *
     * @param mixed $changedValue
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
        return [CustomerSetFirstNameAction::fromArray([$this->getFieldName() => $changedValue])];
    }
}
