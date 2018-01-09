<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\ActionBuilder\ShoppingList;

use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Commercetools\Core\Request\ShoppingLists\Command\ShoppingListSetKeyAction;

/**
 * Reacts on the change of the key field and creates the update action.
 *
 * @author blange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\ActionBuilder\ShoppingList
 */
class ChangeKey extends ShoppingListActionBuilder
{
    /**
     * @var string Working on the key field.
     */
    protected $fieldName = 'key';

    /**
     * Creates the update actions for the given class and data.
     *
     * @param mixed $changedValue
     * @param ClassMetadataInterface $metadata
     * @param array $changedData
     * @param array $oldData
     * @param mixed $sourceObject
     * @return ShoppingListSetKeyAction[]
     */
    public function createUpdateActions(
        $changedValue,
        ClassMetadataInterface $metadata,
        array $changedData,
        array $oldData,
        $sourceObject
    ): array {
        $action = new ShoppingListSetKeyAction();

        if ($changedValue) {
            $action->setKey($changedValue);
        }

        return [$action];
    }
}
