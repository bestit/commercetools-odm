<?php

namespace BestIt\CommercetoolsODM;

use Doctrine\Common\Persistence\ObjectRepository;

/**
 * Interface RepositoryFactoryInterface
 * @author lange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM
 * @version $id$
 */
interface RepositoryFactoryInterface
{
    /**
     * Gets the repository for a class.
     * @param DocumentManagerInterface $documentManager
     * @param string $className
     * @return ObjectRepository
     * @todo Exception.
     */
    public function getRepository(DocumentManagerInterface $documentManager, string $className): ObjectRepository;
}
