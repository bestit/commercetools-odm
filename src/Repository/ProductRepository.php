<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\Repository;

use BestIt\CommercetoolsODM\DocumentManagerInterface;
use BestIt\CommercetoolsODM\Exception\APIException;
use BestIt\CommercetoolsODM\Exception\ResponseException;
use BestIt\CommercetoolsODM\Model\DefaultRepository;
use Commercetools\Core\Model\Product\Product;
use Commercetools\Core\Request\Products\Command\ProductPublishAction;
use Commercetools\Core\Request\Products\Command\ProductUnpublishAction;
use Commercetools\Core\Request\Products\ProductImageUploadRequest;
use Commercetools\Core\Response\ErrorResponse;
use GuzzleHttp\Psr7\UploadedFile;
use SplFileInfo;

/**
 * The repository for products.
 *
 * @author blange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\Repository
 */
class ProductRepository extends DefaultRepository implements ProductRepositoryInterface
{
    /**
     * Adds the given image to the variant of the product.
     * @param SplFileInfo $fileInfo
     * @param Product $product
     * @param int $variantId
     * @return Product The refreshed product.
     * @throws ResponseException
     */
    public function addImageToProduct(SplFileInfo $fileInfo, Product $product, int $variantId = 1): Product
    {
        $documentManager = $this->getDocumentManager();

        $request = ProductImageUploadRequest::ofIdVariantIdAndFile(
            $product->getId(),
            // Staged because it exists everytime!
            $variantId,
            new UploadedFile(
                $fileInfo->getRealPath(),
                $fileInfo->getSize(),
                UPLOAD_ERR_OK,
                $fileInfo->getBasename()
            )
        );

        /** @var Product $product */
        /** @var ErrorResponse $imgResponse */
        list($updatedProduct, $imgResponse) = $this->processQuery($request);

        if ($imgResponse->isError()) {
            throw APIException::fromResponse($imgResponse);
        }

        $documentManager->refresh($product, $updatedProduct);

        return $product;
    }

    /**
     * Publishes the given product.
     * @param Product $product
     * @return Product
     * @throws ResponseException
     */
    public function publish(Product $product): Product
    {
        $documentManager = $this->getDocumentManager();

        $request = $documentManager->createRequest(
            $this->getClassName(),
            DocumentManagerInterface::REQUEST_TYPE_UPDATE_BY_ID,
            $product->getId(),
            $product->getVersion()
        );

        $request->addAction(new ProductPublishAction());

        /** @var Product $product */
        /** @var ErrorResponse $updateResponse */
        list($updatedProduct, $updateResponse) = $this->processQuery($request);

        if ($updateResponse->isError()) {
            throw APIException::fromResponse($updateResponse);
        }

        $documentManager->refresh($product, $updatedProduct);

        return $product;
    }

    /**
     * Unpublishes the given product.
     * @param Product $product
     * @return Product
     * @throws ResponseException
     */
    public function unpublish(Product $product): Product
    {
        $documentManager = $this->getDocumentManager();

        $request = $documentManager->createRequest(
            $this->getClassName(),
            DocumentManagerInterface::REQUEST_TYPE_UPDATE_BY_ID,
            $product->getId(),
            $product->getVersion()
        );

        $request->addAction(new ProductUnpublishAction());

        /** @var Product $product */
        /** @var ErrorResponse $updateResponse */
        list($updatedProduct, $updateResponse) = $this->processQuery($request);

        if ($updateResponse->isError()) {
            throw APIException::fromResponse($updateResponse);
        }

        $documentManager->refresh($product, $updatedProduct);

        return $product;
    }
}
