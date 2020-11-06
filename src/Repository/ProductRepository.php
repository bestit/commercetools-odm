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
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use SplFileInfo;
use function mime_content_type;

/**
 * The repository for products.
 *
 * @author blange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\Repository
 */
class ProductRepository extends DefaultRepository implements ProductRepositoryInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * Adds the given image to the variant of the product.
     *
     * @throws ResponseException If the image upload did not work correctly.
     *
     * @param SplFileInfo $fileInfo
     * @param Product $product
     * @param int $variantId
     *
     * @return Product The refreshed product.
     */
    public function addImageToProduct(SplFileInfo $fileInfo, Product $product, int $variantId = 1): Product
    {
        $this->logger->debug(
            'Tries to upload an image to a product.',
            $logContext = [
                'imagePath' => $fileInfo->getRealPath(),
                'imageSize' => $fileInfo->getSize(),
                'productId' => $product->getId(),
                'productKey' => $product->getKey(),
                'variantId' => 1,
            ]
        );

        $request = ProductImageUploadRequest::ofIdVariantIdAndFile(
            $product->getId(),
            // Staged because this enforces that it exists in every scope of ct.
            $variantId,
            new UploadedFile(
                $fileInfo->getRealPath(),
                $fileInfo->getSize(),
                UPLOAD_ERR_OK,
                $fileInfo->getBasename(),
                mime_content_type($fileInfo->getRealPath())
            )
        );

        /** @var Product $product */
        /** @var ErrorResponse $imgResponse */
        list($updatedProduct, $imgResponse) = $this->processQuery($request);

        if ($imgResponse->isError()) {
            $this->logger->error(
                'Upload of an image for a product failed.',
                $logContext + ['error' => $imgResponse->getMessage()]
            );

            throw APIException::fromResponse($imgResponse);
        }

        $this->logger->info('Uploaded an image to a product.', $logContext);

        $this->getDocumentManager()->refresh($product, $updatedProduct);

        return $product;
    }

    /**
     * Publishes the given product.
     *
     * @param Product $product
     * @throws ResponseException
     *
     * @return Product
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

        if ($this->logger) {
            $this->logger->emergency('Publish action request', [
                'id' => $product->getId(),
                'version' => $product->getVersion(),
                'actions' => json_encode($request->getActions())
            ]);
        }

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
     *
     * @param Product $product
     * @throws ResponseException
     *
     * @return Product
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

        if ($this->logger) {
            $this->logger->emergency('Unpublish action request', [
                'id' => $product->getId(),
                'version' => $product->getVersion(),
                'actions' => json_encode($request->getActions())
            ]);
        }

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
