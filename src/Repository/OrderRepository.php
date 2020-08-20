<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\Repository;

use BestIt\CommercetoolsODM\Exception\APIException;
use BestIt\CommercetoolsODM\Exception\ResponseException;
use BestIt\CommercetoolsODM\Model\DefaultRepository;
use Commercetools\Core\Model\Cart\Cart;
use Commercetools\Core\Model\Order\ImportOrder;
use Commercetools\Core\Model\Order\Order;
use Commercetools\Core\Request\Orders\OrderCreateFromCartRequest;
use Commercetools\Core\Request\Orders\OrderImportRequest;
use Commercetools\Core\Response\ErrorResponse;

/**
 * Repository for orders.
 *
 * @author blange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\Repository
 */
class OrderRepository extends DefaultRepository implements OrderRepositoryInterface
{
    /**
     * Creates an order from a cart.
     *
     * @throws ResponseException
     *
     * @param Cart $cart
     * @param string|null $orderNumber
     *
     * @return Order
     */
    public function createFromCart(Cart $cart, string $orderNumber = null): Order
    {
        $documentManager = $this->getDocumentManager();

        $request = $documentManager->createRequest(
            $this->getClassName(),
            OrderCreateFromCartRequest::class,
            $cart->getId(),
            $cart->getVersion()
        );

        if ($orderNumber !== null) {
            /** @var OrderCreateFromCartRequest $request */
            $request->setOrderNumber($orderNumber);
        }

        /** @var Order $order */
        list($order, $response) = $this->processQuery($request);

        if ($response instanceof ErrorResponse) {
            throw APIException::fromResponse($response);
        }

        $documentManager->getUnitOfWork()->registerAsManaged($order, $order->getId(), $order->getVersion());

        return $order;
    }

    /**
     * Removes the given order.
     *
     * @param Order $order The order.
     * @param bool $direct Should the deletion be deleted directly with a doc manager flush?
     *
     * @return void
     */
    public function deleteOrder(Order $order, bool $direct = true)
    {
        $documentManager = $this->getDocumentManager();
        $documentManager->remove($order);

        if ($direct) {
            $documentManager->flush();
        }
    }

    /**
     * Imports the given order.
     *
     * @param Order $importableOrder
     * @throws ResponseException
     *
     * @return Order
     */
    public function import(Order $importableOrder): Order
    {
        $documentManager = $this->getDocumentManager();

        $request = $documentManager->createRequest(
            $this->getClassName(),
            OrderImportRequest::class,
            ImportOrder::fromArray($importableOrder->toArray())
        );

        /** @var Order $importedOrder */
        list($importedOrder, $response) = $this->processQuery($request);

        if ($response instanceof ErrorResponse) {
            throw APIException::fromResponse($response);
        }

        $documentManager->getUnitOfWork()->registerAsManaged(
            $importedOrder,
            $importedOrder->getId(),
            $importedOrder->getVersion()
        );

        return $importedOrder;
    }
}
