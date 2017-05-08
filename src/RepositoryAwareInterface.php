<?php

namespace BestIt\CommercetoolsODM;

use BestIt\CommercetoolsODM\Repository\ObjectRepository;

/**
 * Defines a basic api for setting the repository.
 * @author blange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM
 * @version $id$
 */
interface RepositoryAwareInterface
{
    /**
     * Sets the used repository on the object.
     * @param ObjectRepository $repository
     * @return RepositoryAwareInterface
     */
    public function setRepository(ObjectRepository $repository);
}
