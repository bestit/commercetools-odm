<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\Tests\Repository;

use BestIt\CommercetoolsODM\Repository\ProjectRepository;
use BestIt\CommercetoolsODM\Repository\ProjectRepositoryInterface;
use PHPUnit\Framework\TestCase;
use function uniqid;

/**
 * Class ProjectRepositoryTest
 *
 * @package BestIt\CommercetoolsODM\Tests\Repository
 */
class ProjectRepositoryTest extends TestCase
{
    use TestRepositoryTrait {
        TestRepositoryTrait::testInterfaces as testInterfacesInTrait;
    }

    /**
     * The tested class.
     *
     * @var ProjectRepository|null
     */
    protected $fixture = null;

    /**
     * Returns the class name for the repository.
     *
     * @return string
     */
    protected function getRepositoryClass(): string
    {
        return ProjectRepository::class;
    }

    /**
     * Checks if the expand value can be changed without an exception.
     *
     * This update was missing in the commit bc6a665267566e86f0ee81562eed6e417a8b6d18.
     *
     * @return void
     */
    public function testGetAndSetExpandsWithoutException()
    {
        static::assertSame([], $this->fixture->getExpands(), 'The default return should be empty.');

        $this->fixture->setExpands($expands = [uniqid()]);

        static::assertSame(
            $expands,
            $this->fixture->getExpands(),
            'The return should contain the assigned array.'
        );
    }

    /**
     * Checks the class interfaces.
     *
     * @return void
     */
    public function testInterfaces()
    {
        static::assertInstanceOf(ProjectRepositoryInterface::class, $this->fixture);
    }
}
