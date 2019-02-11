<?php

namespace BestIt\CommercetoolsODM\Exception;

use Commercetools\Core\Response\ApiResponseInterface;
use Commercetools\Core\Response\ErrorResponse;

/**
 * Helps with responses in the exceptions.
 *
 * @author blange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\Exception
 * @subpackage Exception
 */
trait ResponseAwareTrait
{
    /**
     * The correlation id for the request.
     *
     * @var string|null
     */
    private $correlationId;

    /**
     * The response.
     *
     * @var ErrorResponse
     */
    private $response = null;

    /**
     * Returns the correlation id for the request.
     *
     * @return string|null
     */
    public function getCorrelationId()
    {
        return $this->correlationId;
    }

    /**
     * Returns the used response.
     *
     * @return ApiResponseInterface
     */
    public function getResponse(): ApiResponseInterface
    {
        return $this->response;
    }

    /**
     * Sets the correlation id for the request.
     *
     * @param string $correlationId
     * @phpcsSuppress BestIt.TypeHints.ReturnTypeDeclaration.MissingReturnTypeHint
     *
     * @return ResponseAwareTrait
     */
    public function setCorrelationId(string $correlationId)
    {
        $this->correlationId = $correlationId;

        return $this;
    }

    /**
     * Sets the response.
     *
     * @param ErrorResponse $response
     * @phpcsSuppress BestIt.TypeHints.ReturnTypeDeclaration.MissingReturnTypeHint
     *
     * @return $this
     */
    public function setResponse(ErrorResponse $response)
    {
        $this->response = $response;

        $correlationId = $response->getCorrelationId();

        if ($correlationId) {
            $this->setCorrelationId($correlationId);
        }

        return $this;
    }
}
