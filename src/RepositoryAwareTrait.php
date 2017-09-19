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
     * The used repository.
     * @var ObjectRepository|null
     */
    private $repository;

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
     * @param ObjectRepository $repository The used repository.
     * @return $this
     */
    public function setRepository(ObjectRepository $repository): self
    {
        $this->repository = $repository;

        return $this;
    }
}
