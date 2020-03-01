<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\UnitOfWork\ResponseHandlers;

use BestIt\CommercetoolsODM\Exception\ResponseException;
use Commercetools\Core\Response\ApiResponseInterface;
use Countable;
use Psr\Log\LoggerAwareInterface;
use function assert;

/**
 * Hides a large amount of response handlers and handles responses collectively.
 *
 * @author blange <bjoern.lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\UnitOfWork\ResponseHandlers
 */
class ResponseHandlerComposite extends ResponseHandlerAbstract implements Countable
{
    /**
     * The Child handlers.
     *
     * @var ResponseHandlerInterface[]
     */
    private $childHandlers = [];

    /**
     * Registers a child handler in this composite.
     *
     * @param ResponseHandlerInterface $responseHandler
     *
     * @return $this
     */
    public function addChildHandler(ResponseHandlerInterface $responseHandler): self
    {
        $responseHandler->setLogger($this->logger);

        $this->childHandlers[] = $responseHandler;

        return $this;
    }

    /**
     * Can this object handle the given response?
     *
     * @param ApiResponseInterface $response
     *
     * @return bool
     */
    public function canHandleResponse(ApiResponseInterface $response): bool
    {
        foreach ($this->getChildHandlers() as $childHandler) {
            assert($childHandler instanceof ResponseHandlerInterface);

            if ($childHandler->canHandleResponse($response)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Returns the count of child handlers.
     *
     * @return int
     */
    public function count(): int
    {
        return count($this->childHandlers);
    }

    /**
     * Returns the child handlers after loading them.
     *
     * @return ResponseHandlerInterface[]
     */
    private function getChildHandlers()
    {
        if (!$this->childHandlers) {
            $this->loadChildHandlers();
        }

        return $this->childHandlers;
    }

    /**
     * Iterates through the child handlers and calls the matching one.
     *
     * @param ApiResponseInterface $response
     * @throws ResponseException
     *
     * @return void
     */
    public function handleResponse(ApiResponseInterface $response)
    {
        foreach ($this->getChildHandlers() as $childHandler) {
            assert($childHandler instanceof ResponseHandlerInterface);

            if ($childHandler->canHandleResponse($response)) {
                $childHandler->handleResponse($response);

                break;
            }
        }
    }

    /**
     * Load the child handlers.
     *
     * @todo Refactor to factory!
     *
     * @return void
     */
    private function loadChildHandlers()
    {
        $documentHandler = $this->getDocumentManager();

        $responseHandlers = [
            NotFoundResponseHandler::class,
            ConflictResponseHandler::class,
            DeleteResponseHandler::class,
            PersistResponseHandler::class,
            DefaultErrorResponseHandler::class,
            DefaultResponseHandler::class,
        ];

        foreach ($responseHandlers as &$handler) {
            $handler = new $handler($documentHandler);

            $this->addChildHandler($handler);
        }
    }
}
