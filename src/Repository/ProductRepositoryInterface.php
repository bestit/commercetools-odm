<?php

namespace BestIt\CommercetoolsODM\Repository;

use BestIt\CommercetoolsODM\Exception\ResponseException;
use Commercetools\Core\Model\Product\Product;
use SplFileInfo;

/**
 * The interface for the products.
 * @author blange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM
 * @subpackage Repository
 * @version $id$
 */
interface ProductRepositoryInterface extends ObjectRepository
{
    /**
     * Adds the given image to the variant of the product.
     * @param SplFileInfo $fileInfo
     * @param Product $product
     * @param int $variantId
     * @return Product The refreshed product.
     * @throws ResponseException
     */
    public function addImageToProduct(SplFileInfo $fileInfo, Product $product, int $variantId = 1): Product;

    /**
     * Publishes the given product.
     * @param Product $product
     * @return Product
     * @throws ResponseException
     */
    public function publish(Product $product): Product;

    /**
     * Unpublishes the given product.
     * @param Product $product
     * @return Product
     * @throws ResponseException
     */
    public function unpublish(Product $product): Product;
}
