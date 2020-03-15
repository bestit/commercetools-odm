<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\UnitOfWork\ResponseHandlers;

use BestIt\CommercetoolsODM\Exception\RemoveCategoryException;
use BestIt\CommercetoolsODM\Exception\ResponseException;
use Commercetools\Core\Response\ApiResponseInterface;
use Commercetools\Core\Response\ErrorResponse;
use function stripos;

/**
 * Exception for the special error if a category can not be removed.
 *
 * @author blange <bjoern.lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\UnitOfWork\ResponseHandlers
 */
class CategoryNotRemovedHandler extends ResponseHandlerAbstract
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
        return $response instanceof ErrorResponse &&
            stripos($response->getMessage(), 'Cannot remove from category') !== false;
    }

    /**
     * Throws a wrapped exception.
     *
     * @param ApiResponseInterface $response
     * @throws ResponseException
     *
     * @return void
     */
    public function handleResponse(ApiResponseInterface $response)
    {
        throw RemoveCategoryException::fromResponse($response);
    }
}
