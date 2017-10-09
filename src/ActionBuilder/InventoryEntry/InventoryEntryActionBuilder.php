<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\ActionBuilder\InventoryEntry;

use BestIt\CommercetoolsODM\ActionBuilder\ActionBuilderAbstract;
use Commercetools\Core\Model\Inventory\InventoryEntry;

/**
 * The basic action builder for inventory entries.
 *
 * @author blange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\ActionBuilder\InventoryEntry
 */
abstract class InventoryEntryActionBuilder extends ActionBuilderAbstract
{
    /**
     * @var string The model for this action builder.
     */
    protected $modelClass = InventoryEntry::class;
}
