<?php

namespace BestIt\CommercetoolsODM\ActionBuilder\Category;

use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Commercetools\Core\Model\Category\CategoryReference;
use Commercetools\Core\Request\AbstractAction;
use Commercetools\Core\Request\Categories\Command\CategoryChangeParentAction;
use function Couchbase\defaultDecoder;

/**
 * Changes the parent of the category.
 *
 * @package BestIt\CommercetoolsODM\ActionBuilder\Category
 * @subpackage ActionBuilder\Category
 */
class ChangeParent extends CategoryActionBuilder
{
    /**
     * A PCRE to match the hierarchical field path without delimiter.
     *
     * @var string
     */
    protected $complexFieldFilter = '^parent$';
    
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
        if (empty($changedValue)) {
            return [];
        }

        return [
            CategoryChangeParentAction::ofParentCategory(
                CategoryReference::fromArray($changedValue)
            ),
        ];
    }
}
