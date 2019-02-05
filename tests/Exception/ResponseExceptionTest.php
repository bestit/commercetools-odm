<?php

namespace BestIt\CommercetoolsODM\Tests\Exception;

use BestIt\CommercetoolsODM\Exception\ResponseAwareTrait;
use BestIt\CommercetoolsODM\Exception\ResponseException;
use Commercetools\Core\Request\ClientRequestInterface;
use Commercetools\Core\Response\ErrorResponse;
use Exception;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

/**
 * Tests ResponseException
 *
 * @author blange <bjoern.lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\Tests\Exception
 */
class ResponseExceptionTest extends TestCase
{
    /**
     * The tested class.
     *
     * @var ResponseException
     */
    private $fixture = null;

    /**
     * Sets up the test.
     *
     * @return void
     */
    public function setUp()
    {
        $this->fixture = new ResponseException(uniqid(), mt_rand(1, 10000));
    }

    /**
     * Checks the building method.
     *
     * @return void
     */
    public function testFromResponse()
    {
        $response = static::createMock(ErrorResponse::class);

        $response
            ->expects(static::once())
            ->method('getCorrelationId')
            ->willReturn($correlationId = uniqid());

        $response
            ->expects(static::once())
            ->method('getMessage')
            ->willReturn($message = uniqid());

        $response
            ->expects(static::once())
            ->method('getStatusCode')
            ->willReturn($code = mt_rand(1, 10000));

        static::assertInstanceOf(
            ResponseException::class,
            $exception = ResponseException::fromResponse($response),
            'Wrong type.'
        );

        static::assertSame($response, $exception->getResponse(), 'Wrong response');
        static::assertSame($message, $exception->getMessage(), 'Wrong message');
        static::assertSame($code, $exception->getCode(), 'Wrong code');
        static::assertSame($correlationId, $exception->getCorrelationId(), 'Wrong correlation id');
    }

    /**
     * Tests the instance.
     *
     * @return void
     */
    public function testInstance()
    {
        static::assertInstanceOf(ResponseException::class, $this->fixture);
    }

    /**
     * Checks if the required traits are used.
     *
     * @return void
     */
    public function testTraits()
    {
        static::assertContains(ResponseAwareTrait::class, class_uses($this->fixture));
    }
}
