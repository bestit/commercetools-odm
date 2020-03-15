<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\Tests\UnitOfWork\ResponseHandlers;

use BestIt\CommercetoolsODM\Exception\ResponseException;
use BestIt\CommercetoolsODM\UnitOfWork\ResponseHandlers\DefaultResponseHandler;
use Commercetools\Core\Request\AbstractDeleteRequest;
use Commercetools\Core\Response\ApiResponseInterface;
use Commercetools\Core\Response\ErrorResponse;
use DomainException;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * Class DefaultResponseHandlerTest
 *
 * @author blange <bjoern.lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\Tests\UnitOfWork\ResponseHandlers
 */
class DefaultResponseHandlerTest extends ResponseHandlerTestCase
{
    /**
     * Returns a matching response for this handler.
     *
     * @return MockObject
     */
    private function getMatchingResponse(): MockObject
    {
        return $this->createMock(ApiResponseInterface::class);
    }

    /**
     * Sets up the test.
     *
     * @return void
     */
    protected function setUp()
    {
        $this->fixture = new DefaultResponseHandler($this->loadDocumentManager());
    }

    /**
     * Checks the default return of the method.
     *
     * @return void
     */
    public function testCanHandleResponseFalseExcludeDeletes()
    {
        $response = $this->createMock(ApiResponseInterface::class);

        $response
            ->expects(static::once())
            ->method('getRequest')
            ->willReturn($this->createMock(AbstractDeleteRequest::class));

        static::assertFalse($this->fixture->canHandleResponse($response));
    }

    /**
     * Checks the default return of the method.
     *
     * @return void
     */
    public function testCanHandleResponseFalseExcludeErrors()
    {
        static::assertFalse($this->fixture->canHandleResponse($this->createMock(ErrorResponse::class)));
    }

    /**
     * Checks if the 409 is handled.
     *
     * @return void
     */
    public function testCanHandleResponseSuccess()
    {
        $response = $this->getMatchingResponse();

        static::assertTrue($this->fixture->canHandleResponse($response));
    }

    /**
     * Checks if an exception is thrown.
     *
     * @throws ResponseException
     *
     * @return void
     */
    public function testHandleResponse()
    {
        static::expectException(DomainException::class);

        $this->fixture->handleResponse($this->createMock(ErrorResponse::class));
    }
}
