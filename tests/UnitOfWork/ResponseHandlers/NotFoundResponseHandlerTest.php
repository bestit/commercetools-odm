<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\Tests\UnitOfWork\ResponseHandlers;

use BestIt\CommercetoolsODM\Exception\APIException;
use BestIt\CommercetoolsODM\Exception\NotFoundException;
use BestIt\CommercetoolsODM\Exception\ResponseException;
use BestIt\CommercetoolsODM\UnitOfWork\ResponseHandlers\NotFoundResponseHandler;
use Commercetools\Core\Response\ErrorResponse;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * Class NotFoundResponseHandlerTest
 *
 * @author blange <bjoern.lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\Tests\UnitOfWork\ResponseHandlers
 */
class NotFoundResponseHandlerTest extends ResponseHandlerTestCase
{
    /**
     * Sets up the test.
     *
     * @return void
     */
    protected function setUp()
    {
        $this->fixture = new NotFoundResponseHandler($this->loadDocumentManager());
    }

    /**
     * Returns a matching response for this handler.
     *
     * @return MockObject
     */
    private function getMatchingResponse(): MockObject
    {
        $response = $this->createMock(ErrorResponse::class);

        $response
            ->method('getStatusCode')
            ->willReturn(404);

        return $response;
    }

    /**
     * Checks the default return of the method.
     *
     * @return void
     */
    public function testCanHandleResponseDefault()
    {
        $this->checkExcludeOfDefaultResponse();
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
        static::expectException(NotFoundException::class);

        $this->fixture->handleResponse($this->createMock(ErrorResponse::class));
    }
}
