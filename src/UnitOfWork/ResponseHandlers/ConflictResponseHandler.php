<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\UnitOfWork\ResponseHandlers;

use BestIt\CommercetoolsODM\Exception\ConflictException;
use BestIt\CommercetoolsODM\Exception\ResponseException;
use BestIt\CommercetoolsODM\UnitOfWork\ConflictProcessor;
use BestIt\CommercetoolsODM\UnitOfWork\ConflictProcessorInterface;
use Commercetools\Core\Response\ApiResponseInterface;
use Commercetools\Core\Response\ErrorResponse;
use function get_class;

/**
 * Handles the conflict response.
 *
 * @author blange <bjoern.lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\UnitOfWork\ResponseHandlers
 */
class ConflictResponseHandler extends ResponseHandlerAbstract
{
    /**
     * Helper to  process conflicts which is filled by the lazy load getter.
     *
     * @var ConflictProcessorInterface|null
     */
    private $conflictProcessor;

    /**
     * Fallback for every other response.
     *
     * @param ApiResponseInterface $response
     *
     * @return bool
     */
    public function canHandleResponse(ApiResponseInterface $response): bool
    {
        $isErrorResponse = $response instanceof ErrorResponse;
        $statusCode = $response->getStatusCode();
        $isConflictCode = $statusCode === 409;
        $canHandleAsConflict = $isErrorResponse && $isConflictCode;

        $this->logger
            ->debug(
                'Checks if the given response is a conflict.',
                [
                    'canHandleAsConflict' => $canHandleAsConflict,
                    'statusCode' => $statusCode,
                    'isConflictCode' => $isConflictCode,
                    'isErrorResponse' => $isErrorResponse,
                ]
            );

        return $canHandleAsConflict;
    }

    /**
     * Returns the conflict processor.
     *
     * @return ConflictProcessorInterface
     */
    public function getConflictProcessor(): ConflictProcessorInterface
    {
        if (!$this->conflictProcessor) {
            $this->conflictProcessor = $this->loadConflictProcessor();
        }

        return $this->conflictProcessor;
    }

    /**
     * Wraps the response in an APIException.
     *
     * @param ApiResponseInterface $response
     * @throws ResponseException
     *
     * @return void
     */
    public function handleResponse(ApiResponseInterface $response)
    {
        $unitOfWork = $this->getUnitOfWork();
        $canRetry = $unitOfWork->canRetry();
        $id = $response->getRequest()->getIdentifier();
        $updatedObject = $unitOfWork->tryGetById($id);
        $hasModifyCallbacks = $unitOfWork->hasModifyCallbacks($updatedObject);

        $this->logger
            ->debug(
                'Handles the conflict reponse.',
                [
                    'canRetry' => $canRetry,
                    'class' => get_class($updatedObject),
                    'hasModifyCallbacks' => $hasModifyCallbacks,
                    'id' => $id,
                ]
            );

        if ($canRetry && $hasModifyCallbacks) {
            $this->getConflictProcessor()->handleConflict($updatedObject);
            $unitOfWork->runModifyCallbacks($updatedObject);
        } else {
            $this->logger
                ->warning(
                    'Throws the conflict as an exception.',
                    [
                        'canRetry' => $canRetry,
                        'class' => get_class($updatedObject),
                        'hasModifyCallbacks' => $hasModifyCallbacks,
                        'id' => $id,
                    ]
                );

            throw ConflictException::fromResponse($response);
        }
    }

    /**
     * Returns a fresh instance of the conflict processor.
     *
     * @todo Refactor.
     *
     * @return ConflictProcessorInterface
     */
    private function loadConflictProcessor(): ConflictProcessorInterface
    {
        $conflictProcessor = new ConflictProcessor($this->getDocumentManager());

        $conflictProcessor->setLogger($this->logger);

        return $conflictProcessor;
    }

    /**
     * Sets the conflict processor.
     *
     * @param ConflictProcessorInterface $conflictProcessor
     *
     * @return $this
     */
    public function setConflictProcessor(ConflictProcessorInterface $conflictProcessor): self
    {
        $this->conflictProcessor = $conflictProcessor;

        return $this;
    }
}
