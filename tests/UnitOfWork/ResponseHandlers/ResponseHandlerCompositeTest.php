<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\Tests\UnitOfWork\ResponseHandlers;

use BestIt\CommercetoolsODM\Exception\ResponseException;
use BestIt\CommercetoolsODM\UnitOfWork\ResponseHandlers\ResponseHandlerComposite;
use BestIt\CommercetoolsODM\UnitOfWork\ResponseHandlers\ResponseHandlerInterface;
use Commercetools\Core\Response\ApiResponseInterface;
use Commercetools\Core\Response\ErrorResponse;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Log\NullLogger;

/**
 * Class ResponseHandlerCompositeTest
 *
 * @author blange <bjoern.lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\Tests\UnitOfWork\ResponseHandlers
 */
class ResponseHandlerCompositeTest extends ResponseHandlerTestCase
{
    /**
     * The tested class.
     *
     * @var ResponseHandlerComposite|null
     */
    protected $fixture;

    /**
     * Returns basic asserts for the status code handling.
     *
     * @return array
     */
    public function getStatusAsserts(): array
    {
        return [
            'ok' => [200, ApiResponseInterface::class],
            'created' => [201, ApiResponseInterface::class],
            'moved' => [307, ApiResponseInterface::class],
            'category-not-removed' => [422, ErrorResponse::class, 'Cannot remove from category'],
            'not-found' => [404, ErrorResponse::class],
            'conflict' => [409, ErrorResponse::class],
            'default-error' => [418, ErrorResponse::class],
        ];
    }

    /**
     * Sets up the test.
     *
     * @return void
     */
    protected function setUp()
    {
        $this->fixture = new ResponseHandlerComposite($this->loadDocumentManager());
    }

    /**
     * Checks if a handler can be added.
     *
     * @return MockObject
     */
    public function testAddChildHandler(): MockObject
    {
        $handler = $this->createMock(ResponseHandlerInterface::class);

        $handler
            ->expects(static::once())
            ->method('setLogger')
            ->with($logger = new NullLogger());

        $this->fixture->setLogger($logger);

        static::assertSame(
            $this->fixture,
            $this->fixture->addChildHandler($handler)
        );

        static::assertSame(1, count($this->fixture));

        return $handler;
    }

    /**
     * Checks if the loaded child handlers are used.
     *
     * @dataProvider getStatusAsserts
     * @todo Not Perfect and should be refactored to a factory.
     *
     * @param int $statusCode
     * @param string $responseClass
     * @param string $errorMessage
     *
     * @return void
     */
    public function testCanHandleResponseWithChildHandlers(
        int $statusCode,
        string $responseClass = '',
        string $errorMessage = ''
    ) {
        if (!$responseClass) {
            $responseClass = ApiResponseInterface::class;
        }

        $response = $this->createMock($responseClass);

        $response
            ->method('getStatusCode')
            ->willReturn($statusCode);

        static::assertTrue($this->fixture->canHandleResponse($response));
    }

    /**
     * Checks the default return.
     *
     * @return void
     */
    public function testCountDefault()
    {
        static::assertSame(0, count($this->fixture));
    }

    /**
     * Checks if the call is delegated to the registered handler.
     *
     * @throws ResponseException
     *
     * @return void
     */
    public function testHandleResponseWithMock()
    {
        $handler = $this->testAddChildHandler();

        $handler
            ->expects(static::once())
            ->method('canHandleResponse')
            ->with($response = $this->createMock(ApiResponseInterface::class));

        $handler
            ->expects(static::once())
            ->method('canHandleResponse')
            ->with($response);

        $this->fixture->handleResponse($response);
    }
}
