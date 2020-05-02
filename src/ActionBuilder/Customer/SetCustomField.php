<?php

namespace BestIt\CommercetoolsODM\ActionBuilder\Customer;

use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Commercetools\Core\Model\Common\DateTimeDecorator;
use Commercetools\Core\Model\Customer\Customer;
use Commercetools\Core\Model\Type\TypeReference;
use Commercetools\Core\Request\AbstractAction;
use Commercetools\Core\Request\Customers\Command\CustomerSetCustomTypeAction;
use Commercetools\Core\Request\CustomField\Command\SetCustomFieldAction;
use Commercetools\Core\Request\CustomField\Command\SetCustomTypeAction;
use DateTime;

/**
 * Builds the action to add an attribute to a product type.
 *
 * @author blange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\ActionBuilder\Customer
 * @subpackage ActionBuilder\ProductType
 */
class SetCustomField extends CustomerActionBuilder
{
    /**
     * A PCRE to match the hierarchical field path without delimiter.
     *
     * @var string
     */
    protected $complexFieldFilter = 'custom/fields/([^/]*)$';

    /**
     * Creates the update actions for the given class and data.
     *
     * @param mixed $changedValue
     * @param ClassMetadataInterface $metadata
     * @param array $changedData
     * @param array $oldData
     * @param Customer $sourceObject
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
        list(, $field) = $this->getLastFoundMatch();

        if ($changedValue instanceof DateTime) {
            $changedValue = new DateTimeDecorator($changedValue);
        }

        if (is_array($changedValue)) {
            $changedValue = array_filter($changedValue);
        }

        $action = SetCustomFieldAction::ofName($field);

        if ($changedValue !== null) {
            $action->setValue($changedValue);
        }

        $actions = [];

        if (!isset($oldData['custom']) && $field === 'businessPartner') {
            $actions[] = CustomerSetCustomTypeAction::ofType(
                TypeReference::ofKey('customer')
            );
        }

        $actions[] = $action;

        return $actions;
    }
}

