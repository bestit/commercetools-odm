<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\ActionBuilder\Order;

use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Commercetools\Core\Request\AbstractAction;
use Commercetools\Core\Request\Orders\Command\OrderAddDeliveryAction;

/**
 * Adds deliveries to the order.
 *
 * @author blange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\ActionBuilder\Order
 */
class AddDelivery extends OrderActionBuilder
{
    /**
     * The field name filter.
     *
     * @var string
     */
    protected $complexFieldFilter = '^shippingInfo/deliveries/(\d+)$';

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
        return [
            OrderAddDeliveryAction::fromArray($changedValue)
        ];
    }
}
