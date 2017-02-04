<?php

namespace BestIt\CommercetoolsODM\Tests\ActionBuilder;

use BestIt\CommercetoolsODM\ActionBuilder\ActionBuilderAbstract;
use BestIt\CommercetoolsODM\ActionBuilder\ActionBuilderInterface;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

/**
 * Tests ActionBuilderAbstract.
 * @author lange <lange@bestit-online.de>
 * @category Tests
 * @package BestIt\CommercetoolsODM
 * @subpackage ActionBuilder
 * @version $id$
 */
class ActionBuilderAbstractTest extends TestCase
{
    /**
     * The test class.
     * @var ActionBuilderAbstract|PHPUnit_Framework_MockObject_MockObject
     */
    private $fixture = null;

    /**
     * Sets up the test.
     * @return void
     */
    public function setUp()
    {
        $this->fixture = static::getMockForAbstractClass(ActionBuilderAbstract::class);
    }

    /**
     * Checks the priority getter.
     * @covers ActionBuilderAbstract::getPriority()
     * @return void
     */
    public function testGetPriorityDefault()
    {
        static::assertSame(0, $this->fixture->getPriority());
    }

    /**
     * Checks the instance of the abstract class.
     * @return void
     */
    public function testInstance()
    {
        static::assertInstanceOf(ActionBuilderInterface::class, $this->fixture);
    }

    /**
     * Checks the handling of the stackable parameter.
     * @covers ActionBuilderAbstract::isStackable()
     * @return void
     */
    public function testIsStackable()
    {
        static::assertTrue($this->fixture->isStackable(), 'Default return was wrong.');
        static::assertTrue($this->fixture->isStackable(false), 'True should be returned on the false-set.');
        static::assertFalse($this->fixture->isStackable(), 'The value should be false now.');
        static::assertFalse($this->fixture->isStackable(true), 'The old value should be returned.');
        static::assertTrue($this->fixture->isStackable(), 'True should be returned.');
    }

    /**
     * Checks if the correct default value is returned.
     * @return void
     */
    public function testSupportDefault()
    {
        static::assertFalse($this->fixture->supports(uniqid(), uniqid()));
    }
}
