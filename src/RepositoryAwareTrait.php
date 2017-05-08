<?php

namespace BestIt\CommercetoolsODM;

use BestIt\CommercetoolsODM\Repository\ObjectRepository;

/**
 * Helper for using repositories.
 * @author blange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM
 * @version $id$
 */
trait RepositoryAwareTrait
{
    /**
     * The used repository.
     * @var ObjectRepository
     */
    protected $repository = null;

    /**
     * Returns the used repository.
     * @return ObjectRepository
     */
    public function getRepository(): ObjectRepository
    {
        return $this->repository;
    }

    /**
     * Sets the used repository.
     * @param ObjectRepository $repository
     * @return $this
     */
    public function setRepository(ObjectRepository $repository)
    {
        $this->repository = $repository;

        return $this;
    }
}
