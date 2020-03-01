<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\UnitOfWork\ResponseHandlers;

use BestIt\CommercetoolsODM\Exception\NotFoundException;
use BestIt\CommercetoolsODM\Exception\ResponseException;
use Commercetools\Core\Response\ApiResponseInterface;
use Commercetools\Core\Response\ErrorResponse;

/**
 * Throws a wrapped 404 exception.
 *
 * @author blange <bjoern.lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\UnitOfWork\ResponseHandlers
 */
class NotFoundResponseHandler extends ResponseHandlerAbstract
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
        return $response instanceof ErrorResponse && $response->getStatusCode() === 404;
    }

    /**
     * Throws a 404 exception.
     *
     * @param ApiResponseInterface $response
     *
     * @throws ResponseException
     *
     * @return void
     */
    public function handleResponse(ApiResponseInterface $response)
    {
        throw NotFoundException::fromResponse($response);
    }
}
