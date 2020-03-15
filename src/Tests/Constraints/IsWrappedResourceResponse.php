<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\Tests\Constraints;

use Commercetools\Core\Response\AbstractApiResponse;
use PHPUnit\Framework\Constraint\Constraint;
use Psr\Http\Message\ResponseInterface;

/**
 * Checks if the given response is wrapped in the ct resource response.
 *
 * @author blange <bjoern.lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\Tests\Constraints
 */
class IsWrappedResourceResponse extends Constraint
{
    /**
     * The checked response.
     *
     * @var ResponseInterface
     */
    private $response;

    /**
     * IsWrappedResourceResponse constructor.
     *
     * @param ResponseInterface $response
     */
    public function __construct(ResponseInterface $response)
    {
        parent::__construct();

        $this->response = $response;
    }

    /**
     * Evaluates the constraint for parameter $other. Returns true if the
     * constraint is met, false otherwise.
     *
     * @param AbstractApiResponse $response
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
     *
     * @return bool
     */
    protected function matches($response): bool
    {
        return $response->getResponse() === $this->response;
    }

    /**
     * Returns a simple marker for checking the error.
     *
     * @return string
     */
    public function toString(): string
    {
        return 'Response does not contain ' . get_class($this->response);
    }
}
