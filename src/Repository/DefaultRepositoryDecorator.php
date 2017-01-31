<?php

namespace BestIt\CommercetoolsODM\Repository;

use BestIt\CommercetoolsODM\DocumentManagerInterface;

/**
 * Decorator for the default repository.
 * @author blange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM
 * @subpackage Repository
 * @version $id$
 */
class DefaultRepositoryDecorator implements ObjectRepository
{
    /**
     * The wrapped repository.
     * @var ObjectRepository
     */
    private $wrapped = null;

    /**
     * DefaultRepositoryDecorator constructor.
     * @param ObjectRepository $wrapped
     */
    public function __construct(ObjectRepository $wrapped)
    {
        $this->setWrapped($wrapped);
    }

    /**
     * Should the expand cache be cleared after the query.
     * @param bool $newStatus The new status.
     * @return bool The old status.
     */
    public function clearExpandAfterQuery($newStatus = false): bool
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
    public function findAll()
    {
        return $this->getWrapped()->{__FUNCTION__}(...func_get_args());
    }

    /**
     * Finds an object by its primary key / identifier.
     * @param mixed $id The identifier.
     * @param callable|void $onResolve Callback on the successful response.
     * @param callable|void $onReject Callback for an error.
     * @return void
     * @throws Exception If there is something wrong.
     */
    public function findAsync($id, callable $onResolve = null, callable $onReject = null)
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
        return $this->getWrapped()->{__FUNCTION__}(...func_get_args());
    }

    /**
     * Finds objects by a set of criteria.
     *
     * Optionally sorting and limiting details can be passed. An implementation may throw
     * an UnexpectedValueException if certain values of the sorting or limiting details are
     * not supported.
     * @param array $criteria
     * @param array $orderBy
     * @param int $limit
     * @param int $offset
     * @param callable|void $onResolve Callback on the successful response.
     * @param callable|void $onReject Callback for an error.
     * @return void
     * @throws Exception If there is something wrong.
     */
    public function findByAsync(
        array $criteria,
        array $orderBy = [],
        int $limit = 0,
        int $offset = 0,
        callable $onResolve = null,
        callable $onReject = null
    ) {
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
     * @param array $criteria The criteria.
     * @param callable|void $onResolve Callback on the successful response.
     * @param callable|void $onReject Callback for an error.
     * @return void
     * @throws Exception If there is something wrong.
     */
    public function findOneByAsync(array $criteria, callable $onResolve = null, callable $onReject = null)
    {
        return $this->getWrapped()->{__FUNCTION__}(...func_get_args());
    }

    /**
     * Returns the class name of the object managed by the repository.
     *
     * @return string
     */
    public function getClassName()
    {
        return $this->getWrapped()->{__FUNCTION__}(...func_get_args());
    }

    /**
     * Returns the used document manager.
     * @return DocumentManagerInterface
     */
    public function getDocumentManager(): DocumentManagerInterface
    {
        return $this->getWrapped()->{__FUNCTION__}(...func_get_args());
    }

    /**
     * Returns the elements which should be expanded.
     * @return array
     */
    public function getExpands(): array
    {
        return $this->getWrapped()->{__FUNCTION__}(...func_get_args());
    }

    /**
     * Returns the wrapped repository.
     * @return ObjectRepository
     */
    protected function getWrapped(): ObjectRepository
    {
        return $this->wrapped;
    }

    /**
     * Set the elements which should be expanded.
     * @param array $expands
     * @param bool $clearAfterwards Should the expand cache be cleared after the query.
     * @return ObjectRepository
     */
    public function setExpands(array $expands, $clearAfterwards = false): ObjectRepository
    {
        return $this->getWrapped()->{__FUNCTION__}(...func_get_args());
    }

    /**
     * Sets the wrapped repository.
     * @param ObjectRepository $wrapped
     * @return DefaultRepositoryDecorator
     */
    private function setWrapped(ObjectRepository $wrapped): DefaultRepositoryDecorator
    {
        $this->wrapped = $wrapped;

        return $this;
    }
}
