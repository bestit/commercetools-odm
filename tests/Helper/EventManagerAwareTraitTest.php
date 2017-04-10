<?php

namespace BestIt\CommercetoolsODM\Tests\Helper;

use BestIt\CommercetoolsODM\Helper\EventManagerAwareTrait;
use Doctrine\Common\EventManager;
use PHPUnit\Framework\TestCase;

/**
 * Class EventManagerAwareTraitTest
 * @author blange <lange@bestit-online.de>
 * @category Tests
 * @package BestIt\CommercetoolsODM
 * @subpackage Helper
 * @version $id$
 */
class EventManagerAwareTraitTest extends TestCase
{
    /**
     * The tested class.
     * @var EventManagerAwareTrait
     */
    private $fixture = null;

    /**
     * Sets up the test.
     * @return void
     */
    protected function setUp()
    {
        $this->fixture = static::getMockForTrait(EventManagerAwareTrait::class);
    }

    /**
     * Checks the getter and setter.
     * @return void
     */
    public function testSetAndGetEventManager()
    {
        static::assertSame(
            $this->fixture,
            $this->fixture->setEventManager($mock = static::createMock(EventManager::class)),
            'Fluent interface failed.'
        );

        static::assertSame($mock, $this->fixture->getEventManager(), 'Object not persisted.');
    }
}
