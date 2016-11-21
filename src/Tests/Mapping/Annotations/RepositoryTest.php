<?php

namespace BestIt\CommercetoolsODM\Tests\Mapping\Annotations;

use BestIt\CommercetoolsODM\Mapping\Annotations\Repository;
use PHPUnit\Framework\TestCase;

/**
 * Class RepositoryTest
 * @author lange <lange@bestit-online.de>
 * @category Tests
 * @package BestIt\CommercetoolsODM
 * @subpackage Mapping\Annotations
 * @version $id$
 */
class RepositoryTest extends TestCase
{
    use AnnotationTestTrait;

    /**
     * The tested class.
     * @var Repository
     */
    protected $fixture = null;

    /**
     * Sets up the test.
     * @return void
     */
    public function setUp()
    {
        $this->fixture = new Repository();
    }

    /**
     * Checks the property of the annotation.
     * @return void
     */
    public function testClass()
    {
        static::assertSame('', $this->fixture->value);
        static::assertSame('', $this->fixture->getClass());

        static::assertPropertyHasAnnotation(Repository::class, 'value', 'Required');

        $this->fixture->value = $mock = uniqid();
        static::assertSame($mock, $this->fixture->getClass());
    }

    /**
     * Checks the declaration for the annotation.
     * @return void
     */
    public function testAnnotationDeclaration()
    {
        static::assertClassHasAnnotation(Repository::class, 'Annotation');
    }

    /**
     * Checks if the target declaration is as required.
     * @return void
     */
    public function testTargetAnnotation()
    {
        static::assertClassHasAnnotation(Repository::class, 'Target("CLASS")');
    }
}
