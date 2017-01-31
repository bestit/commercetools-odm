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
     * The delimiter for the pcres.
     * @var string
     */
    const FILTER_DELIMITER = '~';

    /**
     * Creates the update actions for the given class and data.
     * @param mixed $changedValue
     * @param ClassMetadataInterface $metadata
     * @param array $changedData
     * @param array $oldData
     * @param mixed $sourceObject
     * @param string $subFieldName If you work on attributes etc. this is the name of the specific attribute.
     * @return AbstractAction[]
     */
    public function createUpdateActions(
        $changedValue,
        ClassMetadataInterface $metadata,
        array $changedData,
        array $oldData,
        $sourceObject,
        string $subFieldName = ''
    ): array;

    /**
     * At which order should this builder be executed? Highest happens first.
     * @return int
     */
    public function getPriority(): int;

    /**
     * Allows this action other actions?
     * @param bool $newStatus The new status.
     * @return bool The old status.
     */
    public function isStackable(bool $newStatus = false): bool;

    /**
     * Returns true if the given class name matches the model class for this description.
     * @param string $fieldPath The hierarchical path of the fields.
     * @param string $referenceClass
     * @return bool|array If there is a complex match, the matched values are returned.
     */
    public function supports(string $fieldPath, string $referenceClass);
}
