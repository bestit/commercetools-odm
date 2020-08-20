<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\ActionBuilder\ShoppingList;

use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Commercetools\Core\Model\Common\LocalizedString;
use Commercetools\Core\Request\ShoppingLists\Command\ShoppingListSetSlugAction;

/**
 * Reacts on the change of the slug field and creates the update action.
 *
 * @author blange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\ActionBuilder\ShoppingList
 */
class ChangeSlug extends ShoppingListActionBuilder
{
    /**
     * @var string Working on the slug field.
     */
    protected $fieldName = 'slug';

    /**
     * Creates the update actions for the given class and data.
     *
     * @param mixed $changedValue
     * @param ClassMetadataInterface $metadata
     * @param array $changedData
     * @param array $oldData
     * @param mixed $sourceObject
     *
     * @return ShoppingListSetSlugAction[]
     */
    public function createUpdateActions(
        $changedValue,
        ClassMetadataInterface $metadata,
        array $changedData,
        array $oldData,
        $sourceObject
    ): array {
        return [
            ShoppingListSetSlugAction::ofSlug(LocalizedString::fromArray(array_filter(array_merge(
                $oldData['slug'] ?? [],
                $changedValue
            )))),
        ];
    }
}
