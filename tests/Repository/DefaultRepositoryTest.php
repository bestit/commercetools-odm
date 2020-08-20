<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\Tests\Repository;

use BestIt\CommercetoolsODM\Helper\DocumentManagerAwareTrait;
use BestIt\CommercetoolsODM\Helper\FilterManagerAwareTrait;
use BestIt\CommercetoolsODM\Helper\QueryHelperAwareTrait;
use BestIt\CommercetoolsODM\Model\ByKeySearchRepositoryInterface;
use BestIt\CommercetoolsODM\Model\ByKeySearchRepositoryTrait;
use BestIt\CommercetoolsODM\Repository\DefaultRepository;
use BestIt\CommercetoolsODM\Tests\TestTraitsTrait;
use BestIt\CTAsyncPool\PoolAwareTrait;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;

/**
 * Test DefaultRepository.
 *
 * @author b3nl <code@b3nl.de>
 * @package BestIt\CommercetoolsODM\Tests\Repository
 */
class DefaultRepositoryTest extends TestCase
{
    use TestRepositoryTrait {
        TestRepositoryTrait::testInterfaces as testInterfacesInTrait;
    }
    use TestTraitsTrait;

    /**
     * Returns the names of the used traits.
     *
     * @return array
     */
    protected function getUsedTraitNames(): array
    {

        return [
            ByKeySearchRepositoryTrait::class,
            DocumentManagerAwareTrait::class,
            LoggerAwareTrait::class,
            QueryHelperAwareTrait::class,
            PoolAwareTrait::class,
            FilterManagerAwareTrait::class,
        ];
    }

    /**
     * Checks the required interfaces.
     *
     * @return void
     */
    public function testInterfaces()
    {
        static::assertInstanceOf(ByKeySearchRepositoryInterface::class, $this->fixture);
        static::assertInstanceOf(LoggerAwareInterface::class, $this->fixture);
    }

    /**
     * Returns the class name for the repository.
     *
     * @return string
     */
    protected function getRepositoryClass(): string
    {
        return DefaultRepository::class;
    }
}
