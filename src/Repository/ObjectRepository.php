<?php

namespace BestIt\CommercetoolsODM\Repository;

use BestIt\CommercetoolsODM\DocumentManagerInterface;
use Doctrine\Common\Persistence\ObjectRepository as BasicInterface;

/**
 * The API for the object repos.
 * @author lange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM
 * @subpackage Repository
 * @version $id$
 */
interface ObjectRepository extends BasicInterface
{
    /**
     * Should the expand cache be cleared after the query.
     * @param bool $newStatus The new status.
     * @return bool The old status.
     */
    public function clearExpandAfterQuery($newStatus = false) : bool;

    /**
     * Returns the used document manager.
     * @return DocumentManagerInterface
     */
    public function getDocumentManager(): DocumentManagerInterface;

    /**
     * Returns the elements which should be expanded.
     * @return array
     */
    public function getExpands() : array;

    /**
     * Set the elements which should be expanded.
     * @param array $expands
     * @param bool $clearAfterwards Should the expand cache be cleared after the query.
     * @return ObjectRepository
     */
    public function setExpands(array $expands, $clearAfterwards = false) : ObjectRepository;
}
