<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\ActionBuilder\Order;

use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Commercetools\Core\Request\AbstractAction;
use Commercetools\Core\Request\Orders\Command\OrderUpdateSyncInfoAction;

/**
 * Refreshes the sync info for the order with the actual data
 *
 * @author blange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\ActionBuilder\Order
 */
class UpdateSyncInfo extends OrderActionBuilder
{
    /**
     * @var string The query for the sync info field.
     */
    protected $fieldName = 'syncInfo';

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
        $actions = [];

        if (@$changedValue[0]) {
            $actions[] = OrderUpdateSyncInfoAction::fromArray($changedValue[0]);
        }

        return $actions;
    }
}
