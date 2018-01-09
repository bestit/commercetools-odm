<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\ActionBuilder\ShoppingList;

use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Commercetools\Core\Model\Common\LocalizedString;
use Commercetools\Core\Request\ShoppingLists\Command\ShoppingListChangeNameAction;

/**
 * Reacts on the change of the name field and creates the update action.
 *
 * @author blange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\ActionBuilder\ShoppingList
 */
class ChangeName extends ShoppingListActionBuilder
{
    /**
     * @var string Working on the name field.
     */
    protected $fieldName = 'name';

    /**
     * Creates the update actions for the given class and data.
     *
     * @param mixed $changedValue
     * @param ClassMetadataInterface $metadata
     * @param array $changedData
     * @param array $oldData
     * @param mixed $sourceObject
     * @return ShoppingListChangeNameAction[]
     */
    public function createUpdateActions(
        $changedValue,
        ClassMetadataInterface $metadata,
        array $changedData,
        array $oldData,
        $sourceObject
    ): array {
        return [
            ShoppingListChangeNameAction::ofName(LocalizedString::fromArray(array_filter(array_merge(
                $oldData['name'] ?? [],
                $changedValue
            ))))
        ];
    }
}
