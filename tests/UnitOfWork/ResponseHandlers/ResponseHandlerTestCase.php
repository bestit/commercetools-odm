<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\Tests\UnitOfWork\ResponseHandlers;

use BestIt\CommercetoolsODM\DocumentManagerInterface;
use BestIt\CommercetoolsODM\UnitOfWork\ResponseHandlers\ResponseHandlerInterface;
use Commercetools\Core\Response\ApiResponseInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerAwareInterface;

/**
 * Tests for the basic calls.
 *
 * @author blange <bjoern.lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\Tests\UnitOfWork\ResponseHandlers
 */
class ResponseHandlerTestCase extends TestCase
{
    /**
     * The injected document handler.
     *
     * @var MockObject|null|DocumentManagerInterface
     */
    protected $documentManager;

    /**
     * The tested class.
     *
     * @var ResponseHandlerInterface|null
     */
    protected $fixture;

    /**
     * Checks the default return of the method.
     *
     * @return void
     */
    protected function checkExcludeOfDefaultResponse()
    {
        static::assertFalse($this->fixture->canHandleResponse($this->createMock(ApiResponseInterface::class)));
    }

    /**
     * Return a fresh document manager.
     *
     * @return MockObject
     */
    protected function loadDocumentManager(): MockObject
    {
        return $this->documentManager = $this->createMock(DocumentManagerInterface::class);
    }

    /**
     * Checks if the required interface is implemented.
     *
     * @return void
     */
    public function testInterface()
    {
        static::assertInstanceOf(ResponseHandlerInterface::class, $this->fixture);
        static::assertInstanceOf(LoggerAwareInterface::class, $this->fixture);
    }
}
