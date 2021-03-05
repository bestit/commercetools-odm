<?php

namespace BestIt\CommercetoolsODM\ActionBuilder\Payment;

use BestIt\CommercetoolsODM\ActionBuilder\ActionBuilderAbstract;
use Commercetools\Core\Model\Order\Order;
use Commercetools\Core\Model\Payment\Payment;

/**
 * Basic action builder for payments.
 *
 * @author André Varelmann <andre.varelmann@bestit-online.de>
 * @package BestIt\CommercetoolsODM\ActionBuilder\Payment
 * @subpackage ActionBuilder\Payment
 */
abstract class PaymentActionBuilder extends ActionBuilderAbstract
{
    /**
     * For which class is this description used?
     *
     * @var string
     */
    protected $modelClass = Payment::class;
}
