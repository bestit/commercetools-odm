<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\ActionBuilder\Subscription;

use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Commercetools\Core\Model\Subscription\MessageSubscriptionCollection;
use Commercetools\Core\Request\Subscriptions\Command\SubscriptionSetKeyAction;
use Commercetools\Core\Request\Subscriptions\Command\SubscriptionSetMessagesAction;

/**
 * Reacts on the change of the messages field and creates the update action.
 *
 * @author Michel Chowanski <michel.chowanski@bestit-online.de>
 * @package BestIt\CommercetoolsODM\ActionBuilder\Subscription
 */
class SetMessages extends SubscriptionActionBuilder
{
    /**
     * @var string Working on the messages field.
     */
    protected $fieldName = 'messages';

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

        $messages = [];
        $action = new SubscriptionSetMessagesAction();
        foreach ($changedValue as $key => $value) {
            $resourceId = $value['resourceTypeId'] ?? $oldData['messages'][$key]['resourceTypeId'];
            $types = array_filter($value['types'] ?? $oldData['messages'][$key]['types']);
            
            $messages[] = [
                'resourceTypeId' => $resourceId,
                'types' => array_filter($types),
            ];
        }
        
        $action->setMessages(new MessageSubscriptionCollection($messages));

        return [$action];
    }
}
