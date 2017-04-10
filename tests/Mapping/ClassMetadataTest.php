<?php

namespace BestIt\CommercetoolsODM\Tests\Mapping;

use BestIt\CommercetoolsODM\Mapping\ClassMetadata;
use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Commercetools\Core\Model\Order\Order;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * Class ClassMetadataTest
 * @author blange <lange@bestit-online.de>
 * @category Tests
 * @package BestIt\CommercetoolsODM
 * @subpackage Mapping
 * @version $id$
 */
class ClassMetadataTest extends TestCase
{
    /**
     * The tested class.
     * @var ClassMetadata
     */
    private $fixture = null;

    /**
     * The used object name.
     * @var string
     */
    private $objectName = '';

    /**
     * Sets up the test.
     * @reteurn void
     */
    protected function setUp()
    {
        $this->fixture = new ClassMetadata($this->objectName = Order::class);
    }

    /**
     * Checks the field name getter for the standard model.
     * @return void
     */
    public function testGetFieldNamesStandard()
    {
        $this->fixture = new ClassMetadata($this->objectName = Order::class);

        static::assertSame(
            array_keys((new $this->objectName)->fieldDefinitions()),
            $this->fixture->getFieldNames()
        );
    }

    /**
     * Checks the name getter.
     * @return void
     */
    public function testGetName()
    {
        static::assertSame($this->objectName, $this->fixture->getName());
    }

    /**
     * Checks the getter and setter.
     * @return void
     */
    public function testGetAndSetReflectionClass()
    {
        static::assertInstanceOf(ReflectionClass::class, $this->fixture->getReflectionClass());

        static::assertSame(
            $this->fixture,
            $this->fixture->setReflectionClass($mock = new ReflectionClass(Order::class)),
            'Fluent interface broken.'
        );

        static::assertSame($mock, $this->fixture->getReflectionClass(), 'Object not saved.');
    }

    /**
     * Checks the used interface.
     * @return void
     */
    public function testInterface()
    {
        static::assertInstanceOf(ClassMetadataInterface::class, $this->fixture);
    }
}
