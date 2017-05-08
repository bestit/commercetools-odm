<?php

namespace BestIt\CommercetoolsODM\Tests\Mapping;

use BestIt\CommercetoolsODM\Mapping\Annotations\Field;
use BestIt\CommercetoolsODM\Mapping\ClassMetadata;
use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use BestIt\CommercetoolsODM\Tests\Mapping\ClassMetadata\TestClass;
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
        $this->fixture = new ClassMetadata($this->objectName = TestClass::class);
    }

    /**
     * Checks the getter and setter.
     * @return void
     */
    public function testGetAndSetFieldMappings()
    {
        $this->assertSame([], $this->fixture->getFieldMappings(), 'Wrong default return.');

        $this->assertSame(
            $this->fixture,
            $this->fixture->setFieldMappings($fields = [uniqid() => uniqid()]),
            'Fluent interface broken.'
        );

        $this->assertSame(
            $fields,
            $this->fixture->getFieldMappings(),
            'Wrong persistent.'
        );
    }

    /**
     * Checks the getter and setter.
     * @return void
     */
    public function testGetAndSetReflectionClass()
    {
        $this->assertInstanceOf(ReflectionClass::class, $this->fixture->getReflectionClass());

        $this->assertSame(
            $this->fixture,
            $this->fixture->setReflectionClass($mock = new ReflectionClass(Order::class)),
            'Fluent interface broken.'
        );

        $this->assertSame($mock, $this->fixture->getReflectionClass(), 'Object not saved.');
    }

    /**
     * Checks the field name getter for the standard model.
     * @return void
     */
    public function testGetFieldNamesStandard()
    {
        $this->fixture = new ClassMetadata($this->objectName = Order::class);

        $this->assertSame(
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
        $this->assertSame($this->objectName, $this->fixture->getName());
    }

    /**
     * Checks if the ignore marker is used correctly for the given field.
     */
    public function testIgnoreFieldOnEmptyFalse()
    {
        $this->fixture->setFieldMappings([
            ($fieldName = uniqid()) => $fieldObject = new Field()
        ]);

        $this->assertFalse($this->fixture->ignoreFieldOnEmpty($fieldName));
    }

    /**
     * Checks if the ignore marker falls back correctly on a missing field.
     */
    public function testIgnoreFieldOnEmptyMissing()
    {
        $this->fixture->setFieldMappings([
            ($fieldName = uniqid()) => $fieldObject = new Field()
        ]);

        $this->assertFalse($this->fixture->ignoreFieldOnEmpty($fieldName . uniqid()));
    }

    /**
     * Checks if the ignore marker is used correctly for the given field.
     */
    public function testIgnoreFieldOnEmptyTrue()
    {
        $this->fixture->setFieldMappings([
            ($fieldName = uniqid()) => $fieldObject = new Field()
        ]);

        $fieldObject->ignoreOnEmpty = true;

        $this->assertTrue($this->fixture->ignoreFieldOnEmpty($fieldName));
    }

    /**
     * Checks the used interface.
     * @return void
     */
    public function testInterface()
    {
        $this->assertInstanceOf(ClassMetadataInterface::class, $this->fixture);
    }
}
