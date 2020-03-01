<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\UnitOfWork\ResponseHandlers;

use BestIt\CommercetoolsODM\Events;
use Commercetools\Core\Request\AbstractDeleteRequest;
use Commercetools\Core\Response\ApiResponseInterface;

/**
 * Handles the delete responses.
 *
 * @author blange <bjoern.lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\UnitOfWork\ResponseHandlers
 */
class DeleteResponseHandler extends ResponseHandlerAbstract
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
        return $response->getRequest() instanceof AbstractDeleteRequest;
    }

    /**
     * Removes the deleted object from the unit of work after the lifecycle events are invoked.
     *
     * @param ApiResponseInterface $response
     *
     * @return void
     */
    public function handleResponse(ApiResponseInterface $response)
    {
        $this->logger->info(
            'Deleted object.',
            ['objectId' => $objectId = $response->getRequest()->getIdentifier()]
        );

        $documentManager = $this->getDocumentManager();
        $unitOfWork = $documentManager->getUnitOfWork();

        $unitOfWork->invokeLifecycleEvents($object = $unitOfWork->tryGetById($objectId), Events::POST_REMOVE);

        $documentManager->detach($object);
    }
}
