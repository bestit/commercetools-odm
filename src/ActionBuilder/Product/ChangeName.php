<?php

namespace BestIt\CommercetoolsODM\ActionBuilder\Product;

use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Commercetools\Core\Model\Common\LocalizedString;
use Commercetools\Core\Request\AbstractAction;
use Commercetools\Core\Request\Products\Command\ProductChangeNameAction;

/**
 * Changes the name of the product catalog data.
 *
 * @author blange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\ActionBuilder\Product
 * @subpackage ActionBuilder\Product
 */
class ChangeName extends ProductActionBuilder
{
    /**
     * A PCRE to match the hierarchical field path without delimiter.
     *
     * @var string
     */
    protected $complexFieldFilter = '^masterData/(current|staged)/name$';
    
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
        list(, $productCatalogData) = $this->getLastFoundMatch();

        return [
            ProductChangeNameAction::ofName(LocalizedString::fromArray(array_filter(array_merge(
                $oldData['masterData'][$productCatalogData]['name'] ?? [],
                $changedValue
            ))))->setStaged($productCatalogData === 'staged'),
        ];
    }
}
