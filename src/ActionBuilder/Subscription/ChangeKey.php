<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\ActionBuilder\Subscription;

use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Commercetools\Core\Request\Subscriptions\Command\SubscriptionSetKeyAction;

/**
 * Reacts on the change of the key field and creates the update action.
 *
 * @author Michel Chowanski <michel.chowanski@bestit-online.de>
 * @package BestIt\CommercetoolsODM\ActionBuilder\Subscription
 */
class ChangeKey extends SubscriptionActionBuilder
{
    /**
     * @var string Working on the key field.
     */
    protected $fieldName = 'key';

    /**
     * Creates the update actions for the given class and data.
     *
     * @param mixed $changedValue
     * @param ClassMetadataInterface $metadata
     * @param array $changedData
     * @param array $oldData
     * @param mixed $sourceObject
     *
     * @return SubscriptionSetKeyAction[]
     */
    public function createUpdateActions(
        $changedValue,
        ClassMetadataInterface $metadata,
        array $changedData,
        array $oldData,
        $sourceObject
    ): array {
        $action = new SubscriptionSetKeyAction();
        
        if ($changedValue) {
            $action->setKey($changedValue);
        }

        return [$action];
    }
}
