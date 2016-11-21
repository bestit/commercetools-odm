<?php
/**
 * Created by PhpStorm.
 * User: lange
 * Date: 18.11.2016
 * Time: 14:12
 */
namespace BestIt\CommercetoolsODM;

/**
 * Provides unit of works for this odm package.
 * @author lange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM
 * @version $id$
 */
interface UnitOfWorkFactoryInterface
{
    /**
     * Returns a fresh instance of the unit of work for the given document manager.
     * @param DocumentManagerInterface $documentManager
     * @return UnitOfWorkInterface
     */
    public function getUnitOfWork(DocumentManagerInterface $documentManager) : UnitOfWorkInterface;
}
