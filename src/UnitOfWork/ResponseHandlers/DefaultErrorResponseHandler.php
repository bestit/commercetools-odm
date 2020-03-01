<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\UnitOfWork\ResponseHandlers;

use BestIt\CommercetoolsODM\Exception\APIException;
use BestIt\CommercetoolsODM\Exception\ResponseException;
use Commercetools\Core\Response\ApiResponseInterface;
use Commercetools\Core\Response\ErrorResponse;

/**
 * Just throws an exception.
 *
 * @author blange <bjoern.lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\UnitOfWork\ResponseHandlers
 */
class DefaultErrorResponseHandler extends ResponseHandlerAbstract
{
    /**
     * Fallback for every other response.
     *
     * @param ApiResponseInterface $response
     *
     * @return bool
     */
    public function canHandleResponse(ApiResponseInterface $response): bool
    {
        return $response instanceof ErrorResponse;
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
        throw APIException::fromResponse($response);
    }
}
