<?php

namespace BestIt\CommercetoolsODM\ActionBuilder\Product;

use Commercetools\Core\Model\Product\Product;

/**
 * Finds the variant using the index from the variants array.
 *
 * @package BestIt\CommercetoolsODM\ActionBuilder\Product
 */
trait VariantIdFinderTrait
{
    /**
     * @param Product $product
     * @param string|int $variantIndex
     *
     * @return int|null
     */
    protected function findVariantIdByVariantIndex(Product $product, $variantIndex)
    {
        $variants = $product->getMasterData()->getCurrent()->getVariants()->toArray();

        if (!isset($variants[$variantIndex])) {
            return null;
        }

        return $variants[$variantIndex]['id'] ?? null;
    }
}
