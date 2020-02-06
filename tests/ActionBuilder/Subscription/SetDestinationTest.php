<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\Tests\ActionBuilder\Subscription;

use BestIt\CommercetoolsODM\ActionBuilder\Subscription\SetDestination;
use BestIt\CommercetoolsODM\ActionBuilder\Subscription\SubscriptionActionBuilder;
use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use BestIt\CommercetoolsODM\Tests\ActionBuilder\SupportTestTrait;
use Commercetools\Core\Model\Subscription\Destination;
use Commercetools\Core\Model\Subscription\Subscription;
use Commercetools\Core\Request\Subscriptions\Command\SubscriptionChangeDestinationAction;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

/**
 * Test SetDestination
 *
 * @author Michel Chowanski <michel.chowanski@bestit-online.de>
 * @package BestIt\CommercetoolsODM\Tests\ActionBuilder\Subscription
 */
class SetDestinationTest extends TestCase
{
    use SupportTestTrait;

    /**
     * @var SetDestination|PHPUnit_Framework_MockObject_MockObject The test class.
     */
    protected $fixture;


    /**
     * Returns an array with the assertions for the support method.
     *
     * The First Element is the field path, the second element is the reference class and the optional third value
     * indicates the return value of the support method.
     *
     * @return array
     */
    public function getSupportAssertions(): array
    {
        return [
            ['destination', Subscription::class, true],
            ['destinations', Subscription::class],
        ];
    }

    /**
     * Sets up the test.
     *
     * @return void
     */
    public function setUp()
    {
        $this->fixture = new SetDestination();
    }

    /**
     * Checks if the action is rendered correctly.
     *
     * @return void
     */
    public function testCreateUpdateActions()
    {
        $actions = $this->fixture->createUpdateActions(
            $destination = [
                'type' => $type = Destination::DESTINATION_SQS,
                'accessKey' => $accessKey = uniqid(),
                'accessSecret' => $accessSecret = uniqid(),
                'queueUrl' => $queueUrl = uniqid(),
                'region' => $region = uniqid()
            ],
            $this->createMock(ClassMetadataInterface::class),
            [],
            [],
            new Subscription()
        );

        /** @var $action SubscriptionChangeDestinationAction */
        static::assertCount(1, $actions, 'Wrong action count.');
        static::assertInstanceOf(SubscriptionChangeDestinationAction::class, $action = $actions[0], 'Wrong instance.');

        $result = $action->getDestination()->toArray();
        static::assertSame($type, $result['type']);
        static::assertSame($accessKey, $result['accessKey']);
        static::assertSame($accessSecret, $result['accessSecret']);
        static::assertSame($queueUrl, $result['queueUrl']);
        static::assertSame($region, $result['region']);
    }

    /**
     * Checks the instance of the builder.
     *
     * @return void
     */
    public function testInstance()
    {
        static::assertInstanceOf(SubscriptionActionBuilder::class, $this->fixture);
    }
}
