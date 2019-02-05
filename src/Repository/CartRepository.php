<?php

namespace BestIt\CommercetoolsODM\Repository;

use BestIt\CommercetoolsODM\DocumentManagerInterface;
use BestIt\CommercetoolsODM\Exception\APIException;
use BestIt\CommercetoolsODM\Model\DefaultRepository;
use Commercetools\Core\Model\Cart\Cart;
use Commercetools\Core\Request\Carts\CartUpdateRequest;
use Commercetools\Core\Request\Carts\Command\CartRecalculateAction;
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
            throw new RuntimeException('Invalid response.');
        }

        $this->getDocumentManager()->getUnitOfWork()->registerAsManaged(
            $response,
            $response->getId(),
            $response->getVersion()
        );

        return $response;
    }
}
