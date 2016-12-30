<?php

namespace BestIt\CommercetoolsODM\Model;

use BestIt\CommercetoolsODM\DocumentManager;
use BestIt\CommercetoolsODM\DocumentManagerInterface;
use BestIt\CommercetoolsODM\Exception\APIException;
use Commercetools\Core\Request\ClientRequestInterface;
use Commercetools\Core\Response\ApiResponseInterface;

/**
 * Added an additional find api to the respository.
 * @author lange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM
 * @subpackage Model
 * @version $id$
 */
trait ByKeySearchRepositoryTrait
{
    /**
     * Adds the expanded fields to the request.
     * @param ClientRequestInterface $request
     * @return void
     */
    abstract protected function addExpandsToRequest(ClientRequestInterface $request);

    /**
     * Finds an object by its user defined key.
     * @param string $key
     * @return mixed|void
     * @throws APIException If there is something wrong.
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
     * Processes the given query.
     * @param ClientRequestInterface $request
     * @return array The mapped response, the raw response, the request
     */
    abstract protected function processQuery(ClientRequestInterface $request): array;
}
