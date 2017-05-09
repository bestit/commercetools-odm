<?php

namespace BestIt\CommercetoolsODM;

use Psr\Log\LoggerAwareInterface;

/**
 * Provides unit of works for this odm package.
 * @author lange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM
 * @version $id$
 */
interface UnitOfWorkFactoryInterface extends LoggerAwareInterface
{
    /**
     * Returns a fresh instance of the unit of work for the given document manager.
     * @param DocumentManagerInterface $documentManager
     * @return UnitOfWorkInterface
     */
    public function getUnitOfWork(DocumentManagerInterface $documentManager) : UnitOfWorkInterface;
}
