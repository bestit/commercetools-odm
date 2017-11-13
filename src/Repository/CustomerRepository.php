<?php

namespace BestIt\CommercetoolsODM\Repository;

use BestIt\CommercetoolsODM\Exception\APIException;
use BestIt\CommercetoolsODM\Exception\ResponseException;
use BestIt\CommercetoolsODM\Model\DefaultRepository;
use BestIt\CommercetoolsODM\RepositoryAwareInterface;
use Commercetools\Core\Model\Customer\Customer;
use Commercetools\Core\Model\Customer\CustomerToken;
use Commercetools\Core\Request\Customers\CustomerByTokenGetRequest;
use Commercetools\Core\Request\Customers\CustomerPasswordChangeRequest;
use Commercetools\Core\Request\Customers\CustomerPasswordTokenRequest;
use Commercetools\Core\Response\ErrorResponse;
use InvalidArgumentException;

/**
 * Class CustomerRepository
 * @author blange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM
 * @subpackage Repository
 * @version $id$
 */
class CustomerRepository extends DefaultRepository
{
    /**
     * Returns the value of the customer token.
     * @param string $email
     * @return string
     * @throws ResponseException
     */
    public function createPasswordTokenByMail(string $email): string
    {
        /** @var CustomerToken $token */
        list($token, $rawResponse) = $this->processQuery(CustomerPasswordTokenRequest::ofEmail($email));

        if ($rawResponse instanceof ErrorResponse) {
            throw APIException::fromResponse($rawResponse);
        }

        return $token->getValue();
    }

    /**
     * Finds the customer by its password token.
     * @param string $token
     * @return Customer|null
     * @throws ResponseException
     */
    public function findByPasswordToken(string $token)
    {
        $documentManager = $this->getDocumentManager();

        $request = $documentManager->createRequest(
            Customer::class,
            CustomerByTokenGetRequest::class,
            $token
        );

        $return = null;
        list($customer, $rawResponse) = $this->processQuery($request);

        if ($rawResponse->getStatusCode() !== 404) {
            if ($rawResponse instanceof ErrorResponse) {
                throw APIException::fromResponse($rawResponse);
            }

            $return = $documentManager->getUnitOfWork()->createDocument($this->getClassName(), $customer);

            if ($return instanceof RepositoryAwareInterface) {
                $return->setRepository($this);
            }
        }

        return $return;
    }

    /**
     * Updates the password of the user with the given user id
     *
     * @param string $customerId
     * @param int $customerVersion
     * @param string $currentPassword
     * @param string $newPassword
     *
     * @return Customer|null
     * @throws InvalidArgumentException
     * @throws ResponseException
     */
    public function updateOnlyPassword(
        string $customerId,
        int $customerVersion,
        string $currentPassword,
        string $newPassword
    ) {
        $documentManager = $this->getDocumentManager();

        $request = $documentManager->createRequest(
            Customer::class,
            CustomerPasswordChangeRequest::class,
            $customerId,
            $customerVersion,
            $currentPassword,
            $newPassword
        );

        $return = null;
        list($customer, $rawResponse) = $this->processQuery($request);

        if ($rawResponse->getStatusCode() !== 404) {
            if ($rawResponse instanceof ErrorResponse) {
                throw APIException::fromResponse($rawResponse);
            }

            $return = $documentManager->getUnitOfWork()->createDocument($this->getClassName(), $customer);

            if ($return instanceof RepositoryAwareInterface) {
                $return->setRepository($this);
            }
        }

        return $return;
    }
}
