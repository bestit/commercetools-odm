<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\ActionBuilder\Category;

use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Commercetools\Core\Model\Category\Category;
use Commercetools\Core\Request\AbstractAction;
use Commercetools\Core\Request\CustomField\Command\SetCustomFieldAction;

/**
 * Builds the action to add a custom field to a category.
 *
 * @package BestIt\CommercetoolsODM\ActionBuilder\Category
 * @subpackage ActionBuilder\Category
 */
class SetCustomField extends CategoryActionBuilder
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
     * @param Category $sourceObject
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

        $setCustomFieldAction = SetCustomFieldAction::ofName($field);

        if (is_array($changedValue)) {
            $changedValue = array_filter($changedValue);
        }

        if ($changedValue) {
            $setCustomFieldAction = $setCustomFieldAction->setValue($changedValue);
        }

        return [
            $setCustomFieldAction,
        ];
    }
}
