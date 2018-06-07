<?php

namespace BestIt\CommercetoolsODM\Repository;

use BadMethodCallException;
use BestIt\CommercetoolsODM\DocumentManagerInterface;
use BestIt\CommercetoolsODM\Exception\APIException;
use BestIt\CommercetoolsODM\Exception\ResponseException;
use BestIt\CommercetoolsODM\Model\DefaultRepository;
use Commercetools\Core\Model\Project\Project;
use Commercetools\Core\Request\Project\ProjectGetRequest;
use Commercetools\Core\Response\ApiResponseInterface;
use Commercetools\Core\Response\ErrorResponse;
use Exception;

/**
 * Class ProjectRepository
 * @author blange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM
 * @subpackage Repository
 * @version $id$
 */
class ProjectRepository extends DefaultRepository implements ProjectRepositoryInterface
{
    /**
     * Finds an object by its primary key / identifier.
     *
     * @param mixed $id The identifier.
     *
     * @return object|null The object.
     */
    public function find($id)
    {
        throw new BadMethodCallException('Method not supported for projects.');
    }

    /**
     * Finds all objects in the repository.
     * @return Project[] The objects.
     */
    public function findAll(): array
    {
        return [$this->getInfoForActualProject()];
    }

    /**
     * Finds an object by its primary key / identifier.
     * @param mixed $id The identifier.
     * @deprecated Don't use the callback param anymore. Use chaining!
     * @param callable|void $onResolve Callback on the successful response.
     * @param callable|void $onReject Callback for an error.
     * @return ApiResponseInterface
     * @throws Exception If there is something wrong.
     */
    public function findAsync($id, callable $onResolve = null, callable $onReject = null): ApiResponseInterface
    {
        throw new BadMethodCallException('Method not supported for projects.');
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
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null): array
    {
        throw new BadMethodCallException('Method not supported for projects.');
    }

    /**
     * Finds objects by a set of criteria.
     *
     * Optionally sorting and limiting details can be passed. An implementation may throw
     * an UnexpectedValueException if certain values of the sorting or limiting details are
     * not supported.
     * @deprecated Don't use the callback param anymore. Use chaining!
     * @param array $criteria
     * @param array $orderBy
     * @param int $limit
     * @param int $offset
     * @param callable|void $onResolve Callback on the successful response.
     * @param callable|void $onReject Callback for an error.
     * @return ApiResponseInterface
     * @throws Exception If there is something wrong.
     */
    public function findByAsync(
        array $criteria,
        array $orderBy = [],
        int $limit = 0,
        int $offset = 0,
        callable $onResolve = null,
        callable $onReject = null
    ): ApiResponseInterface {
        throw new BadMethodCallException('Method not supported for projects.');
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
        throw new BadMethodCallException('Method not supported for projects.');
    }

    /**
     * Finds a single object by a set of criteria.
     * @deprecated Don't use the callback param anymore. Use chaining!
     * @param array $criteria The criteria.
     * @param callable|void $onResolve Callback on the successful response.
     * @param callable|void $onReject Callback for an error.
     * @return ApiResponseInterface
     * @throws Exception If there is something wrong.
     */
    public function findOneByAsync(
        array $criteria,
        callable $onResolve = null,
        callable $onReject = null
    ): ApiResponseInterface {
        throw new BadMethodCallException('Method not supported for projects.');
    }

    /**
     * Returns the elements which should be expanded.
     * @return array
     */
    public function getExpands(): array
    {
        throw new BadMethodCallException('Method not supported for projects.');
    }

    /**
     * Returns the info for the actual projcet.
     * @return Project
     * @throws ResponseException
     */
    public function getInfoForActualProject(): Project
    {
        $request = $this->createSimpleQuery($this->getClassName(), ProjectGetRequest::class);

        list($project, $rawResponse) = $this->processQuery($request);

        if ($rawResponse instanceof ErrorResponse) {
            throw APIException::fromResponse($rawResponse);
        }

        return $project;
    }

    /**
     * Set the elements which should be expanded.
     * @param array $expands
     * @param bool $clearAfterwards Should the expand cache be cleared after the query.
     * @return ObjectRepository
     */
    public function setExpands(array $expands, bool $clearAfterwards = false): ObjectRepository
    {
        throw new BadMethodCallException('Method not supported for projects.');
    }
}
