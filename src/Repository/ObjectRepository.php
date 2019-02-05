<?php

namespace BestIt\CommercetoolsODM\Repository;

use BestIt\CommercetoolsODM\DocumentManagerInterface;
use Commercetools\Core\Response\ApiResponseInterface;
use Doctrine\Common\Persistence\ObjectRepository as BasicInterface;
use Exception;

/**
 * The API for the object repos.
 *
 * @author lange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\Repository
 * @subpackage Repository
 */
interface ObjectRepository extends BasicInterface
{
    /**
     * Should the expand cache be cleared after the query.
     *
     * @param bool $newStatus The new status.
     *
     * @return bool The old status.
     */
    public function clearExpandAfterQuery(bool $newStatus = false): bool;

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
    public function findAsync($id, callable $onResolve = null, callable $onReject = null): ApiResponseInterface;

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
    ): ApiResponseInterface;

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
    ): ApiResponseInterface;

    /**
     * Returns the used document manager.
     *
     * @return DocumentManagerInterface
     */
    public function getDocumentManager(): DocumentManagerInterface;

    /**
     * Returns the elements which should be expanded.
     *
     * @return array
     */
    public function getExpands(): array;

    /**
     * Returns the array of registered filters.
     *
     * @return array
     */
    public function getFilters(): array;

    /**
     * Shortcut to save the given model.
     *
     * @param mixed $model The saving model.
     * @param bool $withFlush Should the document manager flush the buffer?
     *
     * @return mixed The "saved" model.
     */
    public function save($model, bool $withFlush = false);

    /**
     * Set the elements which should be expanded.
     *
     * @param array $expands
     * @param bool $clearAfterwards Should the expand cache be cleared after the query.
     *
     * @return ObjectRepository
     */
    public function setExpands(array $expands, bool $clearAfterwards = false): ObjectRepository;

    /**
     * Apply filters
     *
     * @param string[] $filters
     *
     * @return ObjectRepository
     */
    public function filter(string... $filters): ObjectRepository;
}
