<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\UnitOfWork\ResponseHandlers;

use BestIt\CommercetoolsODM\Exception\ResponseException;
use Commercetools\Core\Response\ApiResponseInterface;
use Psr\Log\LoggerAwareInterface;

/**
 * Basic interface to handle responses in the unit of work.
 *
 * @author blange <bjoern.lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\UnitOfWork\ResponseHandlers
 */
interface ResponseHandlerInterface extends LoggerAwareInterface
{
    /**
     * Wraps the response in an APIException.
     *
     * @param ApiResponseInterface $response
     * @throws ResponseException
     *
     * @return void
     */
    public function handleResponse(ApiResponseInterface $response);

    /**
     * Can this object handle the given response?
     *
     * @param ApiResponseInterface $response
     *
     * @return bool
     */
    public function canHandleResponse(ApiResponseInterface $response): bool;
}
