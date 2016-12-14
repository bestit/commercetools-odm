<?php

namespace BestIt\CommercetoolsODM\Model;

use BestIt\CommercetoolsODM\DocumentManagerInterface;
use BestIt\CommercetoolsODM\Exception\APIException;
use BestIt\CommercetoolsODM\Helper\DocumentManagerAwareTrait;
use BestIt\CommercetoolsODM\Helper\QueryHelperAwareTrait;
use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Commercetools\Commons\Helper\QueryHelper;
use Commercetools\Core\Model\Common\Collection;
use Commercetools\Core\Request\AbstractQueryRequest;
use Commercetools\Core\Request\ClientRequestInterface;
use Commercetools\Core\Response\ApiResponseInterface;
use Commercetools\Core\Response\ErrorResponse;
use Doctrine\Common\Persistence\ObjectRepository;
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
    use DocumentManagerAwareTrait, QueryHelperAwareTrait;

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
     */
    public function __construct(
        ClassMetadataInterface $metadata,
        DocumentManagerInterface $documentManager,
        QueryHelper $queryHelper
    ) {
        $this
            ->setDocumentManager($documentManager)
            ->setMetdata($metadata)
            ->setQueryHelper($queryHelper);
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
            $request = $documentManager->createRequest(
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

        $request = $documentManager->createRequest($this->getClassName(), DocumentManagerInterface::REQUEST_TYPE_QUERY);

        /** @var Collection|array $rawDocuments */
        $rawDocuments = $this->getQueryHelper()->getAll($documentManager->getClient(), $request);

        foreach ($rawDocuments as $rawDocument) {
            $documents[$rawDocument->getId()] = $uow->createDocument($this->getClassName(), $rawDocument, []);
        }

        return $documents;
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
        /** @var DocumentManagerInterface $documentManager */
        $documents = [];
        $documentManager = $this->getDocumentManager();
        $uow = $documentManager->getUnitOfWork();

        /** @var AbstractQueryRequest $request */
        $request = $documentManager->createRequest($this->getClassName(), DocumentManagerInterface::REQUEST_TYPE_QUERY);

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

        // TODO Error Message
        list($rawDocuments, $response, $request) = $this->processQuery($request);

        if ($response instanceof ErrorResponse) {
            var_dump($response->getMessage());
        }

        foreach ($rawDocuments as $rawDocument) {
            $documents[$rawDocument->getId()] = $uow->createDocument($this->getClassName(), $rawDocument, []);
        }

        return $documents;
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
     * Returns the class name of the object managed by the repository.
     * @return string
     */
    public function getClassName(): string
    {
        return $this->getMetdata()->getName();
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
     * @return array<mixed|ApiResponseInterface|ClientRequestInterface> The mapped response, the raw response, the
     *         request.
     */
    protected function processQuery(ClientRequestInterface $request): array
    {
        $response = $this->getDocumentManager()->getClient()->execute($request);

        return [$request->mapResponse($response), $response, $request];
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
