<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\ActionBuilder\ShoppingList;

use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Commercetools\Core\Model\ShoppingList\ShoppingList;
use Commercetools\Core\Request\AbstractAction;
use Commercetools\Core\Request\ShoppingLists\Command\ShoppingListRemoveLineItemAction;

/**
 * Builds the action to remove a line item.
 *
 * @author blange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\ActionBuilder\ShoppingList
 */
class RemoveLineItem extends ShoppingListActionBuilder
{
    /**
     * @var string A PCRE to match the hierarchical field path without delimiter.
     */
    protected $complexFieldFilter = '^lineItems$';

    /**
     * Creates the update action for the given class and data.
     *
     * @param mixed $changedValue
     * @param ClassMetadataInterface $metadata
     * @param array $changedData
     * @param array $oldData
     * @param ShoppingList $sourceObject
     * @param string $subFieldName If you work on attributes etc. this is the name of the specific attribute.
     *
     * @return AbstractAction[]
     */
    public function createUpdateActions(
        $changedValue,
        ClassMetadataInterface $metadata,
        array $changedData,
        array $oldData,
        $sourceObject,
        string $subFieldName = ''
    ): array {
        $actions = [];

        $lineItems = $sourceObject->getLineItems();

        foreach ($oldData['lineItems'] ?? [] as $index => $lineItemArray) {
            // offsetExists Workaround against the symfony exception for the "missing index" notice in the getter of
            // the ct sdk
            if ($lineItemArray && ($lineItemId = $lineItemArray['id']) && ((!$lineItems->offsetExists($index)) ||
                ($lineItems->getAt($index)->getId() !== $lineItemId))) {
                $actions[] = ShoppingListRemoveLineItemAction::ofLineItemId($lineItemId);
            }
        }

        return $actions;
    }
}
