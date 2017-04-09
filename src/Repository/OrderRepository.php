<?php

namespace BestIt\CommercetoolsODM\Repository;

use BestIt\CommercetoolsODM\Model\DefaultRepository;
use Commercetools\Core\Model\Cart\Cart;
use Commercetools\Core\Model\Order\Order;
use Commercetools\Core\Request\Orders\OrderCreateFromCartRequest;

/**
 * Repository for orders.
 * @author blange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM
 * @subpackage Repository
 * @version $id$
 */
class OrderRepository extends DefaultRepository implements OrderRepositoryInterface
{
    /**
     * Creates an order frmo a cart.
     * @param Cart $cart
     * @return Order
     */
    public function createFromCart(Cart $cart): Order
    {
        $documentManager = $this->getDocumentManager();

        $request = $documentManager->createRequest(
            $this->getClassName(),
            OrderCreateFromCartRequest::class,
            $cart->getId(),
            $cart->getVersion()
        );

        /** @var Order $order */
        list($order) = $this->processQuery($request);

        $documentManager->getUnitOfWork()->registerAsManaged($order, $order->getId(), $order->getVersion());

        return $order;
    }
}
