<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\Tests;

use BestIt\CommercetoolsODM\DocumentManagerInterface;
use BestIt\CommercetoolsODM\Filter\FilterManager;
use BestIt\CommercetoolsODM\Helper\FilterManagerAwareTrait;
use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use BestIt\CommercetoolsODM\Model\DefaultRepository;
use BestIt\CommercetoolsODM\Repository\ObjectRepository;
use BestIt\CommercetoolsODM\RepositoryFactory;
use BestIt\CommercetoolsODM\RepositoryFactoryInterface;
use BestIt\CTAsyncPool\PoolAwareTrait;
use BestIt\CTAsyncPool\PoolInterface;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use function uniqid;

/**
 * Tests the factory for the repositories.
 *
 * @package BestIt\CommercetoolsODM\Tests
 */
class RepositoryFactoryTest extends TestCase
{
    use TestTraitsTrait;

    /**
     * The tested class.
     *
     * @var RepositoryFactory
     */
    protected $fixture;

    /**
     * Returns the names of the used traits.
     *
     * @return array
     */
    protected function getUsedTraitNames(): array
    {
        return [
            FilterManagerAwareTrait::class,
            LoggerAwareTrait::class,
            PoolAwareTrait::class,
        ];
    }

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

    /**
     * Checks the required interfaces.
     *
     * @return void
     */
    public function testInterfaces()
    {
        static::assertInstanceOf(LoggerAwareInterface::class, $this->fixture);
        static::assertInstanceOf(RepositoryFactoryInterface::class, $this->fixture);
    }
}
