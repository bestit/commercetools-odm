<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\Model;

use BadMethodCallException;
use BestIt\CommercetoolsODM\DocumentManagerInterface;
use BestIt\CommercetoolsODM\Exception\ResponseException;
use BestIt\CommercetoolsODM\Filter\FilterManagerInterface;
use BestIt\CommercetoolsODM\Helper\DocumentManagerAwareTrait;
use BestIt\CommercetoolsODM\Helper\FilterManagerAwareTrait;
use BestIt\CommercetoolsODM\Helper\QueryHelperAwareTrait;
use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use BestIt\CommercetoolsODM\Repository\ObjectRepository;
use BestIt\CommercetoolsODM\RepositoryAwareInterface;
use BestIt\CTAsyncPool\PoolAwareTrait;
use BestIt\CTAsyncPool\PoolInterface;
use Commercetools\Commons\Helper\QueryHelper;
use Commercetools\Core\Client\Adapter\Guzzle6Promise;
use Commercetools\Core\Model\Channel\ChannelReference;
use Commercetools\Core\Model\Common\Collection;
use Commercetools\Core\Request\AbstractQueryRequest;
use Commercetools\Core\Request\ClientRequestInterface;
use Commercetools\Core\Request\ExpandTrait;
use Commercetools\Core\Response\ApiResponseInterface;
use Commercetools\Core\Response\ErrorResponse;
use Commercetools\Core\Response\ResourceResponse;
use GuzzleHttp\Promise\FulfilledPromise;
use UnexpectedValueException;
use function array_map;
use function array_walk;
use function func_num_args;
use function is_numeric;
use function method_exists;
use function sprintf;

/**
 * The default repository for this commercetools package.
 *
 * @author lange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM
 */
class DefaultRepository implements ByKeySearchRepositoryInterface
{
    use ByKeySearchRepositoryTrait;
    use DocumentManagerAwareTrait;
    use QueryHelperAwareTrait;
    use PoolAwareTrait;
    use FilterManagerAwareTrait;

    /**
     * @var bool Should the expand cache be cleared after the query.
     */
    private $clearExpandAfterQuery = false;

    /**
     * @var array Saves the elements which should be expanded.
     */
    private $expands = [];

    /**
     * @var string[] Filters
     */
    private $filters = [];

    /**
     * @var ClassMetadataInterface The metadata for the used class.
     */
    private $metadata;

    /**
     * DefaultRepository constructor.
     *
     * @param ClassMetadataInterface $metadata
     * @param DocumentManagerInterface $documentManager
     * @param QueryHelper $queryHelper
     * @param FilterManagerInterface $filterManager
     * @param PoolInterface|null $pool
     */
    public function __construct(
        ClassMetadataInterface $metadata,
        DocumentManagerInterface $documentManager,
        QueryHelper $queryHelper,
        FilterManagerInterface $filterManager,
        PoolInterface $pool = null
    ) {
        $this
            ->setDocumentManager($documentManager)
            ->setQueryHelper($queryHelper)
            ->setFilterManager($filterManager);

        $this->metadata = $metadata;

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
    public function clearExpandAfterQuery(bool $newStatus = false): bool
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
        $request = $this->documentManager->createRequest(
            $this->getClassName(),
            DocumentManagerInterface::REQUEST_TYPE_QUERY
        );

        $this->addExpandsToRequest($request);

        if ((method_exists($request, 'staged')) && ($criteria) && isset($criteria['staged'])) {
            $request->staged($criteria['staged']);
            unset($criteria['staged']);
        }

        if ((method_exists($request, 'channel')) && ($criteria) && isset($criteria['priceChannel'])) {
            $request->channel(ChannelReference::ofId($criteria['priceChannel']));
            unset($criteria['priceChannel']);
        }

        if ((method_exists($request, 'currency')) && ($criteria) && isset($criteria['priceCurrency'])) {
            $request->currency($criteria['priceCurrency']);
            unset($criteria['priceCurrency']);
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

        foreach ($this->filters as $filterKey) {
            $this->getFilterManager()->apply($filterKey, $request);
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
        $request = $this->documentManager->createRequest($objectClass, $queryType, ...$parameters);

        $this->addExpandsToRequest($request);

        foreach ($this->filters as $filterKey) {
            $this->getFilterManager()->apply($filterKey, $request);
        }

        return $request;
    }

    /**
     * {@inheritdoc}
     */
    public function filter(string... $filters): ObjectRepository
    {
        $this->filters = $filters;

        return $this;
    }

    /**
     * Finds an object by its primary key / identifier.
     *
     * @param mixed $id The identifier.
     * @throws ResponseException
     * @return object The found object.
     */
    public function find($id)
    {
        /** @var DocumentManagerInterface $documentManager */
        $documentManager = $this->documentManager;
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
                    throw ResponseException::fromResponse($rawResponse);
                }

                $document = $uow->createDocument($this->getClassName(), $response, []);

                if ($document instanceof RepositoryAwareInterface) {
                    $document->setRepository($this);
                }
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
        $documentManager = $this->documentManager;
        $uow = $documentManager->getUnitOfWork();

        $request = $this->createSimpleQuery(
            $this->getClassName(),
            DocumentManagerInterface::REQUEST_TYPE_QUERY
        );

        /** @var Collection|array $rawDocuments */
        $rawDocuments = $this->getQueryHelper()->getAll($documentManager->getClient(), $request);

        foreach ($rawDocuments as $rawDocument) {
            $document = $uow->createDocument($this->getClassName(), $rawDocument, []);

            $documents[$rawDocument->getId()] = $document;

            if ($document instanceof RepositoryAwareInterface) {
                $document->setRepository($this);
            }
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

        if (!$document = $this->documentManager->getUnitOfWork()->tryGetById($id)) {
            $return = $this->processQueryAsync($request, $onResolve, $onReject)->then(function ($document) {
                $this->documentManager->getUnitOfWork()->createDocument(get_class($document), $document, []);

                if ($document instanceof RepositoryAwareInterface) {
                    $document->setRepository($this);
                }

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
     * @throws ResponseException
     */
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null): array
    {
        $request = $this->createFindByQuery($criteria, $orderBy ?? [], $limit ?? 0, $offset ?? 0);

        list($rawDocuments, $response) = $this->processQuery($request);

        if ($response instanceof ErrorResponse) {
            throw ResponseException::fromResponse($response);
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
        return $this->findByAsync($criteria, [], 1, 0, $onResolve, $onReject);
    }

    /**
     * Returns the class name of the object managed by the repository.
     * @return string
     */
    public function getClassName(): string
    {
        return $this->metadata->getName();
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
     * Returns the array of registered filters.
     *
     * @return array
     */
    public function getFilters(): array
    {
        return $this->filters;
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
        $uow = $this->documentManager->getUnitOfWork();

        foreach ($rawDocuments as $rawDocument) {
            $documents[$rawDocument->getId()] = $document = $uow->createDocument(
                $this->getClassName(),
                $rawDocument,
                []
            );

            if ($document instanceof RepositoryAwareInterface) {
                $document->setRepository($this);
            }
        }

        return $documents;
    }

    /**
     * Processes the given query.
     * @param ClientRequestInterface $request
     * @return array<mixed|ApiResponseInterface|ClientRequestInterface> The mapped response, the raw response, the
     *         request.
     */
    protected function processQuery(ClientRequestInterface $request): array
    {
        $response = $this->documentManager->getClient()->execute($request);

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
     * Shortcut to save the given model.
     *
     * @param mixed $model The saving model.
     * @param bool $withFlush Should the document manager flush the buffer?
     * @return mixed The "saved" model.
     */
    public function save($model, bool $withFlush = false)
    {
        $documentManager = $this->documentManager;

        $documentManager->persist($model);

        if ($withFlush) {
            $documentManager->flush();
        }

        return $model;
    }

    /**
     * Set the elements which should be expanded.
     * @param array $expands
     * @param bool $clearAfterwards Should the expand cache be cleared after the query.
     * @return ObjectRepository
     */
    public function setExpands(array $expands, bool $clearAfterwards = false): ObjectRepository
    {
        $this->expands = $expands;

        $this->clearExpandAfterQuery($clearAfterwards);

        return $this;
    }
}
