<?php

namespace BestIt\CommercetoolsODM\ActionBuilder\ShoppingList;

use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Commercetools\Core\Model\ShoppingList\ShoppingList;
use Commercetools\Core\Request\AbstractAction;
use Commercetools\Core\Request\ShoppingLists\Command\ShoppingListChangeLineItemQuantityAction;

/**
 * Builds the action to change ShoppingList item quantity.
 *
 * @author blange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\ActionBuilder\ShoppingList
 */
class SetLineItemQuantity extends ShoppingListActionBuilder
{
    /**
     * @var string A PCRE to match the hierarchical field path without delimiter.
     */
    protected $complexFieldFilter = '^lineItems/(\d*)$';

    /**
     * Creates the update action for the given class and data.
     *
     * @param mixed $changedValue
     * @param ClassMetadataInterface $metadata
     * @param array $changedData
     * @param array $oldData
     * @param ShoppingList $sourceObject
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
    ): array {
        $actions = [];

        list(, $itemIndex) = $this->getLastFoundMatch();

        $lineItem = $sourceObject->getLineItems()->getAt($itemIndex);

        if ($lineItem && ($lineItemId = $lineItem->getId())) {
            $actions[] = ShoppingListChangeLineItemQuantityAction::ofLineItemIdAndQuantity(
                $lineItemId,
                $changedValue['quantity']
            );
        }

        return $actions;
    }
}
