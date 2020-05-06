<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\Repository\Decorator;

use BestIt\CommercetoolsODM\DocumentManagerInterface;
use BestIt\CommercetoolsODM\Exception\APIException;
use BestIt\CommercetoolsODM\Exception\ResponseException;
use BestIt\CommercetoolsODM\Model\ByKeySearchRepositoryInterface;
use BestIt\CommercetoolsODM\Repository\ObjectRepository;
use Commercetools\Core\Response\ApiResponseInterface;
use Exception;
use Generator;
use UnexpectedValueException;
use function func_get_args;

/**
 * Decorator for the default repository.
 *
 * @author blange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\Repository\Decorator
 */
class DefaultRepositoryDecorator implements ByKeySearchRepositoryInterface
{
    /**
     * The wrapped repository.
     *
     * @var ObjectRepository
     */
    private $wrapped;

    /**
     * DefaultRepositoryDecorator constructor.
     *
     * @param ObjectRepository $wrapped
     */
    public function __construct(ObjectRepository $wrapped)
    {
        $this->setWrapped($wrapped);
    }

    /**
     * Should the expand cache be cleared after the query.
     *
     * @param bool $newStatus The new status.
     *
     * @return bool The old status.
     */
    public function clearExpandAfterQuery(bool $newStatus = false): bool
    {
        return $this->getWrapped()->{__FUNCTION__}(...func_get_args());
    }

    /**
     * Apply the filters with the given names.
     *
     * @param string[] $filters
     *
     * @return ObjectRepository
     */
    public function filter(string ...$filters): ObjectRepository
    {
        return $this->getWrapped()->{__FUNCTION__}(...func_get_args());
    }

    /**
     * Finds an object by its primary key / identifier.
     *
     * @param mixed $id The identifier.
     *
     * @return object|null The object.
     */
    public function find($id)
    {
        return $this->getWrapped()->{__FUNCTION__}(...func_get_args());
    }

    /**
     * Finds all objects in the repository.
     *
     * @return array The objects.
     */
    public function findAll(): array
    {
        return $this->getWrapped()->{__FUNCTION__}(...func_get_args());
    }

    /**
     * @inheritDoc
     */
    public function findAllAsGenerator(): Generator
    {
        return $this->getWrapped()->{__FUNCTION__}(...func_get_args());
    }

    /**
     * Finds and creates the object but with an optional registration in the unit of work.
     *
     * @throws ResponseException
     *
     * @param mixed $id
     * @param bool $withRegistration
     *
     * @return mixed
     */
    public function findAndCreateObject($id, bool $withRegistration = true)
    {
        return $this->getWrapped()->{__FUNCTION__}(...func_get_args());
    }

    /**
     * Finds an object by its primary key / identifier.
     *
     * @throws Exception If there is something wrong.
     *
     * @param mixed $id The identifier.
     * @param callable|void $onResolve Callback on the successful response.
     * @param callable|void $onReject Callback for an error.
     *
     * @return ApiResponseInterface
     */
    public function findAsync($id, callable $onResolve = null, callable $onReject = null): ApiResponseInterface
    {
        return $this->getWrapped()->{__FUNCTION__}(...func_get_args());
    }

    /**
     * Finds objects by a set of criteria.
     *
     * Optionally sorting and limiting details can be passed. An implementation may throw
     * an UnexpectedValueException if certain values of the sorting or limiting details are
     * not supported.
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
     * @throws UnexpectedValueException
     *
     * @param array $criteria
     * @param array|null $orderBy
     * @param int|null $limit
     * @param int|null $offset
     *
     * @return array The objects.
     */
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null): array
    {
        return $this->getWrapped()->{__FUNCTION__}(...func_get_args());
    }

    /**
     * Finds objects by a set of criteria.
     *
     * Optionally sorting and limiting details can be passed. An implementation may throw an UnexpectedValueException
     * if certain values of the sorting or limiting details are not supported.
     *
     * @throws Exception If there is something wrong.
     *
     * @param array $criteria
     * @param array $orderBy
     * @param int $limit
     * @param int $offset
     * @param callable|void $onResolve Callback on the successful response.
     * @param callable|void $onReject Callback for an error.
     *
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
        return $this->getWrapped()->{__FUNCTION__}(...func_get_args());
    }

    /**
     * Finds an object by its user defined key.
     *
     * @param string $key
     * @throws APIException If there is something wrong.
     *
     * @return mixed|void
     */
    public function findByKey(string $key)
    {
        return $this->getWrapped()->{__FUNCTION__}(...func_get_args());
    }

    /**
     * Finds an object by its user defined key.
     *
     * @param string $key
     * @param callable|void $onResolve Callback on the successful response.
     * @param callable|void $onReject Callback for an error.
     *
     * @return mixed|void
     */
    public function findByKeyAsync(string $key, callable $onResolve = null, callable $onReject = null)
    {
        return $this->getWrapped()->{__FUNCTION__}(...func_get_args());
    }

    /**
     * Finds a single object by a set of criteria.
     *
     * @param array $criteria The criteria.
     *
     * @return object|null The object.
     */
    public function findOneBy(array $criteria)
    {
        return $this->getWrapped()->{__FUNCTION__}(...func_get_args());
    }

    /**
     * Finds a single object by a set of criteria.
     *
     * @throws Exception If there is something wrong.
     *
     * @param array $criteria The criteria.
     * @param callable|void $onResolve Callback on the successful response.
     * @param callable|void $onReject Callback for an error.
     *
     * @return ApiResponseInterface
     */
    public function findOneByAsync(
        array $criteria,
        callable $onResolve = null,
        callable $onReject = null
    ): ApiResponseInterface {
        return $this->getWrapped()->{__FUNCTION__}(...func_get_args());
    }

    /**
     * Returns the class name of the object managed by the repository.
     *
     * @return string
     */
    public function getClassName(): string
    {
        return $this->getWrapped()->{__FUNCTION__}(...func_get_args());
    }

    /**
     * Returns the used document manager.
     *
     * @return DocumentManagerInterface
     */
    public function getDocumentManager(): DocumentManagerInterface
    {
        return $this->getWrapped()->{__FUNCTION__}(...func_get_args());
    }

    /**
     * Returns the elements which should be expanded.
     *
     * @return array
     */
    public function getExpands(): array
    {
        return $this->getWrapped()->{__FUNCTION__}(...func_get_args());
    }

    /**
     * Returns the array of registered filters.
     *
     * @return array
     */
    public function getFilters(): array
    {
        return $this->getWrapped()->{__FUNCTION__}(...func_get_args());
    }

    /**
     * Returns the wrapped repository.
     *
     * @return ObjectRepository
     */
    protected function getWrapped(): ObjectRepository
    {
        return $this->wrapped;
    }

    /**
     * Shortcut to save the given model.
     *
     * @param mixed $model The saving model.
     * @param bool $withFlush Should the document manager flush the buffer?
     *
     * @return mixed The "saved" model.
     */
    public function save($model, bool $withFlush = false)
    {
        return $this->getWrapped()->{__FUNCTION__}(...func_get_args());
    }

    /**
     * Set the elements which should be expanded.
     *
     * @param array $expands The identifiers of the types.
     * @param bool $clearAfterwards Should the expand cache be cleared after the query.
     *
     * @return ObjectRepository
     */
    public function setExpands(array $expands, bool $clearAfterwards = false): ObjectRepository
    {
        return $this->getWrapped()->{__FUNCTION__}(...func_get_args());
    }

    /**
     * Sets the wrapped repository.
     *
     * @param ObjectRepository $wrapped The original repository.
     *
     * @return $this
     */
    private function setWrapped(ObjectRepository $wrapped): self
    {
        $this->wrapped = $wrapped;

        return $this;
    }
}
