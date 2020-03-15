<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\UnitOfWork\ResponseHandlers;

use Commercetools\Core\Request\AbstractDeleteRequest;
use Commercetools\Core\Response\ApiResponseInterface;
use Commercetools\Core\Response\ErrorResponse;
use DomainException;

/**
 * Throws an exception for unusable status codes.
 *
 * @author blange <bjoern.lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\UnitOfWork\ResponseHandlers
 */
class DefaultResponseHandler extends ResponseHandlerAbstract
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
        return !($response->getRequest() instanceof AbstractDeleteRequest) &&
            !($response instanceof ErrorResponse);
    }

    /**
     * Throws an exception for unusable error codes.
     *
     * @param ApiResponseInterface $response
     *
     * @return void
     */
    public function handleResponse(ApiResponseInterface $response)
    {
        throw new DomainException('The delivered status code was not usable.');
    }
}
