<?php

namespace BestIt\CommercetoolsODM\Tests\Mapping\Annotations;

use ReflectionClass;

/**
 * Helps to check annotations.
 *
 * @author lange <lange@bestit-online.de>
 * @category Tests
 * @package BestIt\CommercetoolsODM\Tests\Mapping\Annotations
 * @subpackage Mapping\Annotations
 */
trait AnnotationTestTrait
{
    /**
     * Checks if the given class has the given annotation in the doc block.
     *
     * @param string $class
     * @param string $annotation
     *
     * @return void
     */
    public function assertClassHasAnnotation(string $class, string $annotation)
    {
        static::assertRegExp(
            sprintf('/\* *@%s/', preg_quote($annotation, '/')),
            (new ReflectionClass($class))->getDocComment()
        );
    }

    /**
     * Checks if the given method of the given class has the given annotation in the doc block.
     *
     * @param string $class
     * @param string $method
     * @param string $annotation
     *
     * @return void
     */
    public function assertMethodHasAnnotation(string $class, string $method, string $annotation)
    {
        static::assertRegExp(
            sprintf('/\* *@%s/', preg_quote($annotation, '/')),
            (new ReflectionClass($class))->getMethod($method)->getDocComment()
        );
    }

    /**
     * Checks if the given property of the given class has the given annotation in the doc block.
     *
     * @param string $class
     * @param string $property
     * @param string $annotation
     *
     * @return void
     */
    public function assertPropertyHasAnnotation(string $class, string $property, string $annotation)
    {
        static::assertRegExp(
            sprintf('/\* *@%s/', preg_quote($annotation, '/')),
            (new ReflectionClass($class))->getProperty($property)->getDocComment()
        );
    }
}
