<?php

namespace BestIt\CommercetoolsODM;

use Doctrine\Common\Persistence\ObjectRepository;

/**
 * Interface RepositoryFactoryInterface
 *
 * @author lange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM
 */
interface RepositoryFactoryInterface
{
    /**
     * Gets the repository for a class.
     *
     * @todo Exception.
     *
     * @param DocumentManagerInterface $documentManager
     * @param string $className
     *
     * @return ObjectRepository
     */
    public function getRepository(DocumentManagerInterface $documentManager, string $className): ObjectRepository;
}
