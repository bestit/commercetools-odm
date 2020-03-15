<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\UnitOfWork\ResponseHandlers;

use BestIt\CommercetoolsODM\Events;
use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Commercetools\Core\Model\Common\JsonObject;
use Commercetools\Core\Model\Customer\CustomerSigninResult;
use Commercetools\Core\Request\AbstractDeleteRequest;
use Commercetools\Core\Response\ApiResponseInterface;
use function in_array;
use function ucfirst;

/**
 * Handles the persist requests.
 *
 * @author blange <bjoern.lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\UnitOfWork\ResponseHandlers
 */
class PersistResponseHandler extends ResponseHandlerAbstract
{
    /**
     * Can this object handle the given response?
     *
     * @param ApiResponseInterface $response
     *
     * @return bool
     */
    public function canHandleResponse(ApiResponseInterface $response): bool
    {
        $allowedCodes = [200, 201];

        return !($response->getRequest() instanceof AbstractDeleteRequest) &&
            in_array($response->getStatusCode(), $allowedCodes, true);
    }

    /**
     * Saves the updated object in the unit of work.
     *
     * @param ApiResponseInterface $response
     *
     * @return void
     */
    public function handleResponse(ApiResponseInterface $response)
    {
        $documentManager = $this->getDocumentManager();
        $update = $response->toObject();
        $uow = $documentManager->getUnitOfWork();

        if ($update instanceof CustomerSigninResult) {
            $update = $update->getCustomer();
        }

        $this->logger->info(
            'Persisted object.',
            [
                'class' => get_class($update),
                'id' => $objectId = $response->getRequest()->getIdentifier(),
                'statusCode' => $response->getStatusCode(),
            ]
        );

        if ($originalDocument = $uow->tryGetById($objectId)) {
            $this->updateWorkingModelWithResponse($originalDocument, $update);
        } else {
            $originalDocument = $update;
        }

        $documentManager->merge($originalDocument);

        $uow->invokeLifecycleEvents($originalDocument, Events::POST_PERSIST);

        $uow->processDeferredDetach($originalDocument);
    }


    /**
     * There is no public working and safe api to update a ct object with the data of another one, so use own mapping.
     *
     * @todo Refactor to mapper.
     *
     * @param mixed $originalModel The working object.
     * @param JsonObject $responseObject The ct response.
     *
     * @return mixed
     */
    private function updateWorkingModelWithResponse($originalModel, JsonObject $responseObject)
    {
        $documentManager = $this->getDocumentManager();

        $metadata = $documentManager->getClassMetadata(get_class($originalModel));

        assert($metadata instanceof ClassMetadataInterface);

        $documentManager->refresh($originalModel, $responseObject);

        if (!$metadata->isCTStandardModel()) {
            if ($versionField = $metadata->getVersion()) {
                $originalModel->{'set' . ucfirst($versionField)}($responseObject->getVersion());
            }

            if ($idField = $metadata->getIdentifier()) {
                $originalModel->{'set' . ucfirst($idField)}($responseObject->getId());
            }
        }

        return $originalModel;
    }
}
