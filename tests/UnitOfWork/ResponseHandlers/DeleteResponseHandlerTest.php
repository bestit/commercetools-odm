<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\Tests\UnitOfWork\ResponseHandlers;

use BestIt\CommercetoolsODM\Events;
use BestIt\CommercetoolsODM\Exception\ResponseException;
use BestIt\CommercetoolsODM\UnitOfWork\ResponseHandlers\DeleteResponseHandler;
use BestIt\CommercetoolsODM\UnitOfWorkInterface;
use Commercetools\Core\Model\Product\Product;
use Commercetools\Core\Request\AbstractDeleteRequest;
use Commercetools\Core\Response\ApiResponseInterface;
use PHPUnit\Framework\MockObject\MockObject;
use function uniqid;

/**
 * Class DeleteResponseHandlerTest
 *
 * @author blange <bjoern.lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\Tests\UnitOfWork\ResponseHandlers
 */
class DeleteResponseHandlerTest extends ResponseHandlerTestCase
{
    /**
     * Sets up the test.
     *
     * @return void
     */
    protected function setUp()
    {
        $this->fixture = new DeleteResponseHandler($this->loadDocumentManager());
    }

    /**
     * Returns a matching response for this handler.
     *
     * @return MockObject[] The first element is the response and the second one is the request.
     */
    private function getMatchingResponse()
    {
        $response = $this->createMock(ApiResponseInterface::class);

        $response
            ->expects(static::once())
            ->method('getRequest')
            ->willReturn(
                $request = $this->createMock(AbstractDeleteRequest::class)
            );

        return [$response, $request];
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
        list($response) = $this->getMatchingResponse();

        static::assertTrue($this->fixture->canHandleResponse($response));
    }

    /**
     * Checks the full call of the response handling.
     *
     * @throws ResponseException
     *
     * @return void
     */
    public function testHandleResponse()
    {
        list($response, $request) = $this->getMatchingResponse();

        $request
            ->expects(static::once())
            ->method('getIdentifier')
            ->willReturn($productId = uniqid());

        $this->documentManager
            ->expects(static::once())
            ->method('detach')
            ->with($product = new Product([]));

        $this->documentManager
            ->expects(static::once())
            ->method('getUnitOfWork')
            ->willReturn($uow = $this->createMock(UnitOfWorkInterface::class));

        $uow
            ->expects(static::once())
            ->method('invokeLifecycleEvents')
            ->with(
                $product,
                Events::POST_REMOVE
            );

        $uow
            ->expects(static::once())
            ->method('tryGetById')
            ->with($productId)
            ->willReturn($product);

        $this->fixture->handleResponse($response);
    }
}
