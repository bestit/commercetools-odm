<?php

namespace BestIt\CommercetoolsODM\Exception;

use Commercetools\Core\Response\ErrorResponse;
use Exception;

/**
 * Exception class for response errors.
 * @author lange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM
 * @subpackage Exception
 * @version $id$
 */
class ResponseException extends Exception
{
    use ResponseAwareTrait;

    /**
     * Constructs and returns an exception from the given response.
     * @param ErrorResponse $response
     * @return APIException
     */
    public static function fromResponse(ErrorResponse $response)
    {
        $exception = new static($response->getMessage(), $response->getStatusCode());

        return $exception
            ->setCorrelationId($response->getCorrelationId())
            ->setResponse($response);
    }
}
