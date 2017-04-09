<?php

namespace BestIt\CommercetoolsODM\Repository;

use Commercetools\Core\Model\Cart\Cart;
use Commercetools\Core\Model\Order\Order;

/**
 * Repository for orders.
 * @author blange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM
 * @subpackage Repository
 * @version $id$
 */
interface OrderRepositoryInterface extends ObjectRepository
{
    /**
     * Creates an order frmo a cart.
     * @param Cart $cart
     * @return Order
     */
    public function createFromCart(Cart $cart): Order;
}
