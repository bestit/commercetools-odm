<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\Tests\UnitOfWork\ResponseHandlers;

use BestIt\CommercetoolsODM\UnitOfWork\ResponseHandlers\ResponseHandlerAbstract;
use Commercetools\Core\Response\ApiResponseInterface;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * Class ResponseHandlerAbstractTest
 *
 * @author blange <bjoern.lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\Tests\UnitOfWork\ResponseHandlers
 */
class ResponseHandlerAbstractTest extends ResponseHandlerTestCase
{
    /**
     * The tested class.
     *
     * @var MockObject|null|ResponseHandlerAbstract
     */
    protected $fixture;

    /**
     * Sets up the test.
     *
     * @return void
     */
    protected function setUp()
    {
        $this->fixture = $this->getMockForAbstractClass(
            ResponseHandlerAbstract::class,
            [
                $this->loadDocumentManager(),
            ]
        );
    }

    /**
     * Checks the default return of the method.
     *
     * @return void
     */
    public function testCanHandleResponse()
    {
        static::assertFalse($this->fixture->canHandleResponse($this->createMock(ApiResponseInterface::class)));
    }
}
