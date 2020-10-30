<?php

namespace BestIt\CommercetoolsODM\ActionBuilder\Customer;

use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Commercetools\Core\Request\AbstractAction;
use Commercetools\Core\Request\Customers\Command\CustomerSetCompanyNameAction;
use Commercetools\Core\Request\Customers\Command\CustomerSetVatIdAction;

/**
 * Sets the company name on the customer.
 *
 * @author André Varelmann <andre-varelmann@bestit-online.de>
 * @package BestIt\CommercetoolsODM\ActionBuilder\Customer
 */
class SetCompanyName extends CustomerActionBuilder
{
    /**
     * The field name.
     *
     * @var string
     */
    protected $fieldName = 'companyName';

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
        return [CustomerSetCompanyNameAction::fromArray([$this->getFieldName() => $changedValue])];
    }
}
