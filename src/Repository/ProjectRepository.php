<?php

namespace BestIt\CommercetoolsODM\Repository;

use BadMethodCallException;
use BestIt\CommercetoolsODM\Exception\APIException;
use BestIt\CommercetoolsODM\Exception\ResponseException;
use Commercetools\Core\Model\Project\Project;
use Commercetools\Core\Request\Project\ProjectGetRequest;
use Commercetools\Core\Response\ApiResponseInterface;
use Commercetools\Core\Response\ErrorResponse;
use Exception;
use UnexpectedValueException;

/**
 * Class ProjectRepository
 *
 * @package BestIt\CommercetoolsODM\Repository
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
     *
     * @return Project[] The objects.
     */
    public function findAll(): array
    {
        return [$this->getInfoForActualProject()];
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
        throw new BadMethodCallException('Method not supported for projects.');
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
        throw new BadMethodCallException('Method not supported for projects.');
    }

    /**
     * Finds objects by a set of criteria.
     *
     * Optionally sorting and limiting details can be passed. An implementation may throw
     * an UnexpectedValueException if certain values of the sorting or limiting details are
     * not supported.
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
        throw new BadMethodCallException('Method not supported for projects.');
    }

    /**
     * Returns the info for the actual projcet.
     *
     * @throws ResponseException
     *
     * @return Project
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
     *
     * @param array $expands
     * @param bool $clearAfterwards Should the expand cache be cleared after the query.
     *
     * @return ObjectRepository
     */
    public function setExpands(array $expands, bool $clearAfterwards = false): ObjectRepository
    {
        $this->logger->warning(
            'This repository does not support the expands api.',
            ['repository' => static::class]
        );

        return parent::setExpands($expands, $clearAfterwards);
    }
}
