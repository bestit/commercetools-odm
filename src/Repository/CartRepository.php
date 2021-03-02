<?php

namespace BestIt\CommercetoolsODM\Repository;

use BestIt\CommercetoolsODM\DocumentManagerInterface;
use BestIt\CommercetoolsODM\Exception\APIException;
use BestIt\CommercetoolsODM\Model\DefaultRepository;
use Commercetools\Core\Model\Cart\Cart;
use Commercetools\Core\Model\Order\Order;
use Commercetools\Core\Request\Carts\CartReplicateRequest;
use Commercetools\Core\Request\Carts\CartUpdateRequest;
use Commercetools\Core\Request\Carts\Command\CartRecalculateAction;
use Commercetools\Core\Request\InStores\InStoreRequestDecorator;
use Commercetools\Core\Response\ApiResponseInterface;
use RuntimeException;

/**
 * Repository for the carts.
 *
 * @author chowanski <chowanski@bestit-online.de>
 * @package BestIt\CommercetoolsODM\Repository
 * @subpackage Repository
 */
class CartRepository extends DefaultRepository
{
    /**
     * Returns a cart object or null by customer id.
     *
     * @param string $id
     * @throws APIException
     *
     * @return mixed
     */
    public function findByCustomerId(string $id)
    {
        /** @var DocumentManagerInterface $documentManager */
        $documentManager = $this->getDocumentManager();
        $uow = $documentManager->getUnitOfWork();

        $document = $uow->tryGetByCustomerId($id);

        if (!$document) {
            $request = $documentManager->createRequest(
                $this->getClassName(),
                DocumentManagerInterface::REQUEST_TYPE_FIND_BY_CUSTOMER_ID,
                $id
            );

            /** @var ApiResponseInterface $rawResponse */
            list ($response, $rawResponse) = $this->processQuery($request);

            if ($rawResponse->getStatusCode() !== 404) {
                if ($rawResponse->isError()) {
                    throw APIException::fromResponse($rawResponse);
                }

                $document = $uow->createDocument($this->getClassName(), $response, []);
            }
        }

        return $document;
    }

    /**
     * Create cart from order
     *
     * @param Order $order
     *
     * @return Cart
     */
    public function createFromOrder(Order $order): Cart
    {
        $request = CartReplicateRequest::ofOrderId($order->getId());

        if (class_exists('Commercetools\Core\Request\InStores\InStoreRequestDecorator') && $order->getStore()) {
            $request = InStoreRequestDecorator::ofStoreKeyAndRequest($order->getStore()->getKey(), $request);
        }

        list($response) = $this->processQuery($request);

        if (!$response instanceof Cart) {
            $this->logger->error('Unexpected response for cart from order request', [
                'cartId' => $order->getId(),
                'version' => $order->getVersion(),
                'response' => $response,
            ]);

            throw new RuntimeException('Unexpected response for cart from order request');
        }

        $this->getDocumentManager()->getUnitOfWork()->registerAsManaged(
            $response,
            $response->getId(),
            $response->getVersion()
        );

        return $response;
    }

    /**
     * Duplicate cart
     *
     * @param Cart $cart
     *
     * @return Cart
     */
    public function duplicateCart(Cart $cart): Cart
    {
        $request = CartReplicateRequest::ofCartId($cart->getId());

        if (class_exists('Commercetools\Core\Request\InStores\InStoreRequestDecorator') && $cart->getStore()) {
            $request = InStoreRequestDecorator::ofStoreKeyAndRequest($cart->getStore()->getKey(), $request);
        }

        list($response) = $this->processQuery($request);

        if (!$response instanceof Cart) {
            $this->logger->error('Unexpected response for cart duplicate request', [
                'cartId' => $cart->getId(),
                'version' => $cart->getVersion(),
                'response' => $response,
            ]);

            throw new RuntimeException('Unexpected response for cart duplicate request');
        }

        $this->getDocumentManager()->getUnitOfWork()->registerAsManaged(
            $response,
            $response->getId(),
            $response->getVersion()
        );

        return $response;
    }

    /**
     * Recalculate cart.
     *
     * @param Cart $cart
     * @param bool $updateProductData Update product data?
     *
     * @return Cart
     */
    public function recalculateCart(Cart $cart, bool $updateProductData = false): Cart
    {
        return $this->recalculateCartByIdAndVersion(
            $cart->getId(),
            $cart->getVersion(),
            $updateProductData
        );
    }

    /**
     * Recalculate cart by cart id, version.
     *
     * @throws RuntimeException
     *
     * @param string $id Id of recalculated cart.
     * @param int $version Id of recalculated cart.
     * @param bool $updateProductData Update product data?
     *
     * @return Cart
     */
    public function recalculateCartByIdAndVersion(string $id, int $version, bool $updateProductData = false): Cart
    {
        $request = CartUpdateRequest::ofIdAndVersion($id, $version)
            ->setActions([CartRecalculateAction::of()->setUpdateProductData($updateProductData)]);

        list($response) = $this->processQuery($request);

        if (!$response instanceof Cart) {
            $this->logger->error('Unexpected response for cart recalculation', [
                'cartId' => $id,
                'version' => $version,
                'updateProductData' => $updateProductData,
                'response' => $response,
            ]);

            throw new RuntimeException('Unexpected response for cart recalculation');
        }

        $this->getDocumentManager()->getUnitOfWork()->registerAsManaged(
            $response,
            $response->getId(),
            $response->getVersion()
        );

        return $response;
    }
}
