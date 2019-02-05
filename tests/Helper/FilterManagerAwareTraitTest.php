<?php

namespace BestIt\CommercetoolsODM\Tests\Helper;

use BestIt\CommercetoolsODM\Filter\FilterManagerInterface;
use BestIt\CommercetoolsODM\Helper\FilterManagerAwareTrait;
use PHPUnit\Framework\TestCase;

/**
 * Class FilterManagerAwareTraitTest
 *
 * @author Michel Chowanski <chowanski@bestit-online.de>
 * @package BestIt\CommercetoolsODM\Tests\Helper
 */
class FilterManagerAwareTraitTest extends TestCase
{
    /**
     * The tested class
     *
     * @var FilterManagerAwareTrait
     */
    private $fixture = null;

    /**
     * Sets up the test.
     *
     * @return void
     */
    protected function setUp()
    {
        $this->fixture = $this->getMockForTrait(FilterManagerAwareTrait::class);
    }

    /**
     * Checks the getter and setter
     *
     * @return void
     */
    public function testGetAndSetRepository()
    {
        $this->assertSame(
            $this->fixture,
            $this->fixture->setFilterManager($mock = $this->createMock(FilterManagerInterface::class)),
            'Fluent interface broken.'
        );

        $this->assertSame($mock, $this->fixture->getFilterManager(), 'Not saved.');
    }
}
