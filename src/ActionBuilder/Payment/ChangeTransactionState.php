<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\ActionBuilder\Payment;

use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Commercetools\Core\Model\Payment\Payment;
use Commercetools\Core\Request\AbstractAction;
use Commercetools\Core\Request\Payments\Command\PaymentChangeTransactionStateAction;

/**
 * Change transaction state
 *
 * @package BestIt\CommercetoolsODM\ActionBuilder\Payment
 */
class ChangeTransactionState extends PaymentActionBuilder
{
    /**
     * A PCRE to match the hierarchical field path without delimiter.
     *
     * @var string
     */
    protected $complexFieldFilter = 'transactions/([^/]+)/state';

    /**
     * Creates the update actions for the given class and data.
     *
     * @param mixed $changedValue
     * @param ClassMetadataInterface $metadata
     * @param array $changedData
     * @param array $oldData
     * @param Payment $sourceObject
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

        $offset = $this->getLastFoundMatch()[1];
        $transaction = $sourceObject->getTransactions()->getAt($offset);

        // Exit if no transaction found or id is null (=== new)
        if (!$transaction || $transaction->getId() === null) {
            return $actions;
        }

        return [
            PaymentChangeTransactionStateAction::ofTransactionIdAndState($transaction->getId(), $changedValue)
        ];
    }
}
