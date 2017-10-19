<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM;

use BestIt\CommercetoolsODM\Repository\ObjectRepository;

/**
 * Helper for using repositories.
 *
 * @author blange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM
 */
trait RepositoryAwareTrait
{
    /**
     * @var ObjectRepository|null The used repository.
     */
    protected $repository = null;

    /**
     * Returns the used repository.
     *
     * This getter exists to provide you a type safe way.
     *
     * @return ObjectRepository
     */
    public function getRepository(): ObjectRepository
    {
        return $this->repository;
    }

    /**
     * Sets the used repository.
     *
     * @param ObjectRepository $repository The used repository.
     * @return $this
     */
    public function setRepository(ObjectRepository $repository): self
    {
        $this->repository = $repository;

        return $this;
    }
}
