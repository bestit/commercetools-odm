<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\Tests;

use BestIt\CTAsyncPool\PoolInterface;
use BestIt\CommercetoolsODM\DocumentManagerInterface;
use BestIt\CommercetoolsODM\Filter\FilterManager;
use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use BestIt\CommercetoolsODM\Model\DefaultRepository;
use BestIt\CommercetoolsODM\Repository\ObjectRepository;
use BestIt\CommercetoolsODM\RepositoryFactory;
use PHPUnit\Framework\TestCase;
use function uniqid;

/**
 * Tests the factory for the repositories.
 *
 * @author blange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\Tests
 */
class RepositoryFactoryTest extends TestCase
{
    /**
     * @var RepositoryFactory The tested class.
     */
    private $fixture;

    /**
     * Sets up the test.
     *
     * @return void
     */
    protected function setUp()
    {
        $this->fixture = new RepositoryFactory(
            $this->createMock(FilterManager::class),
            $this->createMock(PoolInterface::class)
        );
    }

    /**
     * Checks if the default repository is rendered as a singleton.
     *
     * @return void
     */
    public function testGetRepositoryDefaultAsSingleton()
    {
        $documentManager = $this->createMock(DocumentManagerInterface::class);

        $documentManager
            ->expects(static::once())
            ->method('getClassMetadata')
            ->with($className = uniqid())
            ->willReturn($metadata = $this->createMock(ClassMetadataInterface::class));

        $metadata
            ->expects(static::once())
            ->method('getRepository')
            ->willReturn('');

        static::assertInstanceOf(
            ObjectRepository::class,
            $repo1 = $this->fixture->getRepository($documentManager, $className)
        );

        static::assertInstanceOf(
            DefaultRepository::class,
            $repo1
        );

        static::assertSame($repo1, $this->fixture->getRepository($documentManager, $className));
    }
}
