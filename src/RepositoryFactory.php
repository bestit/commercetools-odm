<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM;

use BestIt\CommercetoolsODM\Filter\FilterManagerInterface;
use BestIt\CommercetoolsODM\Helper\FilterManagerAwareTrait;
use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use BestIt\CommercetoolsODM\Model\DefaultRepository;
use BestIt\CTAsyncPool\PoolAwareTrait;
use BestIt\CTAsyncPool\PoolInterface;
use Doctrine\Common\Persistence\ObjectRepository;

/**
 * Factory to provide the system with repositories.
 *
 * @author lange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM
 */
class RepositoryFactory implements RepositoryFactoryInterface
{
    use PoolAwareTrait;
    use FilterManagerAwareTrait;

    /**
     * @var string The default repository for this factory.
     */
    const DEFAULT_REPOSITORY = DefaultRepository::class;

    /**
     * @var ObjectRepository[] Loaded repos.
     */
    private $cachedRepos = [];

    /**
     * RepositoryFactory constructor.
     *
     * @param FilterManagerInterface $filterManager
     * @param PoolInterface|null $pool
     */
    public function __construct(FilterManagerInterface $filterManager, PoolInterface $pool = null)
    {
        $this->setFilterManager($filterManager);

        if ($pool) {
            $this->setPool($pool);
        }
    }

    /**
     * Gets the repository for a class.
     *
     * @param DocumentManagerInterface $documentManager
     * @param string $className
     *
     * @return ObjectRepository
     */
    public function getRepository(DocumentManagerInterface $documentManager, string $className): ObjectRepository
    {
        if (!@$this->cachedRepos[$className]) {
            $metadata = $documentManager->getClassMetadata($className);
            $repository = static::DEFAULT_REPOSITORY;

            if (($metadata instanceof ClassMetadataInterface) && ($tmp = $metadata->getRepository())) {
                $repository = $tmp;
            }

            $this->cachedRepos[$className] = new $repository(
                $metadata,
                $documentManager,
                $documentManager->getQueryHelper(),
                $this->getFilterManager(),
                $this->getPool()
            );
        }

        return $this->cachedRepos[$className];
    }
}
