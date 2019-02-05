<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\ActionBuilder\InventoryEntry;

use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Commercetools\Core\Request\AbstractAction;
use Commercetools\Core\Request\Inventory\Command\InventoryChangeQuantityAction;

/**
 * Updates quantityOnStock.
 *
 * @author blange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\ActionBuilder\InventoryEntry
 */
class UpdateQuantityOnStock extends InventoryEntryActionBuilder
{
    /**
     * @var string The updated field.
     */
    protected $fieldName = 'quantityOnStock';

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
        return [InventoryChangeQuantityAction::ofQuantity((int) $changedValue)];
    }
}
