<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\Tests\ActionBuilder\Subscription;

use BestIt\CommercetoolsODM\ActionBuilder\Subscription\ChangeKey;
use BestIt\CommercetoolsODM\ActionBuilder\Subscription\SubscriptionActionBuilder;
use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use BestIt\CommercetoolsODM\Tests\ActionBuilder\SupportTestTrait;
use Commercetools\Core\Model\Subscription\Subscription;
use Commercetools\Core\Request\Subscriptions\Command\SubscriptionSetKeyAction;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

/**
 * Test ChangeKey
 *
 * @author Michel Chowanski <michel.chowanski@bestit-online.de>
 * @package BestIt\CommercetoolsODM\Tests\ActionBuilder\Subscription
 */
class ChangeKeyTest extends TestCase
{
    use SupportTestTrait;

    /**
     * @var ChangeKey|PHPUnit_Framework_MockObject_MockObject The test class.
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
            ['key', Subscription::class, true],
            ['keys', Subscription::class],
        ];
    }

    /**
     * Sets up the test.
     *
     * @return void
     */
    public function setUp()
    {
        $this->fixture = new ChangeKey();
    }

    /**
     * Checks if the action is rendered correctly.
     *
     * @return void
     */
    public function testCreateUpdateActions()
    {
        $actions = $this->fixture->createUpdateActions(
            $key = uniqid(),
            $this->createMock(ClassMetadataInterface::class),
            [],
            [],
            new Subscription()
        );

        /** @var $action SubscriptionSetKeyAction */
        static::assertCount(1, $actions, 'Wrong action count.');
        static::assertInstanceOf(SubscriptionSetKeyAction::class, $action = $actions[0], 'Wrong instance.');
        static::assertSame($key, $action->getKey(), 'Wrong key.');
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
