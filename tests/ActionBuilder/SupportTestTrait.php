<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\Tests\ActionBuilder;

use BestIt\CommercetoolsODM\ActionBuilder\ActionBuilderAbstract;
use PHPUnit_Framework_MockObject_MockObject;

/**
 * Checks the support method of the action builder.
 *
 * @author lange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\Tests\ActionBuilder
 */
trait SupportTestTrait
{
    /**
     * The test class.
     *
     * @var ActionBuilderAbstract|PHPUnit_Framework_MockObject_MockObject|null
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
    abstract public function getSupportAssertions(): array;

    /**
     * Checks if the support method matches the correct values.
     *
     * @dataProvider getSupportAssertions
     *
     * @param string $fieldPath
     * @param string $referenceClass
     * @param bool $returnValue
     *
     * @return void
     */
    public function testSupportsMatch(string $fieldPath, string $referenceClass, bool $returnValue = false)
    {
        static::assertSame(
            $returnValue,
            $this->fixture->supports($fieldPath, $referenceClass),
            sprintf('%s for %s was not correctly supported.', $fieldPath, $referenceClass)
        );
    }
}
