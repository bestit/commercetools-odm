<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\Tests\ActionBuilder\Subscription;

use BestIt\CommercetoolsODM\ActionBuilder\Subscription\SetMessages;
use BestIt\CommercetoolsODM\ActionBuilder\Subscription\SubscriptionActionBuilder;
use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use BestIt\CommercetoolsODM\Tests\ActionBuilder\SupportTestTrait;
use Commercetools\Core\Model\Subscription\Subscription;
use Commercetools\Core\Request\Subscriptions\Command\SubscriptionSetMessagesAction;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

/**
 * Test SetMessages
 *
 * @author Michel Chowanski <michel.chowanski@bestit-online.de>
 * @package BestIt\CommercetoolsODM\Tests\ActionBuilder\Subscription
 */
class SetMessagesTest extends TestCase
{
    use SupportTestTrait;

    /**
     * @var SetMessages|PHPUnit_Framework_MockObject_MockObject The test class.
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
            ['messages', Subscription::class, true],
            ['messagess', Subscription::class],
        ];
    }

    /**
     * Sets up the test.
     *
     * @return void
     */
    public function setUp()
    {
        $this->fixture = new SetMessages();
    }

    /**
     * Checks if the action is rendered correctly.
     *
     * @return void
     */
    public function testCreateUpdateActions()
    {
        $actions = $this->fixture->createUpdateActions(
            $messages = [
                [
                    'resourceTypeId' => $productResource = 'product',
                    'types' => $productTypes = ['ProductCreated', 'ProductDeleted']
                ],
                [
                    'resourceTypeId' => $cartResource = 'cart',
                    'types' => $cartTypes = []
                ]
            ],
            $this->createMock(ClassMetadataInterface::class),
            [],
            [],
            new Subscription()
        );

        /** @var $action SubscriptionSetMessagesAction */
        static::assertCount(1, $actions, 'Wrong action count.');
        static::assertInstanceOf(SubscriptionSetMessagesAction::class, $action = $actions[0], 'Wrong instance.');

        $result = $action->getMessages()->toArray();
        static::assertSame($productResource, $result[0]['resourceTypeId']);
        static::assertSame($productTypes, $result[0]['types']);
        static::assertSame($cartResource, $result[1]['resourceTypeId']);
        static::assertSame($cartTypes, $result[1]['types']);
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
