<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\Helper;

use Commercetools\Core\Model\Product\Product;
use Commercetools\Core\Model\Product\ProductData;
use Commercetools\Core\Model\Product\ProductVariant;

/**
 * Helper to get variants by id or sku
 *
 * @package BestIt\CommercetoolsODM\Helper
 */
trait VariantFinderTrait
{
    /**
     * Get variant by id or null if not found
     * Alternative for the sdk "->getById" method which can throw exceptions
     *
     * @param ProductData $productData
     * @param int $id
     *
     * @return ProductVariant|null
     */
    private function getVariantById(ProductData $productData, int $id)
    {
        $allVariants = array_merge(
            [$productData->getMasterVariant()],
            iterator_to_array($productData->getVariants())
        );

        foreach ($allVariants as $variant) {
            if ($variant->getId() === $id) {
                return $variant;
            }
        }

        return null;
    }

    /**
     * Find variant id by variant index
     *
     * @param Product $product
     * @param string|int $variantIndex
     * @param string $mode
     *
     * @return int|null
     */
    private function findVariantIdByVariantIndex(Product $product, $variantIndex, string $mode)
    {
        $variants = $product->getMasterData()->{'get' . ucfirst($mode)}()->getVariants()->toArray();

        if (!isset($variants[$variantIndex])) {
            return null;
        }

        return $variants[$variantIndex]['id'] ?? null;
    }

    /**
     * @param ProductData $productData
     * @param array $oldData
     *
     * @return int|null
     */
    private function findVariantIdBySku(ProductData $productData, array $oldData)
    {

    }
}
