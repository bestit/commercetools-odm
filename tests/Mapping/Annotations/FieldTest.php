<?php

namespace BestIt\CommercetoolsODM\Tests\Mapping\Annotations;

use BestIt\CommercetoolsODM\Mapping\Annotations\Field;
use PHPUnit\Framework\TestCase;

/**
 * Class FieldTest
 *
 * @author blange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\Tests\Mapping\Annotations
 * @subpackage Mapping\Annotations
 */
class FieldTest extends TestCase
{
    use AnnotationTestTrait;

    /**
     * The tested class.
     *
     * @var Field
     */
    protected $fixture = null;

    /**
     * Sets up the test.
     *
     * @return void
     */
    public function setUp()
    {
        $this->fixture = new Field();
    }

    /**
     * Checks the declaration for the annotation.
     *
     * @return void
     */
    public function testAnnotationDeclaration()
    {
        $this->assertClassHasAnnotation(Field::class, 'Annotation');
    }

    /**
     * Checks if the target declaration is as required.
     *
     * @return void
     */
    public function testTargetAnnotation()
    {
        $this->assertClassHasAnnotation(Field::class, 'Target({"PROPERTY","ANNOTATION"})');
    }

    /**
     * Checks the collection declaration and usage.
     *
     * @return void
     */
    public function testCollection()
    {
        $this->assertSame('', $this->fixture->collection, 'Collection property is wrong. ');
        $this->assertSame('', $this->fixture->getCollection(), 'Collection getter is wrong.');

        $this->fixture->collection = $mock = uniqid();
        $this->assertSame($mock, $this->fixture->getCollection(), 'Collection was not saved correctly.');
    }

    /**
     * Checks if ignoreOnEmpty is correctly checked.
     *
     * @return void
     */
    public function testIgnoreOnEmpty()
    {
        $this->assertFalse($this->fixture->ignoreOnEmpty, 'The property default is wrong.');
        $this->assertFalse($this->fixture->ignoreOnEmpty(), 'The method default is wrong.');

        $this->fixture->ignoreOnEmpty = true;

        $this->assertTrue($this->fixture->ignoreOnEmpty, 'The property was not saved.');
        $this->assertTrue($this->fixture->ignoreOnEmpty(), 'The method returns the wrong value.');
    }
    /**
     * Checks if read only is correctly checked.
     *
     * @return void
     */
    public function testReadOnly()
    {
        $this->assertFalse($this->fixture->readOnly, 'The property default is wrong.');
        $this->assertFalse($this->fixture->isReadOnly(), 'The method default is wrong.');

        $this->fixture->readOnly = true;

        $this->assertTrue($this->fixture->readOnly, 'The property was not saved.');
        $this->assertTrue($this->fixture->isReadOnly(), 'The method returns the wrong value.');
    }

    /**
     * Checks the type declaration and usage.
     *
     * @return void
     */
    public function testType()
    {
        $this->assertPropertyHasAnnotation(
            Field::class,
            'type',
            'Enum({"array","boolean","dateTime","int","resource","string","set"})',
            'Enum is missing.'
        );
        $this->assertPropertyHasAnnotation(
            Field::class,
            'type',
            'Required',
            'Required is missing.'
        );

        $this->assertSame('string', $this->fixture->type, 'Type property is wrong. ');
        $this->assertSame('string', $this->fixture->getType(), 'Type getter is wrong.');

        $this->fixture->type = $mock = uniqid();
        $this->assertSame($mock, $this->fixture->getType(), 'Type was not saved correctly.');
    }
}
