<?php

namespace BestIt\CommercetoolsODM\Repository;

use BestIt\CommercetoolsODM\DocumentManagerInterface;
use BestIt\CommercetoolsODM\Exception\APIException;
use BestIt\CommercetoolsODM\Model\DefaultRepository;
use Commercetools\Core\Response\ApiResponseInterface;

/**
 * Repository for the carts.
 * @author chowanski <chowanski@bestit-online.de>
 * @package BestIt\CommercetoolsODM
 * @subpackage Repository
 * @version $id$
 */
class CartRepository extends DefaultRepository
{
    /**
     * Returns a cart object or null by customer id.
     * @param string $id
     * @return mixed
     * @throws APIException
     */
    public function findByCustomerId(string $id)
    {
        /** @var DocumentManagerInterface $documentManager */
        $documentManager = $this->getDocumentManager();
        $uow = $documentManager->getUnitOfWork();

        $document = $uow->tryGetByCustomerId($id);

        if (!$document) {
            $request = $documentManager->createRequest(
                $this->getClassName(),
                DocumentManagerInterface::REQUEST_TYPE_FIND_BY_CUSTOMER_ID,
                $id
            );

            /** @var ApiResponseInterface $rawResponse */
            list ($response, $rawResponse) = $this->processQuery($request);

            if ($rawResponse->getStatusCode() !== 404) {
                if ($rawResponse->isError()) {
                    throw APIException::fromResponse($rawResponse);
                }

                $document = $uow->createDocument($this->getClassName(), $response, []);
            }
        }

        return $document;
    }
}
