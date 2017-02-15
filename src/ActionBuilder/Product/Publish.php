<?php

namespace BestIt\CommercetoolsODM\ActionBuilder\Product;

use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Commercetools\Core\Request\AbstractAction;
use Commercetools\Core\Request\Products\Command\ProductPublishAction;

/**
 * Publishes a product.
 * @author blange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM
 * @subpackage ActionBuilder\Product
 * @version $id$
 */
class Publish extends ProductActionBuilder
{
    protected $complexFieldFilter = '^masterData/published$';

    /**
     * Creates the update actions for the given class and data.
     * @param mixed $changedValue
     * @param ClassMetadataInterface $metadata
     * @param array $changedData
     * @param array $oldData
     * @param mixed $sourceObject
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

        if (!empty($changedValue)) {
            $actions[] = new ProductPublishAction();
        }

        return $actions;
    }
}
