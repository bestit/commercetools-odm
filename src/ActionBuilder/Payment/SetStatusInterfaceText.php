<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\ActionBuilder\Payment;

use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Commercetools\Core\Model\Order\Order;
use Commercetools\Core\Request\AbstractAction;
use Commercetools\Core\Request\Payments\Command\PaymentSetStatusInterfaceTextAction;

/**
 * @author André Varelmann <andre.varelmann@bestit-online.de>
 * @package BestIt\CommercetoolsODM\ActionBuilder\Payment
 */
class SetStatusInterfaceText extends PaymentActionBuilder
{
    /**
     * A PCRE to match the hierarchical field path without delimiter.
     *
     * @var string
     */
    protected $fieldName = 'paymentStatus/interfaceText';

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
        return [(new PaymentSetStatusInterfaceTextAction())->setInterfaceText($changedValue)];
    }
}
