<?php

namespace BestIt\CommercetoolsODM\Tests\Helper;

use BestIt\CommercetoolsODM\Event\ListenersInvoker;
use BestIt\CommercetoolsODM\Helper\ListenerInvokerAwareTrait;
use PHPUnit\Framework\TestCase;

/**
 * Class ListenerInvokerAwareTraitTest
 * @author blange <lange@bestit-online.de>
 * @category Tests
 * @package BestIt\CommercetoolsODM
 * @subpackage Helper
 * @version $id$
 */
class ListenerInvokerAwareTraitTest extends TestCase
{
    /**
     * The tested class.
     * @var ListenerInvokerAwareTrait
     */
    private $fixture = null;

    /**
     * Sets up the test.
     * @return void
     */
    protected function setUp()
    {
        $this->fixture = static::getMockForTrait(ListenerInvokerAwareTrait::class);
    }

    /**
     * Checks the getter and setter.
     * @return void
     */
    public function testSetAndGetListenerInvoker()
    {
        static::assertSame(
            $this->fixture,
            $this->fixture->setListenerInvoker($mock = static::createMock(ListenersInvoker::class)),
            'Fluent interface failed.'
        );

        static::assertSame($mock, $this->fixture->getListenerInvoker(), 'Object not persisted.');
    }
}
