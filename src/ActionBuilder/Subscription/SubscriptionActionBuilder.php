<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\ActionBuilder\Subscription;

use BestIt\CommercetoolsODM\ActionBuilder\ActionBuilderAbstract;
use Commercetools\Core\Model\Subscription\Subscription;

/**
 * The basic action builder for the subscriptions.
 *
 * @author Michel Chowanski <michel.chowanski@bestit-online.de>
 * @package BestIt\CommercetoolsODM\ActionBuilder\Subscription
 */
abstract class SubscriptionActionBuilder extends ActionBuilderAbstract
{
    /**
     * For which class is this description used?
     *
     * @var string
     */
    protected $modelClass = Subscription::class;
}
