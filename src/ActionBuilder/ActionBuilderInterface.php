<?php

namespace BestIt\CommercetoolsODM\ActionBuilder;

use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Commercetools\Core\Request\AbstractAction;

/**
 * Builds the action to add an attribute to a product type.
 * @author blange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM
 * @subpackage ActionBuilder\ProductType
 * @version $id$
 */
interface ActionBuilderInterface
{
    /**
     * Creates the update action for the given class and data.
     * @param mixed $changedValue
     * @param ClassMetadataInterface $metadata
     * @param array $changedData
     * @param array $oldData
     * @param mixed $sourceObject
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
    );

    /**
     * Returns the name of the field.
     * @return string
     */
    public function getFieldName() : string;

    /**
     * At which order should this builder be executed? Highest happens first.
     * @return int
     */
    public function getPriority() : int;

    /**
     * Allows this action other actions?
     * @param bool $newStatus The new status.
     * @return bool The old status.
     */
    public function isStackable(bool $newStatus = false) : bool;

    /**
     * Returns true if the given class name matches the model class for this description.
     * @param string $fieldName
     * @param string $referenceClass
     * @return bool
     */
    public function supports(string $fieldName, string $referenceClass) : bool;
}
