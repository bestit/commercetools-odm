<?php

namespace BestIt\CommercetoolsODM\Tests\Repository;

use BestIt\CommercetoolsODM\DocumentManagerInterface;
use BestIt\CommercetoolsODM\Exception\APIException;
use BestIt\CommercetoolsODM\Filter\FilterManagerInterface;
use BestIt\CommercetoolsODM\Helper\GeneratorQueryHelper;
use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use BestIt\CommercetoolsODM\Repository\CustomerRepository;
use BestIt\CommercetoolsODM\UnitOfWork;
use Commercetools\Commons\Helper\QueryHelper;
use Commercetools\Core\Client;
use Commercetools\Core\Model\Customer\Customer;
use Commercetools\Core\Request\AbstractUpdateRequest;
use Commercetools\Core\Request\Customers\CustomerPasswordChangeRequest;
use Commercetools\Core\Response\ApiResponseInterface;
use Commercetools\Core\Response\ErrorResponse;
use PHPUnit\Framework\TestCase;

/**
 * Class CustomerRepositoryTest
 *
 * @author hardeweg <nils.hardeweg@bestit-online.de>
 * @package BestIt\CommercetoolsODM\Tests\Repository
 */
class CustomerRepositoryTest extends TestCase
{
    use TestRepositoryTrait;

    /**
     * Returns the class name for the repository.
     *
     * @return string
     */
    protected function getRepositoryClass(): string
    {
        return CustomerRepository::class;
    }

    /**
     * Tests the updatePasswordOnly method
     *
     * @return void
     */
    public function testUpdatePasswordOnly()
    {
        $responseMock = $this->createMock(ApiResponseInterface::class);
        $responseMock->method('getStatusCode')->willReturn(200);

        $requestMock = $this->createMock(AbstractUpdateRequest::class);
        $requestMock->method('mapResponse')->with($responseMock)->willReturn(['mapped' => 'Response']);

        $clientMock = $this->createMock(Client::class);
        $clientMock->method('execute')->with($requestMock)->willReturn($responseMock);

        $unitOfWorkMock = $this->createMock(UnitOfWork::class);
        $unitOfWorkMock
            ->method('createDocument')
            ->with(CustomerRepository::class, ['mapped' => 'Response'])
            ->willReturn('returnVal');

        $documentManagerMock = $this->createMock(DocumentManagerInterface::class);
        $documentManagerMock
            ->method('createRequest')
            ->with(Customer::class, CustomerPasswordChangeRequest::class, 'id', 4, 'currentPW', 'newPW')
            ->willReturn($requestMock);
        $documentManagerMock->method('getClient')->willReturn($clientMock);
        $documentManagerMock->method('getUnitOfWork')->willReturn($unitOfWorkMock);

        $metaDataMock = $this->createMock(ClassMetadataInterface::class);
        $metaDataMock->method('getName')->willReturn(CustomerRepository::class);

        $customerRepo = new CustomerRepository(
            $metaDataMock,
            $documentManagerMock,
            $this->createMock(QueryHelper::class),
            $this->createMock(GeneratorQueryHelper::class),
            $this->createMock(FilterManagerInterface::class)
        );

        static::assertEquals(
            'returnVal',
            $customerRepo->updateOnlyPassword('id', 4, 'currentPW', 'newPW')
        );
    }

    /**
     * Tests the updatePasswordOnly method with a not found response
     *
     * @return void
     */
    public function testUpdatePasswordOnlyWithNotFoundResponse()
    {
        $responseMock = $this->createMock(ApiResponseInterface::class);
        $responseMock->method('getStatusCode')->willReturn(404);

        $requestMock = $this->createMock(AbstractUpdateRequest::class);
        $requestMock->method('mapResponse')->with($responseMock)->willReturn(['mapped' => 'Response']);

        $clientMock = $this->createMock(Client::class);
        $clientMock->method('execute')->with($requestMock)->willReturn($responseMock);

        $unitOfWorkMock = $this->createMock(UnitOfWork::class);
        $unitOfWorkMock
            ->method('createDocument')
            ->with(CustomerRepository::class, ['mapped' => 'Response'])
            ->willReturn('returnVal');

        $documentManagerMock = $this->createMock(DocumentManagerInterface::class);
        $documentManagerMock
            ->method('createRequest')
            ->with(Customer::class, CustomerPasswordChangeRequest::class, 'id', 4, 'currentPW', 'newPW')
            ->willReturn($requestMock);
        $documentManagerMock->method('getClient')->willReturn($clientMock);
        $documentManagerMock->method('getUnitOfWork')->willReturn($unitOfWorkMock);

        $metaDataMock = $this->createMock(ClassMetadataInterface::class);
        $metaDataMock->method('getName')->willReturn(CustomerRepository::class);

        $customerRepo = new CustomerRepository(
            $metaDataMock,
            $documentManagerMock,
            $this->createMock(QueryHelper::class),
            $this->createMock(GeneratorQueryHelper::class),
            $this->createMock(FilterManagerInterface::class)
        );

        static::assertEquals(
            null,
            $customerRepo->updateOnlyPassword('id', 4, 'currentPW', 'newPW')
        );
    }

    /**
     * Tests the updatePasswordOnly method with a not found response
     *
     * @return void
     */
    public function testUpdatePasswordOnlyWithErrorResponse()
    {
        $this->expectException(APIException::class);

        $responseMock = $this->createMock(ErrorResponse::class);
        $responseMock->method('getStatusCode')->willReturn(200);
        $responseMock->method('getMessage')->willReturn('errorMessage');
        $responseMock->method('getCorrelationId')->willReturn('correlationId');

        $requestMock = $this->createMock(AbstractUpdateRequest::class);
        $requestMock->method('mapResponse')->with($responseMock)->willReturn(['mapped' => 'Response']);

        $clientMock = $this->createMock(Client::class);
        $clientMock->method('execute')->with($requestMock)->willReturn($responseMock);

        $unitOfWorkMock = $this->createMock(UnitOfWork::class);
        $unitOfWorkMock
            ->method('createDocument')
            ->with(CustomerRepository::class, ['mapped' => 'Response'])
            ->willReturn('returnVal');

        $documentManagerMock = $this->createMock(DocumentManagerInterface::class);
        $documentManagerMock
            ->method('createRequest')
            ->with(Customer::class, CustomerPasswordChangeRequest::class, 'id', 4, 'currentPW', 'newPW')
            ->willReturn($requestMock);
        $documentManagerMock->method('getClient')->willReturn($clientMock);
        $documentManagerMock->method('getUnitOfWork')->willReturn($unitOfWorkMock);

        $metaDataMock = $this->createMock(ClassMetadataInterface::class);
        $metaDataMock->method('getName')->willReturn(CustomerRepository::class);

        $customerRepo = new CustomerRepository(
            $metaDataMock,
            $documentManagerMock,
            $this->createMock(QueryHelper::class),
            $this->createMock(GeneratorQueryHelper::class),
            $this->createMock(FilterManagerInterface::class)
        );

        static::assertEquals(
            null,
            $customerRepo->updateOnlyPassword('id', 4, 'currentPW', 'newPW')
        );
    }
}
