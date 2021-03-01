<?php

namespace BestIt\CommercetoolsODM\ActionBuilder\Order;

use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Commercetools\Core\Model\Order\Order;
use Commercetools\Core\Model\Payment\PaymentReference;
use Commercetools\Core\Request\AbstractAction;
use Commercetools\Core\Request\Orders\Command\OrderAddPaymentAction;

/**
 * Builds the action to add a payment info to an order.
 *
 * @author info@bestit.de <info@bestit.de>
 * @package BestIt\CommercetoolsODM\ActionBuilder\Order
 */
class AddPayment extends OrderActionBuilder
{
    /**
     * A PCRE to match the hierarchical field path without delimiter.
     *
     * @var string
     */
    protected $complexFieldFilter = 'paymentInfo/payments/([\d]*)$';

    /**
     * Creates the update actions for the given class and data.
     *
     * @param mixed $changedValue
     * @param ClassMetadataInterface $metadata
     * @param array $changedData
     * @param array $oldData
     * @param Order $sourceObject
     *
     * @return AbstractAction[]
     */
    public function createUpdateActions(
        $changedValue,
        ClassMetadataInterface $metadata,
        array $changedData,
        array $oldData,
        $sourceObject
    ): array {

        $actions = [];
        foreach ($changedData as $payment) {
            if (isset($payment['key'])) {
                $actions[] = (new OrderAddPaymentAction())->setPayment(PaymentReference::ofTypeAndKey($payment['typeId'], $payment['key']));
            }
            if (isset($payment['id'])) {
                $actions[] = (new OrderAddPaymentAction())->setPayment(PaymentReference::ofTypeAndId($payment['typeId'], $payment['id']));
            }
        }

        return $actions;
    }
}
