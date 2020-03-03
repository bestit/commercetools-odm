<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\ActionBuilder\Subscription;

use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Commercetools\Core\Model\Subscription\ChangeSubscriptionCollection;
use Commercetools\Core\Request\Subscriptions\Command\SubscriptionSetChangesAction;
use Commercetools\Core\Request\Subscriptions\Command\SubscriptionSetKeyAction;

/**
 * Reacts on the change of the changes field and creates the update action.
 *
 * @author Michel Chowanski <michel.chowanski@bestit-online.de>
 * @package BestIt\CommercetoolsODM\ActionBuilder\Subscription
 */
class SetChanges extends SubscriptionActionBuilder
{
    /**
     * @var string Working on the changes field.
     */
    protected $fieldName = 'changes';

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
        if (!is_array($changedValue) || count($changedValue) === 0) {
            return [];
        }

        $changes = [];
        $action = new SubscriptionSetChangesAction();
        foreach ($changedValue as $key => $value) {
            $changes[] = [
                'resourceTypeId' => $value['resourceTypeId'],
            ];
        }
        
        $action->setChanges(new ChangeSubscriptionCollection($changes));

        return [$action];
    }
}
