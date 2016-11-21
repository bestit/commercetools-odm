<?php

namespace BestIt\CommercetoolsODM\Exception;

use Commercetools\Core\Response\ApiResponseInterface;
use Commercetools\Core\Response\ErrorResponse;
use Exception;

/**
 * Exception class for api errors.
 * @author lange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM
 * @subpackage Exception
 * @version $id$
 */
class APIException extends Exception
{
    /**
     * The response.
     * @var ErrorResponse
     */
    private $response = null;

    /**
     * Constructs and returns an exception from the given response.
     * @param ErrorResponse $response
     * @return APIException
     */
    public static function fromResponse(ErrorResponse $response)
    {
        $exception = new static($response->getMessage(), $response->getCorrelationId());

        return $exception->setResponse($response);
    }

    /**
     * Returns the used response.
     * @return ApiResponseInterface
     */
    public function getResponse(): ApiResponseInterface
    {
        return $this->response;
    }

    /**
     * Sets the response.
     * @param ErrorResponse $response
     * @return APIException
     */
    protected function setResponse(ErrorResponse $response): APIException
    {
        $this->response = $response;

        return $this;
    }
}
