<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\Model;

use BestIt\CommercetoolsODM\Exception\APIException;
use BestIt\CommercetoolsODM\Repository\ObjectRepository;

/**
 * Added an additional find api to the respository.
 *
 * @author lange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\Model
 */
interface ByKeySearchRepositoryInterface extends ObjectRepository
{
    /**
     * Finds an object by its user defined key.
     *
     * @param string $key
     * @throws APIException If there is something wrong.
     *
     * @return mixed|void
     */
    public function findByKey(string $key);

    /**
     * Finds an object by its user defined key.
     *
     * @param string $key
     * @param callable|void $onResolve Callback on the successful response.
     * @param callable|void $onReject Callback for an error.
     *
     * @return mixed|void
     */
    public function findByKeyAsync(string $key, callable $onResolve = null, callable $onReject = null);
}
