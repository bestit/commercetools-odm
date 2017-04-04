<?php

namespace BestIt\CommercetoolsODM\Model;

use BadMethodCallException;
use BestIt\CommercetoolsODM\DocumentManagerInterface;
use BestIt\CommercetoolsODM\Exception\APIException;
use BestIt\CommercetoolsODM\Helper\DocumentManagerAwareTrait;
use BestIt\CommercetoolsODM\Helper\QueryHelperAwareTrait;
use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use BestIt\CommercetoolsODM\Repository\ObjectRepository;
use BestIt\CTAsyncPool\PoolAwareTrait;
use BestIt\CTAsyncPool\PoolInterface;
use Commercetools\Commons\Helper\QueryHelper;
use Commercetools\Core\Client\Adapter\Guzzle6Promise;
use Commercetools\Core\Model\Common\Collection;
use Commercetools\Core\Request\AbstractQueryRequest;
use Commercetools\Core\Request\ClientRequestInterface;
use Commercetools\Core\Request\ExpandTrait;
use Commercetools\Core\Response\ApiResponseInterface;
use Commercetools\Core\Response\ErrorResponse;
use Commercetools\Core\Response\ResourceResponse;
use GuzzleHttp\Promise\FulfilledPromise;
use UnexpectedValueException;

/**
 * The default repository for this commercetools package.
 * @author lange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM
 * @subpackage $id$
 * @version $id$
 */
class DefaultRepository implements ObjectRepository
{
    use DocumentManagerAwareTrait, QueryHelperAwareTrait, PoolAwareTrait;

    /**
     * Should the expand cache be cleared after the query.
     * @var bool
     */
    private $clearExpandAfterQuery = false;

    /**
     * Saves the elements which should be expanded.
     * @var array
     */
    private $expands = [];

    /**
     * The metadata for the used class.
     * @var ClassMetadataInterface
     */
    private $metdata = null;

    /**
     * DefaultRepository constructor.
     * @param ClassMetadataInterface $metadata
     * @param DocumentManagerInterface $documentManager
     * @param QueryHelper $queryHelper
     * @param PoolInterface|void $Pool
     */
    public function __construct(
        ClassMetadataInterface $metadata,
        DocumentManagerInterface $documentManager,
        QueryHelper $queryHelper,
        PoolInterface $pool = null
    ) {
        $this
            ->setDocumentManager($documentManager)
            ->setMetdata($metadata)
            ->setQueryHelper($queryHelper);

        if ($pool) {
            $this->setPool($pool);
        }
    }

    /**
     * Adds the expanded fields to the request.
     * @param ClientRequestInterface $request
     * @return void
     */
    protected function addExpandsToRequest(ClientRequestInterface $request)
    {
        /** @var ExpandTrait $request */
        if (method_exists($request, 'expand')) {
            array_map(function (string $expand) use ($request) {
                $request->expand($expand);
            }, $this->getExpands());
        }
    }

    /**
     * Should the expand cache be cleared after the query.
     * @param bool $newStatus The new status.
     * @return bool The old status.
     */
    public function clearExpandAfterQuery($newStatus = false): bool
    {
        $oldStatus = $this->clearExpandAfterQuery;

        if (func_num_args()) {
            $this->clearExpandAfterQuery = $newStatus;
        }

        return $oldStatus;
    }

    /**
     * Creates the find query with the given criteria.
     * @param array $criteria
     * @param array $orderBy
     * @param int $limit
     * @param int $offset
     * @return ClientRequestInterface
     */
    private function createFindByQuery(
        array $criteria,
        array $orderBy = [],
        int $limit = 0,
        int $offset = 0
    ): ClientRequestInterface {
        /** @var AbstractQueryRequest $request */
        $request = $this->getDocumentManager()->createRequest(
            $this->getClassName(),
            DocumentManagerInterface::REQUEST_TYPE_QUERY
        );

        $this->addExpandsToRequest($request);

        if ((method_exists($request, 'staged')) && ($criteria) && isset($criteria['staged'])) {
            $request->staged($criteria['staged']);
            unset($criteria['staged']);
        }

        if ($criteria) {
            array_walk($criteria, function ($value, $field) use ($request) {
                $request->where(is_numeric($field) ? $value : sprintf('%s="%s"', $field, $value));
            });
        }

        if ($limit) {
            $request->limit($limit);
        }

        if ($offset) {
            $request->offset($offset);
        }

        if ($orderBy) {
            array_walk($orderBy, function (string $direction, string $key) use ($request) {
                $request->sort($key . ' ' . $direction);
            });
        }

        return $request;
    }

    /**
     * Returns a simple query.
     * @param string $objectClass
     * @param string $queryType
     * @param array ...$parameters
     * @return ClientRequestInterface
     */
    protected function createSimpleQuery(
        string $objectClass,
        string $queryType,
        ...$parameters
    ): ClientRequestInterface {
        $request = $this->getDocumentManager()->createRequest($objectClass, $queryType, ...$parameters);

        $this->addExpandsToRequest($request);

        return $request;
    }

    /**
     * Finds an object by its primary key / identifier.
     * @param mixed $id The identifier.
     * @return object The object.
     * @throws APIException
     */
    public function find($id)
    {
        /** @var DocumentManagerInterface $documentManager */
        $documentManager = $this->getDocumentManager();
        $uow = $documentManager->getUnitOfWork();

        $document = $uow->tryGetById($id);

        if (!$document) {
            $request = $this->createSimpleQuery(
                $this->getClassName(),
                DocumentManagerInterface::REQUEST_TYPE_FIND_BY_ID,
                $id
            );

            /** @var ApiResponseInterface $rawResponse */
            list ($response, $rawResponse) = $this->processQuery($request);

            if ($rawResponse->getStatusCode() !== 404) {
                if ($rawResponse->isError()) {
                    throw APIException::fromResponse($rawResponse);
                }

                $document = $uow->createDocument($this->getClassName(), $response, []);
            }
        }

        return $document;
    }

    /**
     * Finds all objects in the repository.
     * @return array The objects.
     * @todo Should not be used for to large result sets.
     */
    public function findAll(): array
    {
        /** @var DocumentManagerInterface $documentManager */
        $documents = [];
        $documentManager = $this->getDocumentManager();
        $uow = $documentManager->getUnitOfWork();

        $request = $this->createSimpleQuery(
            $this->getClassName(),
            DocumentManagerInterface::REQUEST_TYPE_QUERY
        );

        /** @var Collection|array $rawDocuments */
        $rawDocuments = $this->getQueryHelper()->getAll($documentManager->getClient(), $request);

        foreach ($rawDocuments as $rawDocument) {
            $documents[$rawDocument->getId()] = $uow->createDocument($this->getClassName(), $rawDocument, []);
        }

        return $documents;
    }

    /**
     * Finds an object by its primary key / identifier.
     * @deprecated Don't use the callback param anymore. Use chaining!
     * @param mixed $id The identifier.
     * @param callable|void $onResolve Callback on the successful response.
     * @param callable|void $onReject Callback for an error.
     * @return ApiResponseInterface
     * @todo Not tested.
     */
    public function findAsync($id, callable $onResolve = null, callable $onReject = null): ApiResponseInterface
    {
        $return = null;

        $request = $this->createSimpleQuery(
            $this->getClassName(),
            DocumentManagerInterface::REQUEST_TYPE_FIND_BY_ID,
            $id
        );

        if (!$document = $this->getDocumentManager()->getUnitOfWork()->tryGetById($id)) {
            $return = $this->processQueryAsync($request, $onResolve, $onReject)->then(function ($document) {
                $this->getDocumentManager()->getUnitOfWork()->createDocument(get_class($document), $document, []);

                return $document;
            })->then($onResolve, $onReject);
        } else {
            // TODO: Bad Faking of a native promise on top of the api response interface from commercetools.
            $return = (new ResourceResponse(new Guzzle6Promise(new FulfilledPromise($document)), $request))->then(
                $onResolve
            );
        }

        return $return;
    }

    /**
     * Finds objects by a set of criteria.
     *
     * Optionally sorting and limiting details can be passed. An implementation may throw
     * an UnexpectedValueException if certain values of the sorting or limiting details are
     * not supported.
     * @param array $criteria
     * @param array|null $orderBy
     * @param int|null $limit
     * @param int|null $offset
     * @return array The objects.
     * @throws UnexpectedValueException
     * @todo Add staged as general on the test webs
     */
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null): array
    {
        $request = $this->createFindByQuery($criteria, $orderBy ?? [], $limit ?? 0, $offset ?? 0);

        // TODO Error Message
        list($rawDocuments, $response) = $this->processQuery($request);

        if ($response instanceof ErrorResponse) {
            var_dump($response->getMessage());
        }

        return $this->getObjectsFromCollection($rawDocuments);
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
     */
    public function findByAsync(
        array $criteria,
        array $orderBy = [],
        int $limit = 0,
        int $offset = 0,
        callable $onResolve = null,
        callable $onReject = null
    ): ApiResponseInterface {
        $request = $this->createFindByQuery($criteria, $orderBy, $limit, $offset);

        return $this->processQueryAsync($request, $onResolve, $onReject);
    }

    /**
     * Finds a single object by a set of criteria.
     * @param array $criteria The criteria.
     * @return mixed The object.
     */
    public function findOneBy(array $criteria)
    {
        $found = $this->findBy($criteria, [], 1);

        return $found ? array_values($found)[0] : null;
    }

    /**
     * Finds a single object by a set of criteria.
     * @deprecated Don't use the callback param anymore. Use chaining!
     * @param array $criteria The criteria.
     * @param callable|void $onResolve Callback on the successful response.
     * @param callable|void $onReject Callback for an error.
     * @return ApiResponseInterface
     */
    public function findOneByAsync(
        array $criteria,
        callable $onResolve = null,
        callable $onReject = null
    ): ApiResponseInterface {
        return $this->findByAsync($criteria, 1, 0, $onResolve, $onReject);
    }

    /**
     * Returns the class name of the object managed by the repository.
     * @return string
     */
    public function getClassName(): string
    {
        return $this->getMetdata()->getName();
    }

    /**
     * Returns the elements which should be expanded.
     * @return array
     */
    public function getExpands(): array
    {
        return $this->expands;
    }

    /**
     * Returns the collection as an array with the ids as keys.
     * @param Collection $rawDocuments
     * @return array
     */
    private function getObjectsFromCollection(Collection $rawDocuments): array
    {
        /** @var DocumentManagerInterface $documentManager */
        $documents = [];
        $uow = $this->getDocumentManager()->getUnitOfWork();

        foreach ($rawDocuments as $rawDocument) {
            $documents[$rawDocument->getId()] = $uow->createDocument($this->getClassName(), $rawDocument, []);
        }

        return $documents;
    }

    /**
     * Returns the metadata for the used class.
     * @return ClassMetadataInterface
     */
    protected function getMetdata(): ClassMetadataInterface
    {
        return $this->metdata;
    }

    /**
     * Processes the given query.
     * @param ClientRequestInterface $request
     * @param bool $isAsync Should this request be handled asynchronous?
     * @return array<mixed|ApiResponseInterface|ClientRequestInterface> The mapped response, the raw response, the
     *         request.
     */
    protected function processQuery(ClientRequestInterface $request): array
    {
        $response = $this->getDocumentManager()->getClient()->execute($request);

        if ($this->clearExpandAfterQuery()) {
            $this->setExpands([]);
        }

        return [$request->mapResponse($response), $response, $request];
    }

    /**
     * Processes the given query async.
     * @param ClientRequestInterface $request
     * @param callable|void $onResolve Callback on the successful response.
     * @param callable|void $onReject Callback for an error.
     * @return ApiResponseInterface
     * @throws BadMethodCallException
     */
    protected function processQueryAsync(
        ClientRequestInterface $request,
        callable $onResolve = null,
        callable $onReject = null
    ) {
        if (!$pool = $this->getPool()) {
            throw new BadMethodCallException('Missing async request pool');
        }

        return $pool->addPromise($request)->then($onResolve, $onReject);
    }

    /**
     * Set the elements which should be expanded.
     * @param array $expands
     * @param bool $clearAfterwards Should the expand cache be cleared after the query.
     * @return ObjectRepository
     */
    public function setExpands(array $expands, $clearAfterwards = false): ObjectRepository
    {
        $this->expands = $expands;

        $this->clearExpandAfterQuery($clearAfterwards);

        return $this;
    }

    /**
     * Sets the metadata for the used class.
     * @param ClassMetadataInterface $metdata
     * @return DefaultRepository
     */
    protected function setMetdata(ClassMetadataInterface $metdata): DefaultRepository
    {
        $this->metdata = $metdata;

        return $this;
    }
}
