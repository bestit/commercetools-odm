<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\Repository\Decorator;

use BestIt\CommercetoolsODM\Repository\ObjectRepository;
use Commercetools\Core\Response\ApiResponseInterface;
use Exception;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\InvalidArgumentException;

/**
 * Caches the fetch requests for the given amount of time.
 *
 * @author blange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\Repository\Decorator
 */
class CachedDefaultRepositoryDecorator extends DefaultRepositoryDecorator
{
    /**
     * @var int The time to life in seconds.
     */
    const DEFAULT_CACHE_LIFETIME = 600;

    /**
     * @var CacheItemPoolInterface
     */
    private $cacheItemPool;

    /**
     * @var int The lifetime for the cache in seconds.
     */
    private $cacheTTL;

    /**
     * CachedDefaultRepositoryDecorator constructor.
     *
     * @param CacheItemPoolInterface $cacheItemPool
     * @param ObjectRepository $objectRepository
     * @param int $cacheTTL The lifetime for the cache in seconds.
     */
    public function __construct(
        CacheItemPoolInterface $cacheItemPool,
        ObjectRepository $objectRepository,
        int $cacheTTL =
        self::DEFAULT_CACHE_LIFETIME
    ) {
        parent::__construct($objectRepository);

        $this->cacheTTL = $cacheTTL;
        $this->cacheItemPool = $cacheItemPool;
    }

    /**
     * Decorates the call to the original repository with a cached proxy.
     *
     * @param array $arguments The arguments to call the original repo.
     * @param string $function The function used as part of the cache key.
     * @return mixed
     * @throws InvalidArgumentException
     */
    private function decorateCallWithCache(array $arguments, string $function)
    {
        $cacheHit = $this->cacheItemPool->getItem($this->getCacheKey($function, ...$arguments));

        if (!$cacheHit->isHit()) {
            $this->cacheItemPool->save(
                $cacheHit
                    ->set(parent::{$function}(...$arguments))
                    ->expiresAfter($this->cacheTTL)
            );
        }

        return $cacheHit->get();
    }

    /**
     * Apply the filters with the given names.
     *
     * @param string[] ...$filters
     * @return ObjectRepository
     */
    public function filter(string... $filters): ObjectRepository
    {
        return $this->getWrapped()->{__FUNCTION__}(...func_get_args());
    }

    /**
     * Finds an object by its primary key / identifier.
     *
     * @param mixed $id The identifier.
     * @return object|null The object.
     * @throws InvalidArgumentException
     */
    public function find($id)
    {
        return $this->decorateCallWithCache(func_get_args(), __FUNCTION__);
    }

    /**
     * Finds all objects in the repository.
     *
     * @return array The objects.
     * @throws InvalidArgumentException
     */
    public function findAll()
    {
        return $this->decorateCallWithCache(func_get_args(), __FUNCTION__);
    }

    /**
     * Finds an object by its primary key / identifier.
     *
     * @param mixed $id The identifier.
     * @param callable|void $onResolve Callback on the successful response.
     * @param callable|void $onReject Callback for an error.
     *
     * @deprecated Don't use the callback param anymore. Use chaining!
     * @throws Exception If there is something wrong.
     * @return ApiResponseInterface
     */
    public function findAsync($id, callable $onResolve = null, callable $onReject = null): ApiResponseInterface
    {
        return $this->decorateCallWithCache(func_get_args(), __FUNCTION__);
    }

    /**
     * Finds objects by a set of criteria.
     *
     * Optionally sorting and limiting details can be passed. An implementation may throw
     * an UnexpectedValueException if certain values of the sorting or limiting details are
     * not supported.
     *
     * @param array $criteria
     * @param array|null $orderBy
     * @param int|null $limit
     * @param int|null $offset
     *
     * @return array The objects.
     *
     * @throws \UnexpectedValueException
     */
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        return $this->decorateCallWithCache(func_get_args(), __FUNCTION__);
    }

    /**
     * Finds objects by a set of criteria.
     *
     * Optionally sorting and limiting details can be passed. An implementation may throw an UnexpectedValueException
     * if certain values of the sorting or limiting details are not supported.
     *
     * @param array $criteria
     * @param array $orderBy
     * @param int $limit
     * @param int $offset
     * @param callable|void $onResolve Callback on the successful response.
     * @param callable|void $onReject Callback for an error.
     *
     * @deprecated Don't use the callback param anymore. Use chaining!
     * @throws Exception If there is something wrong.
     * @return ApiResponseInterface
     */
    public function findByAsync(
        array $criteria,
        array $orderBy = [],
        int $limit = 0,
        int $offset = 0,
        callable $onResolve = null,
        callable $onReject = null
    ): ApiResponseInterface {
        return $this->decorateCallWithCache(func_get_args(), __FUNCTION__);
    }

    /**
     * Finds a single object by a set of criteria.
     *
     * @param array $criteria The criteria.
     * @return object|null The object.
     */
    public function findOneBy(array $criteria)
    {
        return $this->decorateCallWithCache(func_get_args(), __FUNCTION__);
    }

    /**
     * Finds a single object by a set of criteria.
     *
     * @param array $criteria The criteria.
     * @param callable|void $onResolve Callback on the successful response.
     * @param callable|void $onReject Callback for an error.
     *
     * @deprecated Don't use the callback param anymore. Use chaining!
     * @throws Exception If there is something wrong.
     * @return ApiResponseInterface
     */
    public function findOneByAsync(
        array $criteria,
        callable $onResolve = null,
        callable $onReject = null
    ): ApiResponseInterface {
        return $this->decorateCallWithCache(func_get_args(), __FUNCTION__);
    }

    /**
     * Returns the cache key for the given arguments and internal markers.
     *
     * @param string $function
     * @param mixed[] ...$arguments
     *
     * @return string
     */
    private function getCacheKey(string $function, ...$arguments): string
    {
        return sha1(
            $function . '|' . serialize($this->getExpands()) . '|' . serialize($this->getFilters()) . '|' .
            serialize($arguments)
        );
    }
}
