<?php

namespace BestIt\CommercetoolsODM\Tests\Mapping\Annotations;

use BestIt\CommercetoolsODM\Mapping\Annotations\Field;
use PHPUnit\Framework\TestCase;

/**
 * Class FieldTest
 * @author blange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM
 * @subpackage Mapping\Annotations
 * @version $id$
 */
class FieldTest extends TestCase
{
    use AnnotationTestTrait;

    /**
     * The tested class.
     * @var Field
     */
    protected $fixture = null;

    /**
     * Sets up the test.
     * @return void
     */
    public function setUp()
    {
        $this->fixture = new Field();
    }

    /**
     * Checks the declaration for the annotation.
     * @return void
     */
    public function testAnnotationDeclaration()
    {
        static::assertClassHasAnnotation(Field::class, 'Annotation');
    }

    /**
     * Checks if the target declaration is as required.
     * @return void
     */
    public function testTargetAnnotation()
    {
        static::assertClassHasAnnotation(Field::class, 'Target({"PROPERTY","ANNOTATION"})');
    }

    /**
     * Checks the collection declaration and usage.
     * @return void
     */
    public function testCollection()
    {
        static::assertSame('', $this->fixture->collection, 'Collection property is wrong. ');
        static::assertSame('', $this->fixture->getCollection(), 'Collection getter is wrong.');

        $this->fixture->collection = $mock = uniqid();
        static::assertSame($mock, $this->fixture->getCollection(), 'Collection was not saved correctly.');
    }

    /**
     * Checks the type declaration and usage.
     * @return void
     */
    public function testType()
    {
        static::assertPropertyHasAnnotation(
            Field::class,
            'type',
            'Enum({"array","boolean","dateTime","int","string","set"})',
            'Enum is missing.'
        );
        static::assertPropertyHasAnnotation(
            Field::class,
            'type',
            'Required',
            'Required is missing.'
        );

        static::assertSame('string', $this->fixture->type, 'Type property is wrong. ');
        static::assertSame('string', $this->fixture->getType(), 'Type getter is wrong.');

        $this->fixture->type = $mock = uniqid();
        static::assertSame($mock, $this->fixture->getType(), 'Type was not saved correctly.');
    }
}
