<?php

namespace BestIt\CommercetoolsODM\Repository;

use BestIt\CommercetoolsODM\DocumentManagerInterface;
use BestIt\CommercetoolsODM\Model\DefaultRepository;

/**
 * Repository for the custom objects.
 *
 * @author lange <blange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\Repository
 * @subpackage Repository
 */
class CustomObjectRepository extends DefaultRepository
{
    /**
     * Returns a custom object or null by container and key.
     *
     * @param string $container
     * @param string $key
     *
     * @return mixed
     */
    public function findByContainerAndKey(string $container, string $key)
    {
        /** @var DocumentManagerInterface $documentManager */
        $documentManager = $this->getDocumentManager();
        $uow = $documentManager->getUnitOfWork();

        $document = $uow->tryGetByContainerAndKey($container, $key);

        if (!$document) {
            $request = $documentManager->createRequest(
                $this->getClassName(),
                DocumentManagerInterface::REQUEST_TYPE_DELETE_BY_CONTAINER_AND_KEY,
                $container,
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
}
