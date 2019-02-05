<?php

namespace BestIt\CommercetoolsODM\ActionBuilder\Order;

use BestIt\CommercetoolsODM\ActionBuilder\ActionBuilderAbstract;
use Commercetools\Core\Model\Order\Order;

/**
 * Basic action builder for orders.
 *
 * @author blange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\ActionBuilder\Order
 * @subpackage ActionBuilder\Order
 */
abstract class OrderActionBuilder extends ActionBuilderAbstract
{
    /**
     * For which class is this description used?
     *
     * @var string
     */
    protected $modelClass = Order::class;
}
