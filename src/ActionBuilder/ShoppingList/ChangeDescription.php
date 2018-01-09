<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\ActionBuilder\ShoppingList;

use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Commercetools\Core\Model\Common\LocalizedString;
use Commercetools\Core\Request\ShoppingLists\Command\ShoppingListSetDescriptionAction;

/**
 * Reacts on the change of the description field and creates the update action.
 *
 * @author blange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\ActionBuilder\ShoppingList
 */
class ChangeDescription extends ShoppingListActionBuilder
{
    /**
     * @var string Working on the description field.
     */
    protected $fieldName = 'description';

    /**
     * Creates the update actions for the given class and data.
     *
     * @param mixed $changedValue
     * @param ClassMetadataInterface $metadata
     * @param array $changedData
     * @param array $oldData
     * @param mixed $sourceObject
     * @return ShoppingListSetDescriptionAction[]
     */
    public function createUpdateActions(
        $changedValue,
        ClassMetadataInterface $metadata,
        array $changedData,
        array $oldData,
        $sourceObject
    ): array {
        return [
            ShoppingListSetDescriptionAction::ofDescription(LocalizedString::fromArray(array_filter(array_merge(
                $oldData['description'] ?? [],
                $changedValue
            ))))
        ];
    }
}
