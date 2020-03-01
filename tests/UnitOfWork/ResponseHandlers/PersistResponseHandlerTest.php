<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\Tests\UnitOfWork\ResponseHandlers;

use BestIt\CommercetoolsODM\Events;
use BestIt\CommercetoolsODM\Exception\ResponseException;
use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use BestIt\CommercetoolsODM\UnitOfWork\ResponseHandlers\PersistResponseHandler;
use BestIt\CommercetoolsODM\UnitOfWorkInterface;
use Commercetools\Core\Model\Customer\Customer;
use Commercetools\Core\Model\Customer\CustomerSigninResult;
use Commercetools\Core\Request\AbstractUpdateByKeyRequest;
use Commercetools\Core\Response\ApiResponseInterface;
use PHPUnit\Framework\MockObject\MockObject;
use function uniqid;

/**
 * Class PersistResponseHandlerTest
 *
 * @author blange <bjoern.lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\Tests\UnitOfWork\ResponseHandlers
 */
class PersistResponseHandlerTest extends ResponseHandlerTestCase
{
    /**
     * Checks how the responses are handled?
     *
     * @return array
     */
    public function getResponseAsserts(): array
    {
        return [
            'unregistered-standard' => [true],
            'unregistered-custom' => [true, true],
            'registered-standard' => [false],
            'registered-custom' => [false, true],
            'unregistered-signin-standard' => [true, false, true],
            'unregistered-signin-custom' => [true, true, true],
            'registered-signin-standard' => [false, false, true],
            'registered-signin-custom' => [false, true, true],
        ];
    }

    /**
     * Returns status codes to test for this response handler.
     *
     * @return array
     */
    public function getStatusCodesCodes(): array
    {
        return [
            [200, true],
            [201, true],
            [300],
            ['200'],
            ['201'],
        ];
    }

    /**
     * Sets up the test.
     *
     * @return void
     */
    protected function setUp()
    {
        $this->fixture = new PersistResponseHandler($this->loadDocumentManager());
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
                $request = $this->createMock(AbstractUpdateByKeyRequest::class)
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
     * @dataProvider getStatusCodesCodes
     *
     * @param int|string $statusCode
     * @param bool $success
     *
     * @return void
     */
    public function testCanHandleResponse($statusCode, bool $success = false)
    {
        list($response) = $this->getMatchingResponse();

        $response
            ->expects(static::once())
            ->method('getStatusCode')
            ->willReturn($statusCode);

        static::assertSame($success, $this->fixture->canHandleResponse($response));
    }

    /**
     * Checks if the response is handled fully.
     *
     * @dataProvider getResponseAsserts
     * @throws ResponseException
     *
     * @param bool $withUnregisteredObject
     * @param bool $handleAsCustomObject
     * @param bool $injectCustomerSignIn
     *
     * @return void
     */
    public function testHandleResponse(
        bool $withUnregisteredObject = false,
        bool $handleAsCustomObject = false,
        bool $injectCustomerSignIn = false
    ) {
        $originalObject = Customer::fromArray([
            'id' => $objectId = uniqid(),
            'version' => $version = 5
        ]);

        list($response, $request) = $this->getMatchingResponse();

        $request
            ->expects(static::once())
            ->method('getIdentifier')
            ->willReturn($objectId);

        if (!$injectCustomerSignIn) {
            $response
                ->expects(static::once())
                ->method('toObject')
                ->willReturn($update = Customer::fromArray([
                    'id' => $updatedId = uniqid(),
                    'version' => $updatedVersion = $version + 1
                ]));
        } else {
            $response
                ->expects(static::once())
                ->method('toObject')
                ->willReturn($signInResult = new CustomerSigninResult());

            $signInResult->setCustomer(
                $update = Customer::fromArray([
                    'id' => $updatedId = uniqid(),
                    'version' => $updatedVersion = $version + 1
                ])
            );
        }

        $this->documentManager
            ->expects(static::once())
            ->method('getUnitOfWork')
            ->willReturn($uow = $this->createMock(UnitOfWorkInterface::class));

        if ($withUnregisteredObject) {
            $usedObject = $update;

            $this->documentManager
                ->expects(static::never())
                ->method('getClassMetadata')
                ->with($originalObject)
                ->willReturn($metadata = $this->createMock(ClassMetadataInterface::class));

            $this->documentManager
                ->expects(static::never())
                ->method('refresh')
                ->with($originalObject, $update);
        } else {
            $usedObject = $originalObject;

            $this->documentManager
                ->expects(static::once())
                ->method('getClassMetadata')
                ->with(get_class($originalObject))
                ->willReturn($metadata = $this->createMock(ClassMetadataInterface::class));

            $this->documentManager
                ->expects(static::once())
                ->method('refresh')
                ->with($originalObject, $update);

            $metadata
                ->method('getIdentifier')
                ->willReturn('id');

            $metadata
                ->method('getVersion')
                ->willReturn('version');

            $metadata
                ->expects(static::once())
                ->method('isCTStandardModel')
                ->willReturn(!$handleAsCustomObject);
        }

        $this->documentManager
            ->expects(static::once())
            ->method('merge')
            ->with($usedObject);

        $uow
            ->expects(static::once())
            ->method('tryGetById')
            ->with($objectId)
            ->willReturn($withUnregisteredObject ? null : $originalObject);

        $uow
            ->expects(static::once())
            ->method('invokeLifecycleEvents')
            ->with($usedObject, Events::POST_PERSIST);

        $uow
            ->expects(static::once())
            ->method('processDeferredDetach')
            ->with($usedObject);

        $this->fixture->handleResponse($response);

        if (!$withUnregisteredObject) {
            if ($handleAsCustomObject) {
                static::assertSame($updatedId, $originalObject->getId(), 'The id should be updated');
                static::assertSame($updatedVersion, $originalObject->getVersion(), 'The version should be updated.');
            } else {
                static::assertSame($objectId, $originalObject->getId(), 'The id should not be updated');
                static::assertSame($version, $originalObject->getVersion(), 'The version should not be updated.');
            }
        }
    }
}
