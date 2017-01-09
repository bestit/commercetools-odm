<?php

namespace BestIt\CommercetoolsODM\ActionBuilder\Customer;

use BestIt\CommercetoolsODM\ActionBuilder\ActionBuilderAbstract;
use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Commercetools\Core\Model\Common\DateTimeDecorator;
use Commercetools\Core\Model\Customer\Customer;
use Commercetools\Core\Request\AbstractAction;
use Commercetools\Core\Request\CustomField\Command\SetCustomFieldAction;
use DateTime;

/**
 * Builds the action to add an attribute to a product type.
 * @author blange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM
 * @subpackage ActionBuilder\ProductType
 * @version $id$
 */
class SetCustomField extends ActionBuilderAbstract
{
    /**
     * The field name.
     * @var string
     */
    protected $fieldName = 'custom/*';

    /**
     * For which class is this description used?
     * @var string
     */
    protected $modelClass = Customer::class;

    /**
     * Creates the update action for the given class and data.
     * @param mixed $changedValue
     * @param ClassMetadataInterface $metadata
     * @param array $changedData
     * @param array $oldData
     * @param Customer $sourceObject
     * @param string $subFieldName If you work on attributes etc. this is the name of the specific attribute.
     * @return AbstractAction|void
     */
    public function createUpdateAction(
        $changedValue,
        ClassMetadataInterface $metadata,
        array $changedData,
        array $oldData,
        $sourceObject,
        string $subFieldName = ''
    ) {

        if ($changedValue instanceof DateTime) {
            $changedValue = new DateTimeDecorator($changedValue);
        }

        return SetCustomFieldAction::ofName($subFieldName)->setValue($changedValue);
    }
}
