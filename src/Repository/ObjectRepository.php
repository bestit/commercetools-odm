<?php

namespace BestIt\CommercetoolsODM\Repository;

use BestIt\CommercetoolsODM\DocumentManagerInterface;
use Doctrine\Common\Persistence\ObjectRepository as BasicInterface;
use Exception;

/**
 * The API for the object repos.
 * @author lange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM
 * @subpackage Repository
 * @version $id$
 */
interface ObjectRepository extends BasicInterface
{
    /**
     * Should the expand cache be cleared after the query.
     * @param bool $newStatus The new status.
     * @return bool The old status.
     */
    public function clearExpandAfterQuery($newStatus = false): bool;

    /**
     * Finds an object by its primary key / identifier.
     * @param mixed $id The identifier.
     * @param callable|void $onResolve Callback on the successful response.
     * @param callable|void $onReject Callback for an error.
     * @return void
     * @throws Exception If there is something wrong.
     */
    public function findAsync($id, callable $onResolve = null, callable $onReject = null);

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
    );

    /**
     * Finds a single object by a set of criteria.
     * @param array $criteria The criteria.
     * @param callable|void $onResolve Callback on the successful response.
     * @param callable|void $onReject Callback for an error.
     * @return void
     * @throws Exception If there is something wrong.
     */
    public function findOneByAsync(array $criteria, callable $onResolve = null, callable $onReject = null);

    /**
     * Returns the used document manager.
     * @return DocumentManagerInterface
     */
    public function getDocumentManager(): DocumentManagerInterface;

    /**
     * Returns the elements which should be expanded.
     * @return array
     */
    public function getExpands(): array;

    /**
     * Set the elements which should be expanded.
     * @param array $expands
     * @param bool $clearAfterwards Should the expand cache be cleared after the query.
     * @return ObjectRepository
     */
    public function setExpands(array $expands, $clearAfterwards = false): ObjectRepository;
}
