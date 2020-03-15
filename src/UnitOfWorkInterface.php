<?php

namespace BestIt\CommercetoolsODM;

use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Countable;
use Psr\Log\LoggerAwareInterface;

/**
 * The basic interface for the commercetools unit of work.
 *
 * @author lange <lange@bestit-online.de>
 * @internal
 * @package BestIt\CommercetoolsODM
 */
interface UnitOfWorkInterface extends Countable, LoggerAwareInterface
{
    /**
     * On default we try it 3 times.
     *
     * @var int
     */
    const RETRY_STATUS_DEFAULT = 3;

    /**
     * The system should not retry the flush.
     *
     * @var int
     */
    const RETRY_STATUS_DISABLED = 1;

    /**
     * The system should retry to flush as long as needed.
     *
     * @var int
     */
    const RETRY_STATUS_INFINITE = -1;

    /**
     * This state marks an entity as detached.
     *
     * @var int
     */
    const STATE_DETACHED = 4;

    /**
     * This state marks an entity as managed.
     *
     * @var int
     */
    const STATE_MANAGED = 2;

    /**
     * This state marks an entity as new.
     *
     * @var int
     */
    const STATE_NEW = 1;

    /**
     * This state marks an entity as removed.
     *
     * @var int
     */
    const STATE_REMOVED = 3;

    /**
     * Is a flush retry allowed?
     *
     * @param bool $increase Should the retry count be increased after the check?
     *
     * @return bool
     */
    public function canRetry(bool $increase = false): bool;

    /**
     * Returns true if the unit of work contains the given document.
     *
     * @param mixed $document
     *
     * @return bool
     */
    public function contains($document): bool;

    /**
     * Returns the count of managed objects.
     *
     * @return int
     */
    public function countManagedObjects(): int;

    /**
     * Returns the count for new objects.
     *
     * @return int
     */
    public function countNewObjects(): int;

    /**
     * Returns the count of scheduled removals.
     *
     * @return int
     */
    public function countRemovals(): int;

    /**
     * Creates a document and registers it as managed.
     *
     * @param string $className
     * @param mixed $responseObject The mapped Response from commercetools.
     * @param array $hints
     * @param bool $withRegistration Should we register the object in the unit of work.
     *
     * @return mixed The document matching to $className.
     */
    public function createDocument(
        string $className,
        $responseObject,
        array $hints = [],
        bool $withRegistration = true
    );

    /**
     * Detaches a document from the persistence management.
     *
     * It's persistence will no longer be managed by Doctrine.
     *
     * @param mixed $document The document to detach.
     *
     * @return void
     */
    public function detach($document);

    /**
     * Detaches the given object after flush.
     *
     * @param mixed $object
     *
     * @return void
     */
    public function detachDeferred($object);

    /**
     * Commits every change to commercetools.
     *
     * @return void
     */
    public function flush();

    /**
     * Are there any modify callbacks for the given object?
     *
     * @param mixed $object
     *
     * @return bool
     */
    public function hasModifyCallbacks($object): bool;

    /**
     * Invokes the lifecycle events for the given model.
     *
     * @param mixed $model
     * @param string $eventName
     * @param ClassMetadataInterface|null $metadata
     *
     * @return void
     */
    public function invokeLifecycleEvents($model, string $eventName, ClassMetadataInterface $metadata = null);

    /**
     * This method uses a callback to modify the given object to get conflict resolution in case of a 409 error.
     *
     * @param mixed $object
     * @param callable $change The callback is called with the given object.
     *
     * @return mixed Returns the changed object.
     */
    public function modify($object, callable $change);

    /**
     * Processed the deferred detach for the given object.
     *
     * @param mixed $model
     *
     * @return void
     */
    public function processDeferredDetach($model);

    /**
     * Refresh the given object by querying commercetools to get the current state.
     *
     * @param mixed $object
     *
     * @return void
     */
    public function refresh($object);

    /**
     * Registers the given document as managed.
     *
     * @param mixed $document
     * @param string|int $identifier
     * @param mixed|null $revision
     *
     * @return UnitOfWorkInterface
     */
    public function registerAsManaged($document, string $identifier = '', $revision = null): UnitOfWorkInterface;

    /**
     * Changes the object with the registered modify callbacks.
     *
     * @param mixed $object
     *
     * return mixed the modified object.
     */
    public function runModifyCallbacks($object);

    /**
     * Removes the object from the commercetools database.
     *
     * @param mixed $object
     *
     * @return UnitOfWorkInterface
     */
    public function scheduleRemove($object): UnitOfWorkInterface;

    /**
     * Puts the given object in the save queue.
     *
     * @param mixed $entity
     *
     * @return UnitOfWorkInterface
     */
    public function scheduleSave($entity): UnitOfWorkInterface;

    /**
     * Tries to find a managed object by its key and container.
     *
     * @param string $container
     * @param string $key
     *
     * @return mixed|void
     */
    public function tryGetByContainerAndKey(string $container, string $key);

    /**
     * Tries to find an document with the given customer identifier in the identity map of this UnitOfWork.
     *
     * @param mixed $id The document identifier to look for.
     *
     * @return mixed Returns the document with the specified identifier if it exists in
     *               this UnitOfWork, void otherwise.
     */
    public function tryGetByCustomerId(string $id);

    /**
     * Tries to find an document with the given identifier in the identity map of this UnitOfWork.
     *
     * @param mixed $id The document identifier to look for.
     *
     * @return mixed Returns the document with the specified identifier if it exists in
     *               this UnitOfWork, void otherwise.
     */
    public function tryGetById($id);

    /**
     * Tries to find an document with the given identifier in the identity map of this UnitOfWork.
     *
     * @param string $key The document key to look for.
     *
     * @return mixed Returns the document with the specified identifier if it exists in
     *               this UnitOfWork, void otherwise.
     */
    public function tryGetByKey(string $key);
}
