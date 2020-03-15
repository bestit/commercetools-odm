<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\Tests\UnitOfWork\ResponseHandlers;

use BestIt\CommercetoolsODM\Exception\ConflictException;
use BestIt\CommercetoolsODM\Exception\ResponseException;
use BestIt\CommercetoolsODM\UnitOfWork\ConflictProcessorInterface;
use BestIt\CommercetoolsODM\UnitOfWork\ResponseHandlers\ConflictResponseHandler;
use BestIt\CommercetoolsODM\UnitOfWorkInterface;
use Commercetools\Core\Model\Product\Product;
use Commercetools\Core\Request\ClientRequestInterface;
use Commercetools\Core\Response\ApiResponseInterface;
use Commercetools\Core\Response\ErrorResponse;
use PHPUnit\Framework\MockObject\MockObject;
use function uniqid;

/**
 * Class ConflictResponseHandlerTest
 *
 * @author blange <bjoern.lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\Tests\UnitOfWork\ResponseHandlers
 */
class ConflictResponseHandlerTest extends ResponseHandlerTestCase
{
    /**
     * The tested class.
     *
     * @var ConflictResponseHandler|null
     */
    protected $fixture;

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
            ->willReturn(409);

        return $response;
    }

    /**
     * Prepares the usual use case that an object needs to be fetched from the unit of work out of the request.
     *
     * @return array
     */
    private function prepareUnitOfWorkWithObject(): array
    {
        $this->documentManager
            ->expects(static::once())
            ->method('getUnitOfWork')
            ->willReturn($uow = $this->createMock(UnitOfWorkInterface::class));

        $uow
            ->method('tryGetById')
            ->with($requestId = uniqid())
            ->willReturn($product = new Product([]));

        $response = $this->getMatchingResponse();

        $response
            ->expects(static::once())
            ->method('getRequest')
            ->willReturn($request = $this->createMock(ClientRequestInterface::class));

        $request
            ->expects(static::once())
            ->method('getIdentifier')
            ->willReturn($requestId);

        return [$product, $response, $uow];
    }

    /**
     * Sets up the test.
     *
     * @return void
     */
    protected function setUp()
    {
        $this->fixture = new ConflictResponseHandler($this->loadDocumentManager());
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
     * Checks if the processor gets returned.
     *
     * @return void
     */
    public function testGetConflictProcessor()
    {
        static::assertInstanceOf(
            ConflictProcessorInterface::class,
            $this->fixture->getConflictProcessor()
        );
    }

    /**
     * Checks if an exception is thrown if there are no change callbacks.
     *
     * @throws ResponseException
     *
     * @return void
     */
    public function testHandleResponseNoCallbackWithException()
    {
        static::expectException(ConflictException::class);

        list($product, $response, $uow) = $this->prepareUnitOfWorkWithObject();

        $uow
            ->method('hasModifyCallbacks')
            ->with($product)
            ->willReturn(false);

        $this->fixture->handleResponse($response);
    }

    /**
     * Checks if an exception is thrown if a retry is not allowed.
     *
     * @throws ResponseException
     *
     * @return void
     */
    public function testHandleResponseNoRetryWithException()
    {
        static::expectException(ConflictException::class);

        list(, $response, $uow) = $this->prepareUnitOfWorkWithObject();

        $uow
            ->method('canRetry')
            ->willReturn(false);

        $this->fixture->handleResponse($response);
    }

    /**
     * Checks if an exception is thrown if a retry is not allowed.
     *
     * @throws ResponseException
     *
     * @return void
     */
    public function testHandleResponseWithRetry()
    {
        list($product, $response, $uow) = $this->prepareUnitOfWorkWithObject();

        $this->fixture->setConflictProcessor($processor = $this->createMock(ConflictProcessorInterface::class));

        $processor
            ->expects(static::once())
            ->method('handleConflict')
            ->with($product);

        $uow
            ->expects(static::once())
            ->method('canRetry')
            ->willReturn(true);

        $uow
            ->expects(static::once())
            ->method('hasModifyCallbacks')
            ->with($product)
            ->willReturn(true);

        $uow
            ->expects(static::once())
            ->method('runModifyCallbacks')
            ->with($product)
            ->willReturn($product);

        $this->fixture->handleResponse($response);
    }

    /**
     * Checks if the conflict processor can be used.
     *
     * @return void
     */
    public function testSetAndGetConflictProcessor()
    {
        static::assertSame(
            $this->fixture,
            $this->fixture->setConflictProcessor($processor = $this->createMock(ConflictProcessorInterface::class))
        );

        static::assertSame($processor, $this->fixture->getConflictProcessor());
    }
}
