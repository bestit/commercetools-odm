<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\ActionBuilder\InventoryEntry;

use BestIt\CommercetoolsODM\ActionBuilder\SetCustomFieldBuilderAbstract;
use Commercetools\Core\Model\Inventory\InventoryEntry;

/**
 * Sets a custom field for inventory entries.
 *
 * @author blange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\ActionBuilder\InventoryEntry
 */
class SetCustomField extends SetCustomFieldBuilderAbstract
{
    /**
     * @var string The model class for this builder.
     */
    protected $modelClass = InventoryEntry::class;
}
