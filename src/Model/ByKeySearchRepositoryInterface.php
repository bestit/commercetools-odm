<?php

namespace BestIt\CommercetoolsODM\Model;

use Doctrine\Common\Persistence\ObjectRepository;

/**
 * Added an additional find api to the respository.
 * @author lange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM
 * @subpackage Model
 * @version $id$
 */
interface ByKeySearchRepositoryInterface extends ObjectRepository
{
    /**
     * Finds an object by its user defined key.
     * @param string $key
     * @return mixed|void
     * @throws APIException If there is something wrong.
     */
    public function findByKey(string $key);

    /**
     * Finds an object by its user defined key.
     * @param string $key
     * @return mixed|void
     * @param callable|void $onResolve Callback on the successful response.
     * @param callable|void $onReject Callback for an error.
     * @return void
     */
    public function findByKeyAsync(string $key, callable $onResolve = null, callable $onReject = null);
}
