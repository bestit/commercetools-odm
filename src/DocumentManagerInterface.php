<?php

namespace BestIt\CommercetoolsODM;

use Commercetools\Core\Client;
use Commercetools\Core\Request\AbstractApiRequest;
use Commercetools\Core\Request\AbstractByIdGetRequest;
use Commercetools\Core\Request\AbstractCreateRequest;
use Commercetools\Core\Request\AbstractDeleteRequest;
use Commercetools\Core\Request\AbstractUpdateRequest;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * The public api for the document manager for commercetools.
 * @author lange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM
 * @version $id$
 */
interface DocumentManagerInterface extends ObjectManager
{
    /**
     * Key to request the request-class for creating.
     * @var string
     */
    const REQUEST_TYPE_CREATE = 'Create';

    /**
     * Key to request the request-class for deleting by id.
     * @var string
     */
    const REQUEST_TYPE_DELETE_BY_ID = 'DeleteById';
    #
    /**
     * Key to request the request-class for deleting by key.
     * @var string
     */
    const REQUEST_TYPE_DELETE_BY_KEY = 'DeleteByKey';
    /**
     * Key to request the request-class for finding by id.
     * @var string
     */
    const REQUEST_TYPE_FIND_BY_ID = 'FindById';
    /**
     * Key to request the request-class for finding by key.
     * @var string
     */
    const REQUEST_TYPE_FIND_BY_KEY = 'FindByKey';
    /**
     * Key to request the request-class for simple querying.
     * @var string
     */
    const REQUEST_TYPE_QUERY = 'Query';
    /**
     * Key to request the request-class for updating by id.
     * @var string
     */
    const REQUEST_TYPE_UPDATE_BY_ID = 'UpdateById';
    /**
     * Key to request the request-class for updating by key.
     * @var string
     */
    const REQUEST_TYPE_UPDATE_BY_KEY = 'UpdateByKey';

    /**
     * Returns a request class for fetching/updating/deleting documents using the request map of the given class name.
     * @param string $className
     * @param string $requestType
     * @param array $args The optional arguments.
     * @return AbstractCreateRequest|AbstractUpdateRequest|AbstractDeleteRequest|AbstractApiRequest
     * @throws \InvalidArgumentException
     */
    public function createRequest(string $className, $requestType = self::REQUEST_TYPE_QUERY, ...$args);

    /**
     * Returns the used commercetools client.
     * @return Client
     */
    public function getClient(): Client;

    /**
     * Returns the unit of work for this manager.
     * @return UnitOfWorkInterface
     */
    public function getUnitOfWork(): UnitOfWorkInterface;
}
