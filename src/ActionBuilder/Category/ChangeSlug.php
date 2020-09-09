<?php

namespace BestIt\CommercetoolsODM\ActionBuilder\Category;

use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Commercetools\Core\Model\Common\LocalizedString;
use Commercetools\Core\Request\AbstractAction;
use Commercetools\Core\Request\Categories\Command\CategoryChangeSlugAction;

/**
 * Changes the slug of the category.
 *
 * @package BestIt\CommercetoolsODM\ActionBuilder\Category
 * @subpackage ActionBuilder\Category
 */
class ChangeSlug extends CategoryActionBuilder
{
    /**
     * A PCRE to match the hierarchical field path without delimiter.
     *
     * @var string
     */
    protected $complexFieldFilter = '^slug$';

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
        $changedValue = array_filter($changedValue);

        if (empty($changedValue)) {
            return [];
        }

        return [
            CategoryChangeSlugAction::ofSlug($sourceObject->getSlug()),
        ];
    }
}
