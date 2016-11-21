<?php

namespace BestIt\CommercetoolsODM;

/**
 * The basic interface for the commercetools unit of work.
 * @author lange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM
 * @version $id$
 */
interface UnitOfWorkInterface
{
    /**
     * This state marks an entity as new.
     * @var int
     */
    const STATE_NEW = 1;

    /**
     * This state marks an entity as managed.
     * @var int
     */
    const STATE_MANAGED = 2;

    /**
     * This state marks an entity as removed.
     * @var int
     */
    const STATE_REMOVED = 3;

    /**
     * This state marks an entity as detached.
     * @var int
     */
    const STATE_DETACHED = 4;

    /**
     * Creates a document and registers it as managed.
     * @param string $className
     * @param mixed $responseObject The mapped Response from commercetools.
     * @param array $hints
     * @return mixed The document matching to $className.
     */
    public function createDocument(string $className, $responseObject, array $hints = []);

    /**
     * Commits every change to commercetools.
     * @return void
     */
    public function flush();

    /**
     * Registers the given document as managed.
     * @param mixed $document
     * @param string|int $identifier
     * @param mixed|void $revision
     * @return UnitOfWorkInterface
     */
    public function registerAsManaged($document, $identifier, $revision): UnitOfWorkInterface;

    /**
     * Puts the given object in the save queue.
     * @param mixed $entity
     * @return UnitOfWorkInterface
     */
    public function scheduleSave($entity): UnitOfWorkInterface;

    /**
     * Tries to find an document with the given identifier in the identity map of this UnitOfWork.
     * @param mixed $id The document identifier to look for.
     * @return mixed Returns the document with the specified identifier if it exists in
     *               this UnitOfWork, FALSE otherwise.
     */
    public function tryGetById($id);

    /**
     * Tries to find an document with the given identifier in the identity map of this UnitOfWork.
     * @param string $key The document key to look for.
     * @return mixed Returns the document with the specified identifier if it exists in
     *               this UnitOfWork, FALSE otherwise.
     */
    public function tryGetByKey(string $key);
}
