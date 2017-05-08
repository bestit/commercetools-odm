<?php

namespace BestIt\CommercetoolsODM\Tests;

use BestIt\CommercetoolsODM\Repository\ObjectRepository;
use BestIt\CommercetoolsODM\RepositoryAwareTrait;
use PHPUnit\Framework\TestCase;

/**
 * Class RepositoryAwareTraitTest
 * @author blange <lange@bestit-online.de>
 * @category Tests
 * @package BestIt\CommercetoolsODM
 * @version $id$
 */
class RepositoryAwareTraitTest extends TestCase
{
    /**
     * The tested class.
     * @var RepositoryAwareTrait
     */
    private $fixture = null;

    /**
     * Sets up the test.
     */
    protected function setUp()
    {
        $this->fixture = $this->getMockForTrait(RepositoryAwareTrait::class);
    }

    /**
     * Checks the getter and setter.
     */
    public function testGetAndSetRepository()
    {
        $this->assertSame(
            $this->fixture,
            $this->fixture->setRepository($mock = $this->createMock(ObjectRepository::class)),
            'Fluent interface broken.'
        );

        $this->assertSame($mock, $this->fixture->getRepository(), 'Not saved.');
    }
}
