<?php

namespace BestIt\CommercetoolsODM\ActionBuilder\Customer;

use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Commercetools\Core\Request\AbstractAction;
use Commercetools\Core\Request\Customers\Command\CustomerChangeEmailAction;

/**
 * Sets the email on the customer.
 *
 * @author mpotthast <markus.potthast@bestit-online.de>
 * @package BestIt\CommercetoolsODM\ActionBuilder\Customer
 */
class SetEmail extends CustomerActionBuilder
{
    /**
     * The field name.
     *
     * @var string
     */
    protected $fieldName = 'email';

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
        return [CustomerChangeEmailAction::fromArray([$this->getFieldName() => $changedValue])];
    }
}
