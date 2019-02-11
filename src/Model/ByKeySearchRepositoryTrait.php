<?php

namespace BestIt\CommercetoolsODM\Model;

use BestIt\CommercetoolsODM\DocumentManagerInterface;
use BestIt\CommercetoolsODM\Exception\APIException;
use BestIt\CommercetoolsODM\Exception\ResponseException;
use Commercetools\Core\Request\ClientRequestInterface;
use Commercetools\Core\Response\ApiResponseInterface;

/**
 * Added an additional find api to the respository.
 *
 * @author lange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\Model
 * @subpackage Model
 */
trait ByKeySearchRepositoryTrait
{
    /**
     * Adds the expanded fields to the request.
     *
     * @param ClientRequestInterface $request
     *
     * @return void
     */
    abstract protected function addExpandsToRequest(ClientRequestInterface $request);

    /**
     * Returns a simple query.
     *
     * @param string $objectClass
     * @param string $queryType
     * @param mixed $parameters
     *
     * @return ClientRequestInterface
     */
    abstract protected function createSimpleQuery(
        string $objectClass,
        string $queryType,
        ...$parameters
    ): ClientRequestInterface;

    /**
     * Finds an object by its user defined key.
     *
     * @param string $key
     * @throws ResponseException
     *
     * @return mixed|void
     */
    public function findByKey(string $key)
    {
        /** @var DocumentManagerInterface $documentManager */
        $documentManager = $this->getDocumentManager();
        $uow = $documentManager->getUnitOfWork();

        $document = $uow->tryGetByKey($key);

        if (!$document) {
            $request = $documentManager->createRequest(
                $this->getClassName(),
                DocumentManagerInterface::REQUEST_TYPE_FIND_BY_KEY,
                $key
            );

            $this->addExpandsToRequest($request);

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
     * Finds an object by its user defined key.
     *
     * @todo Not tested. Readd the expand api.
     *
     * @param string $key
     * @param callable|void $onResolve Callback on the successful response.
     * @param callable|void $onReject Callback for an error.
     *
     * @return mixed|void
     */
    public function findByKeyAsync(string $key, callable $onResolve = null, callable $onReject = null)
    {
        $document = $this->getDocumentManager()->getUnitOfWork()->tryGetByKey($key);

        if ($document) {
            $onResolve($document);
        } else {
            $request = $this->createSimpleQuery(
                $this->getClassName(),
                DocumentManagerInterface::REQUEST_TYPE_FIND_BY_KEY,
                $key
            );

            $this->processQueryAsync($request, $onResolve, $onReject);
        }
    }

    /**
     * Returns the used document manager.
     *
     * @return DocumentManagerInterface
     */
    abstract public function getDocumentManager(): DocumentManagerInterface;

    /**
     * Processes the given query.
     *
     * @param ClientRequestInterface $request
     *
     * @return array<mixed|ApiResponseInterface|ClientRequestInterface> The mapped response, the raw response, the
     *         request.
     */
    abstract protected function processQuery(ClientRequestInterface $request): array;

    /**
     * Processes the given query async.
     *
     * @param ClientRequestInterface $request
     * @param callable|void $onResolve Callback on the successful response.
     * @param callable|void $onReject Callback for an error.
     *
     * @return ApiResponseInterface
     */
    abstract protected function processQueryAsync(
        ClientRequestInterface $request,
        callable $onResolve = null,
        callable $onReject = null
    ): ApiResponseInterface;
}
