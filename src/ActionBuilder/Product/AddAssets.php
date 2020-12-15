<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\ActionBuilder\Product;

use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Commercetools\Core\Model\Common\AssetDraft;
use Commercetools\Core\Model\Product\Product;
use Commercetools\Core\Request\AbstractAction;
use Commercetools\Core\Request\Products\Command\ProductAddAssetAction;
use function ucfirst;

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
        $variantId = $this->loadAssetsVariantIndex($sourceObject);

        // no update needed for new variants
        if ($variantId === null) {
            return [];
        }

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
     * @param Product $sourceObject
     *
     * @return int|null
     */
    private function loadAssetsVariantIndex(Product $sourceObject)
    {
        list(, $productCatalogContainer, $variantContainer, $variantOffset) = $this->getLastFoundMatch();

        $productData = $sourceObject->getMasterData()->{'get' . ucfirst($productCatalogContainer)}();

        if ($variantContainer === 'masterVariant') {
            return $productData->getMasterVariant()->getId();
        }

        return $productData->getVariants()->toArray()[$variantOffset]['id'] ?? null;
    }
}
