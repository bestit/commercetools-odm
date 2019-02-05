<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\ActionBuilder\ShoppingList;

use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Commercetools\Core\Request\AbstractAction;
use Commercetools\Core\Request\ShoppingLists\Command\ShoppingListAddLineItemAction;
use function array_key_exists;
use function is_array;

/**
 * Adds a line item to the shopping list.
 *
 * @author lange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\ActionBuilder\ShoppingList
 */
class AddLineItem extends ShoppingListActionBuilder
{
    /**
     * @var string Matches a single element of the line items.
     */
    protected $complexFieldFilter = '^lineItems/(\d*)$';

    /**
     * Creates the update actions for the given class and data.
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
     *
     * @param array $changedValue
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
        $actions = [];

        list(, $itemIndex) = $this->getLastFoundMatch();

        if (is_array($changedValue) && (!array_key_exists($itemIndex, $oldData['lineItems'])) &&
            (!array_key_exists('id', $changedValue))
        ) {
            $actions[] = ShoppingListAddLineItemAction::ofProductIdVariantIdAndQuantity(
                $changedValue['productId'],
                $changedValue['variantId'],
                $changedValue['quantity']
            );
        }

        return $actions;
    }
}
