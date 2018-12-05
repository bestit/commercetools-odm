<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\ActionBuilder\Product;

use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Commercetools\Core\Model\Common\AssetDraft;
use Commercetools\Core\Request\AbstractAction;
use Commercetools\Core\Request\Products\Command\ProductAddAssetAction;

/**
 * Adds assets to the products.
 *
 * @author blange <bjoern.lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\ActionBuilder\Product
 */
class AddAssets extends ProductActionBuilder
{
    /**
     * A PCRE to match the hierarchical field path without delimiter.
     *
     * @var string
     */
    protected $complexFieldFilter = '^masterData/(current|staged)/(masterVariant|variants)/([\d]*)/?assets$';

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
        $variantId = $this->loadOldAssetsVariantIndex($oldData);

        $actions = [];

        foreach ($changedValue as $assetIndex => $asset) {
            if (!@$asset['id']) {
                $actions[] = ProductAddAssetAction::ofVariantIdAndAsset(
                    $variantId,
                    AssetDraft::fromArray($asset)
                );
            }
        }

        return $actions;
    }

    /**
     * Returns the found variant id.
     *
     * @param array $oldData
     *
     * @return int
     */
    private function loadOldAssetsVariantIndex(array $oldData): int
    {
        list(, $productCatalogContainer, $variantContainer, $variantIndex) = $this->getLastFoundMatch();

        return $variantContainer === 'masterVariant'
            ? 1
            : $oldData['masterData'][$productCatalogContainer][$variantContainer][$variantIndex]['id'];
    }
}
