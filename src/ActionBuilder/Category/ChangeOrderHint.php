<?php

namespace BestIt\CommercetoolsODM\ActionBuilder\Category;

use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Commercetools\Core\Model\Category\Category;
use Commercetools\Core\Request\AbstractAction;
use Commercetools\Core\Request\Categories\Command\CategoryChangeOrderHintAction;

/**
 * Changes the the order hint value for a category.
 *
 * @package BestIt\CommercetoolsODM\ActionBuilder\Category
 * @subpackage ActionBuilder\Category
 */
class ChangeOrderHint extends CategoryActionBuilder
{
    /**
     * A PCRE to match the hierarchical field path without delimiter.
     *
     * @var string
     */
    protected $complexFieldFilter = '^orderHint$';
    
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
        if ($changedValue === null) {
            return [];
        }

        return [
            $test = CategoryChangeOrderHintAction::ofOrderHint($changedValue),
        ];
    }
}
