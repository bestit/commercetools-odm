<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\Helper;

use Commercetools\Core\Model\Product\Product;
use Commercetools\Core\Model\Product\ProductVariant;
use Commercetools\Core\Model\Product\ProductVariantCollection;

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
     * @param ProductVariantCollection $collection
     * @param string $id
     *
     * @return ProductVariant|null
     */
    private function getVariantById(ProductVariantCollection $collection, string $id)
    {
        foreach ($collection as $variant) {
            if ($variant->getId() === $id) {
                return $variant;
            }
        }

        return null;
    }

    /**
     * Get variant by sku or null if not found
     * Alternative for the sdk "->getBySku" method which can throw exceptions
     *
     * @param ProductVariantCollection $collection
     * @param string $sku
     *
     * @return ProductVariant|null
     */
    private function getVariantBySku(ProductVariantCollection $collection, string $sku)
    {
        foreach ($collection as $variant) {
            if ($variant->getId() === $sku) {
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
}
