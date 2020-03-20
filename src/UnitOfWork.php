<?php

namespace BestIt\CommercetoolsODM;

use BestIt\CommercetoolsODM\ActionBuilder\ActionBuilderProcessorInterface;
use BestIt\CommercetoolsODM\Event\LifecycleEventArgs;
use BestIt\CommercetoolsODM\Event\ListenersInvoker;
use BestIt\CommercetoolsODM\Event\OnFlushEventArgs;
use BestIt\CommercetoolsODM\Exception\APIException;
use BestIt\CommercetoolsODM\Exception\ConnectException;
use BestIt\CommercetoolsODM\Helper\DocumentManagerAwareTrait;
use BestIt\CommercetoolsODM\Helper\EventManagerAwareTrait;
use BestIt\CommercetoolsODM\Helper\ListenerInvokerAwareTrait;
use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use BestIt\CommercetoolsODM\Repository\ObjectRepository;
use BestIt\CommercetoolsODM\UnitOfWork\ChangeManager;
use BestIt\CommercetoolsODM\UnitOfWork\ChangeManagerInterface;
use BestIt\CommercetoolsODM\UnitOfWork\ResponseHandlers\ResponseHandlerComposite;
use BestIt\CommercetoolsODM\UnitOfWork\ResponseHandlers\ResponseHandlerInterface;
use Commercetools\Core\Error\ApiException as CtApiException;
use Commercetools\Core\Model\Cart\CartDraft;
use Commercetools\Core\Model\Common\AbstractJsonDeserializeObject;
use Commercetools\Core\Model\Common\AssetDraft;
use Commercetools\Core\Model\Common\AssetDraftCollection;
use Commercetools\Core\Model\Common\DateTimeDecorator;
use Commercetools\Core\Model\Common\JsonObject;
use Commercetools\Core\Model\Common\PriceDraft;
use Commercetools\Core\Model\Common\PriceDraftCollection;
use Commercetools\Core\Model\CustomField\CustomFieldObject;
use Commercetools\Core\Model\CustomField\FieldContainer;
use Commercetools\Core\Model\Product\Product;
use Commercetools\Core\Model\Product\ProductDraft;
use Commercetools\Core\Model\Product\ProductVariant;
use Commercetools\Core\Model\Product\ProductVariantDraft;
use Commercetools\Core\Model\Type\TypeReference;
use Commercetools\Core\Request\AbstractDeleteRequest;
use Commercetools\Core\Request\ClientRequestInterface;
use Commercetools\Core\Response\ApiResponseInterface;
use DateTime;
use Doctrine\Common\EventManager;
use Exception;
use InvalidArgumentException;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use RuntimeException;
use SplObjectStorage;
use Traversable;
use function array_filter;
use function array_keys;
use function array_search;
use function array_walk;
use function count;
use function Funct\Strings\upperCaseFirst;
use function get_class;
use function is_array;
use function is_string;
use function memory_get_usage;
use function method_exists;
use function spl_object_hash;
use function stripos;
use function ucfirst;
use function var_dump;

/**
 * The unit of work inspired by the couch db odm structure.
 *
 * @author blange <lange@bestit-online.de>
 * @internal
 * @package BestIt\CommercetoolsODM
 */
class UnitOfWork implements UnitOfWorkInterface
{
    use ActionBuilderProcessorAwareTrait;
    use ClientAwareTrait;
    use DocumentManagerAwareTrait;
    use EventManagerAwareTrait;
    use ListenerInvokerAwareTrait;
    use LoggerAwareTrait;

    /**
     * Handles the change state of given models.
     *
     * @var ChangeManagerInterface|null
     */
    private $changeManager;

    /**
     * Maps containers and keys to ids.
     *
     * @var array
     */
    protected $containerKeyMap = [];

    /**
     * Maps customer ids.
     *
     * @var array
     */
    protected $customerIdMap = [];

    /**
     * Which objects should be detached after flush.
     *
     * @var SplObjectStorage
     */
    private $detachQueue = null;

    /**
     * Matches object ids to commercetools ids.
     *
     * @var array
     */
    protected $documentIdentifiers = [];

    /**
     * The states for given object ids.
     *
     * @todo Rename var.
     * @var array
     */
    protected $documentState = [];

    /**
     * The number of the consecutive flushs.
     *
     * @var int
     */
    private $flushRuns = 0;

    /**
     * Maps documents to ids.
     *
     * @var array
     */
    protected $identityMap = [];

    /**
     * Maps keys to ids.
     *
     * @var array
     */
    protected $keyMap = [];

    /**
     * Saving of the callbacks for objects
     *
     * @var SplObjectStorage
     */
    private $modifiers;

    /**
     * Saves the completely new documents.
     *
     * @var array
     */
    protected $newDocuments = [];

    /**
     * Helper to handle responses.
     *
     * @var ResponseHandlerInterface
     */
    private $responseHandler;

    /**
     * How many times should we retry the flush?
     *
     * @var int
     */
    private $retryCount = self::RETRY_STATUS_DEFAULT;

    /**
     * UnitOfWork constructor.
     *
     * @param ActionBuilderProcessorInterface $actionBuilderProcessor
     * @param DocumentManagerInterface $documentManager
     * @param EventManager $eventManager
     * @param ListenersInvoker $listenersInvoker
     */
    public function __construct(
        ActionBuilderProcessorInterface $actionBuilderProcessor,
        DocumentManagerInterface $documentManager,
        EventManager $eventManager,
        ListenersInvoker $listenersInvoker
    ) {
        $this
            ->setActionBuilderProcessor($actionBuilderProcessor)
            ->setClient($documentManager->getClient())
            ->setDocumentManager($documentManager)
            ->setEventManager($eventManager)
            ->setListenerInvoker($listenersInvoker);

        $this->detachQueue = new SplObjectStorage();
        $this->logger = new NullLogger();
        $this->modifiers = new SplObjectStorage();
    }

    /**
     * Adds the inserts to the request batch.
     *
     * @return $this
     */
    private function addInsertsToRequestBatch(): self
    {
        $client = $this->getClient();

        $this->logger->debug(
            'Iterates thru the new documents for inserts.',
            [
                'newObjectCount' => $this->countNewObjects()
            ]
        );

        foreach ($this->newDocuments as $id => $object) {
            $this->logger->debug(
                'Iterates to an object for an insert.',
                ['class' => get_class($object)]
            );

            $request = $this->createNewRequest($this->getClassMetadata($object), $object);

            // The responses are marked for the given identifier.
            $client->addBatchRequest($request->setIdentifier($id));
        }

        return $this;
    }

    /**
     * Adds a modifier for the given object.
     *
     * @param mixed $object
     * @param callable $change
     *
     * @return void
     */
    private function addModifier($object, callable $change)
    {
        if (!$this->modifiers->contains($object)) {
            $this->modifiers->attach($object, []);
        }

        $callbacks = $this->modifiers[$object];
        $callbacks[] = $change;

        $this->modifiers[$object] = $callbacks;
    }

    /**
     * Adds the removal requests to the request batch.
     *
     * @todo Check for needed usage of version.
     *
     * @return UnitOfWork
     */
    private function addRemovalsToRequestBatch(): self
    {
        $client = $this->getClient();

        $this->logger->debug(
            'Iterates thru the identity map for removals.',
            [
                'allIds' => array_keys($this->identityMap)
            ]
        );

        foreach ($this->identityMap as $id => $model) {
            $isRemoved = $this->isObjectRemoved($model);

            $this->logger->debug(
                'Iterates to an object for a possible remove.',
                [
                    'class' => get_class($model),
                    'id' => $id,
                    'isRemoved' => $isRemoved
                ]
            );

            if ($isRemoved) {
                $request = $this->createRemovalRequest($model);

                // The responses are marked for the given identifier.
                $client->addBatchRequest($request->setIdentifier($id));
            }
        }

        return $this;
    }

    /**
     * Iterates through the entities and creates their update / creation actions if needed.
     *
     * @return void
     */
    private function addRequestsToBatch()
    {
        $this
            ->addUpdatesToRequestBatch()
            ->addInsertsToRequestBatch()
            ->addRemovalsToRequestBatch();
    }

    /**
     * Adds the updates to the request batch.
     *
     * @return $this
     */
    private function addUpdatesToRequestBatch(): self
    {
        $client = $this->getClient();

        $this->logger->debug(
            'Iterates thru the identity map for updates.',
            [
                'allIds' => array_keys($this->identityMap)
            ]
        );

        foreach ($this->identityMap as $id => $object) {
            $isManaged = $this->isObjectManaged($object);

            $this->logger->debug(
                'Iterates to an object for a possible update.',
                [
                    'class' => get_class($object),
                    'id' => $id,
                    'isManaged' => $isManaged
                ]
            );

            if ($isManaged) {
                $this->invokeLifecycleEvents($object, Events::PRE_PERSIST);

                $isChanged = $this->getChangeManager()->isChanged($object);

                if ($isChanged) {
                    $this->logger->debug(
                        'Adds the possible update of the object to the batch or detaches it if required.',
                        [
                            'class' => get_class($object),
                            'id' => $id,
                            'isChanged' => $isChanged,
                            'isManaged' => $isManaged
                        ]
                    );

                    // The responses are marked for the given identifier.
                    $updateRequest = $this->computeChangedObject($object);

                    if ($updateRequest instanceof ClientRequestInterface) {
                        $client->addBatchRequest($updateRequest->setIdentifier($id));
                    } else {
                        $this->invokeLifecycleEvents($object, Events::POST_PERSIST);
                    }
                } else {
                    $this->invokeLifecycleEvents($object, Events::POST_PERSIST);

                    //We can remove it now, if there are no changed but a deferred detach.
                    $this->processDeferredDetach($object);
                }
            }
        }

        return $this;
    }

    /**
     * Is a flush retry allowed?
     *
     * @param bool $increase Should the retry count be increased after the check?
     *
     * @return bool
     */
    public function canRetry(bool $increase = false): bool
    {
        $canRetry = ($this->retryCount < 0) || ($this->retryCount && $this->flushRuns < $this->retryCount);

        if ($increase) {
            ++$this->flushRuns;
        }

        return $canRetry;
    }

    /**
     * Cascades a detach operation to associated documents.
     *
     * @param mixed $document
     * @param array $visited
     *
     * @return void
     */
    private function cascadeDetach($document, array &$visited)
    {
    }

    /**
     * Cascades the save into the documents childs.
     *
     * @param ClassMetadataInterface $class
     * @param mixed $document
     * @param array $visited
     *
     * @return UnitOfWork
     */
    private function cascadeScheduleInsert(ClassMetadataInterface $class, $document, array &$visited): self
    {
        // TODO

        return $this;
    }

    /**
     * Creates the update action for the given object if there is a change in the data.
     *
     * @param mixed $object
     * @todo Topmost array should be used as a whole.
     *
     * @return ClientRequestInterface|null
     */
    private function computeChangedObject($object)
    {
        return $this->createUpdateRequest(
            $this->getChangeManager()->getChanges($object),
            $this->getChangeManager()->getOriginalStatus($object),
            $object
        );
    }

    /**
     * Returns true if the unit of work contains the given document.
     *
     * @param mixed $document
     *
     * @return bool
     */
    public function contains($document): bool
    {
        $objectKey = $this->getKeyForObject($document);

        return isset($this->documentIdentifiers[$objectKey]) || isset($this->newDocuments[$objectKey]);
    }

    /**
     * Returns the count of managed entities.
     *
     * @return int
     */
    public function count(): int
    {
        return count($this->identityMap) + $this->countNewObjects();
    }

    /**
     * Returns the count of managed objects.
     *
     * @return int
     */
    public function countManagedObjects(): int
    {
        return count(array_filter($this->identityMap, [$this, 'isObjectManaged']));
    }

    /**
     * Returns the count for new objects.
     *
     * @return int
     */
    public function countNewObjects(): int
    {
        return count($this->newDocuments);
    }

    /**
     * Returns the count of scheduled removals.
     *
     * @return int
     */
    public function countRemovals(): int
    {
        return count(array_filter($this->identityMap, [$this, 'isObjectRemoved']));
    }

    /**
     * Creates and executes the request batch after checking every relevant object in the uow.
     *
     * @throws APIException
     * @throws ConnectException
     *
     * @return void
     */
    private function createAndExecuteBatch()
    {
        $this->addRequestsToBatch();

        try {
            if ($batchResponses = $this->getClient()->executeBatch()) {
                $this->processResponsesFromBatch($batchResponses);
            }
        } catch (CtApiException $exception) {
            if (stripos($exception->getMessage(), 'Error completing request') !== false) {
                throw new ConnectException($exception->getMessage(), $exception->getCode(), $exception);
            }
        }
    }

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
    ) {
        unset($hints);

        /** @var ClassMetadataInterface $metadata */
        $document = null;
        $id = null;
        $metadata = $this->getClassMetadata($className);
        $version = null;

        if ($responseObject instanceof $className) {
            $targetDocument = clone $responseObject;
            $id = $responseObject->getId();
            $version = $responseObject->getVersion();
        } else {
            /** @var CustomFieldObject $customObject */
            $targetDocument = $metadata->getNewInstance();
            $customObject = $metadata->getCustomTypeFields() ? $responseObject->getCustom() : new CustomFieldObject();

            if ($metadata->getIdentifier()) {
                $id = $responseObject->getId();
            }

            if ($metadata->getVersion()) {
                $version = $responseObject->getVersion();
            }

            // TODO Make it more nice.
            foreach ($metadata->getFieldNames() as $fieldName) {
                if ($metadata->isCustomTypeField($fieldName)) {
                    $foundValue = $customObject->getFields()->get($fieldName);
                } else {
                    $foundValue = method_exists($responseObject, $getter = 'get' . ucfirst($fieldName))
                        ? $responseObject->$getter()
                        : $responseObject->$fieldName;
                }

                if (!empty($foundValue) || !$metadata->ignoreFieldOnEmpty($fieldName)) {
                    $parsedValue = $this->parseFoundFieldValue($fieldName, $metadata, $foundValue);

                    $targetDocument->{'set' . ucfirst($fieldName)}($parsedValue);
                }
            }
        }

        // TODO Find in new objects.
        $this->invokeLifecycleEvents($targetDocument, Events::POST_LOAD, $metadata);

        if (@$id && $withRegistration) {
            $this->registerAsManaged($targetDocument, $id, @$version);
        }

        return $targetDocument;
    }

    /**
     * Creates the draft for a new request.
     *
     * @todo Move to factory.
     *
     * @param ClassMetadataInterface $metadata
     * @param mixed $object The source object.
     * @param array $fields
     *
     * @return JsonObject
     */
    private function createDraftObjectForNewRequest(
        ClassMetadataInterface $metadata,
        $object,
        array $fields
    ): JsonObject {
        $draftClass = $metadata->getDraft();

        if ($draftClass === ProductDraft::class) {
            $values = $this->parseValuesForProductDraft($metadata, $object, $fields);
        } elseif ($draftClass === CartDraft::class) {
            $values = $this->parseValuesForCartDraft($metadata, $object, $fields);
        } else {
            $values = $this->parseValuesForSimpleDraft($metadata, $object, $fields);
        }

        return new $draftClass($values);
    }

    /**
     * Returns the create query for the given document.
     *
     * @param ClassMetadataInterface $metadata
     * @param mixed $object
     *
     * @return ClientRequestInterface
     */
    private function createNewRequest(ClassMetadataInterface $metadata, $object): ClientRequestInterface
    {
        $fields = array_filter($metadata->getFieldNames(), function (string $field) use ($metadata) {
            return !$metadata->isVersion($field) && !$metadata->isIdentifier($field) &&
                !$metadata->isFieldReadOnly($field);
        });

        if ($metadata->isCTStandardModel()) {
            unset(
                $fields[array_search('createdAt', $fields)],
                $fields[array_search('id', $fields)],
                $fields[array_search('lastModifiedAt', $fields)],
                $fields[array_search('version', $fields)]
            );
        }

        $draftObject = $this->createDraftObjectForNewRequest($metadata, $object, $fields);

        return $this->getDocumentManager()->createRequest(
            $metadata->getName(),
            DocumentManager::REQUEST_TYPE_CREATE,
            $draftObject
        );
    }

    /**
     * Creates the removal request for the given model.
     *
     * @param mixed $model
     *
     * @return AbstractDeleteRequest
     */
    private function createRemovalRequest($model): AbstractDeleteRequest
    {
        return $this->getDocumentManager()->createRequest(
            $this->getClassMetadata($model)->getName(),
            DocumentManager::REQUEST_TYPE_DELETE_BY_ID,
            $model->getId(),
            $model->getVersion()
        );
    }

    /**
     * Creates the update request for the given changed data.
     *
     * @param array $changedData
     * @param array $oldData
     * @param mixed $document
     * @param ClassMetadataInterface|null $metadata
     *
     * @return ClientRequestInterface|null
     */
    private function createUpdateRequest(
        array $changedData,
        array $oldData,
        $document,
        ClassMetadataInterface $metadata = null
    ) {
        $documentClass = get_class($document);

        if (!$metadata) {
            /** @var ClassMetadataInterface $metadata */
            $metadata = $this->getClassMetadata($document);
        }

        $actions = $this->getActionBuilderProcessor()->createUpdateActions(
            $metadata,
            $changedData,
            $oldData,
            $document
        );

        // There are possible differences between the raw view on changes and the real usable changes. If there are no
        // real usable changes then skip the request creation!
        if (!$actions) {
            $this->logger->debug(
                'Skips the creation of the update request because there are no actions.',
                [
                    'actions' => $actions,
                    'class' => get_class($document),
                    'memory' => memory_get_usage(true) / 1024 / 1024,
                    'objectId' => $document->getId(),
                    'objectKey' => $this->getKeyForObject($document),
                    'objectVersion' => $document->getVersion(),
                ]
            );

            return null;
        }

        $requestClass = $this->getDocumentManager()->getRequestClass(
            $documentClass,
            DocumentManager::REQUEST_TYPE_UPDATE_BY_ID
        );

        if (method_exists($requestClass, 'ofObject')) {
            $request = $requestClass::ofObject($document);
        } else {
            $request = $this->getDocumentManager()->createRequest(
                $documentClass,
                DocumentManager::REQUEST_TYPE_UPDATE_BY_ID,
                $document->getId(),
                $document->getVersion()
            );

            /** @var ObjectRepository $repository */
            $repository = $this->getDocumentManager()->getRepository($documentClass);

            // TODO Try to refactor to an explicit api, even if the doctrine base api does not support it.
            if (($repository instanceof ObjectRepository) && ($expands = $repository->getExpands())) {
                array_walk($expands, [$request, 'expand']);
            }

            $this->logger->debug(
                'Created the update request.',
                [
                    'actions' => $actions,
                    'class' => get_class($document),
                    'memory' => memory_get_usage(true) / 1024 / 1024,
                    'objectId' => $document->getId(),
                    'objectKey' => $this->getKeyForObject($document),
                    'objectVersion' => $document->getVersion(),
                    'request' => get_class($request),
                ]
            );

            $request->setActions($actions);
        }

        return $request;
    }

    /**
     * Detaches a document from the persistence management.
     * It's persistence will no longer be managed by Doctrine.
     *
     * @param mixed $object The document to detach.
     *
     * @return void
     */
    public function detach($object)
    {
        $visited = [];
        $this->doDetach($object, $visited);
    }

    /**
     * Detaches the given object after flush.
     *
     * @param mixed $object
     *
     * @return void
     */
    public function detachDeferred($object)
    {
        $this->detachQueue->attach($object);
    }

    /**
     * Executes a detach operation on the given entity.
     *
     * @param mixed $model
     * @param array $visited
     *
     * @return void
     */
    private function doDetach($model, array &$visited)
    {
        $oid = $this->getKeyForObject($model);

        if (!isset($visited[$oid])) {
            $this->logger->info(
                'Model was detached from unit of work.',
                [
                    'class' => get_class($model),
                    'id' => $model->getId(),
                ]
            );

            $visited[$oid] = $model; // mark visited
            $this->detachQueue->detach($model);
            $this->modifiers->detach($model); // TODO Check

            $this->removeFromIdentityMap($model);
            $this->cascadeDetach($model, $visited);

            $this->getChangeManager()->detach($model);
            unset($this->newDocuments[$oid]);

            $this->invokeLifecycleEvents($model, Events::POST_DETACH);
        }
    }

    /**
     * Schedules the removal of the given object.
     *
     * @param mixed $object
     * @param array $visited
     *
     * @return void
     */
    private function doScheduleRemove($object, array &$visited)
    {
        $oid = $this->getKeyForObject($object);

        if (!isset($visited[$oid])) {
            $this->registerAsRemoved($object);
            $this->invokeLifecycleEvents($object, Events::PRE_REMOVE);
        }
    }

    /**
     * Queues the entity for saving or throws an exception if there is something wrong.
     *
     * @param mixed $entity
     * @param array $visited
     *
     * @return void
     */
    private function doScheduleSave($entity, array &$visited)
    {
        $oid = $this->getKeyForObject($entity);

        if (!isset($visited[$oid])) {
            $visited[$oid] = true;

            $class = $this->getClassMetadata($entity);
            $state = $this->getDocumentState($entity);

            switch ($state) {
                case self::STATE_NEW:
                    $this->persistNew($entity);
                    break;
                case self::STATE_MANAGED:
                    // TODO: Change Tracking Deferred Explicit
                    break;
                case self::STATE_REMOVED:
                    // document becomes managed again
                    $this->documentState[$oid] = self::STATE_MANAGED;
                    break;
                case self::STATE_DETACHED:
                    throw new InvalidArgumentException('Detached document passed to persist().');
                    break;
            }

            $this->cascadeScheduleInsert($class, $entity, $visited);
        }
    }

    /**
     * Commits every change to commercetools.
     *
     * @todo Add the detach queue for ignored objects
     *
     * @return void
     */
    public function flush()
    {
        $this->getEventManager()->dispatchEvent(Events::ON_FLUSH, new OnFlushEventArgs($this));

        while ($this->needsToFlush() && ($this->canRetry(true))) {
            $this->logger->debug(
                'Flushes the batch.',
                [
                    'memory' => memory_get_usage(true) / 1024 / 1024,
                    'retryCount' => $this->retryCount,
                    'run' => $this->flushRuns
                ]
            );

            $this->createAndExecuteBatch();

            $this->logger->info(
                'Flushed the batch.',
                [
                    'memory' => memory_get_usage(true) / 1024 / 1024,
                    'retryCount' => $this->retryCount,
                    'run' => $this->flushRuns
                ]
            );
        }

        $this->flushRuns = 0;
    }

    /**
     * Returns the change manager.
     *
     * @return ChangeManagerInterface
     */
    private function getChangeManager(): ChangeManagerInterface
    {
        if (!$this->changeManager) {
            $this->changeManager = $this->loadChangeManager();
        }

        return $this->changeManager;
    }

    /**
     * Returns the metadata for the given class.
     *
     * @param string|object $class
     *
     * @return ClassMetadataInterface
     */
    protected function getClassMetadata($class): ClassMetadataInterface
    {
        return $this->getDocumentManager()->getClassMetadata(is_string($class) ? $class : get_class($class));
    }

    /**
     * Get the state of a document.
     *
     * @param mixed $document
     * @todo Split for Key and ID. Catch the exception of the commercetools process?
     *
     * @return int
     */
    protected function getDocumentState($document): int
    {
        /** @var ClassMetadataInterface $class */
        $class = $this->getClassMetadata($className = get_class($document));
        $isStandard = $document instanceof JsonObject;
        $oid = $this->getKeyForObject($document);
        $state = $this->documentState[$oid] ?? null;
        $id = $isStandard ? $document->getId() : $document->{'get' . ucfirst($class->getIdentifier())}();

        // Check with the id.
        if (!$state && $id) {
            if ($this->tryGetById($id)) {
                $state = self::STATE_DETACHED;
            } else {
                $request = $this->getDocumentManager()->createRequest(
                    $className,
                    DocumentManager::REQUEST_TYPE_FIND_BY_ID,
                    $id
                );

                $response = $this->getDocumentManager()->getClient()->execute($request);
                $state = $response->getStatusCode() === 404 ? self::STATE_NEW : self::STATE_DETACHED;
            }
        }

        // Check with the key.
        if (!$state && ($keyName = $class->getKey()) && ($keyValue = $document->{'get' . ucfirst($keyName)}())) {
            $request = $this->getDocumentManager()->createRequest(
                $className,
                DocumentManager::REQUEST_TYPE_FIND_BY_KEY,
                $keyValue
            );

            $response = $this->getDocumentManager()->getClient()->execute($request);
            $state = $response->getStatusCode() === 404 ? self::STATE_NEW : self::STATE_DETACHED;
        }

        return $state ?? self::STATE_NEW;
    }

    /**
     * Returns a key for the given object.
     *
     * @param mixed $object
     *
     * @return string
     */
    private function getKeyForObject($object): string
    {
        return spl_object_hash($object);
    }

    /**
     * Returns the used response handler.
     *
     * @return ResponseHandlerInterface
     */
    private function getResponseHandler(): ResponseHandlerInterface
    {
        if (!$this->responseHandler) {
            $this->setResponseHandler($this->loadResponseHandler());
        }

        return $this->responseHandler;
    }

    /**
     * Are there any modify callbacks for the given object?
     *
     * @param mixed $object
     *
     * @return bool
     */
    public function hasModifyCallbacks($object): bool
    {
        return $this->modifiers->contains($object);
    }

    /**
     * Invokes the lifecycle events for the given model.
     *
     * @param mixed $model
     * @param string $eventName
     * @param ClassMetadataInterface|null $metadata
     *
     * @return void
     */
    public function invokeLifecycleEvents($model, string $eventName, ClassMetadataInterface $metadata = null)
    {
        $this->getListenerInvoker()->invoke(
            new LifecycleEventArgs($model, $this->getDocumentManager()),
            $eventName,
            $model,
            $metadata ?: $this->getClassMetadata($model)
        );
    }

    /**
     * Returns true if the given object is managed by this class.
     *
     * @param mixed $object
     *
     * @return bool
     */
    private function isObjectManaged($object): bool
    {
        return $this->getDocumentState($object) === self::STATE_MANAGED;
    }

    /**
     * Returns true if the given object is managed by this class but marked as removed.
     *
     * @param mixed $object
     *
     * @return bool
     */
    private function isObjectRemoved($object): bool
    {
        return $this->getDocumentState($object) === self::STATE_REMOVED;
    }

    /**
     * Loads a fresh change manager.
     *
     * @todo Refactor.
     *
     * @return ChangeManagerInterface
     */
    private function loadChangeManager(): ChangeManagerInterface
    {
        $changeManager = new ChangeManager($this->getDocumentManager());

        $changeManager->setLogger($this->logger);

        return $changeManager;
    }

    /**
     * Returns a fresh response handler instance.
     *
     * @return ResponseHandlerInterface
     */
    protected function loadResponseHandler(): ResponseHandlerInterface
    {
        $handler = new ResponseHandlerComposite($this->getDocumentManager());

        $handler->setLogger($this->logger);

        return $handler;
    }

    /**
     * This method uses a callback to modify the given object to get conflict resolution in case of a 409 error.
     *
     * @param mixed $object
     * @param callable $change The callback is called with the given object.
     *
     * @return mixed Returns the changed object.
     */
    public function modify($object, callable $change)
    {
        $change($object);

        $this->addModifier($object, $change);

        return $object;
    }

    /**
     * Are there any objects which need to be flushed to the database.
     *
     * @todo CheckCleanQueue
     *
     * @return bool
     */
    private function needsToFlush()
    {
        if ($this->newDocuments || count($this->detachQueue)) {
            return true;
        }

        foreach ($this->identityMap as $id => $object) {
            if ($this->isObjectManaged($object) && $this->changeManager->isChanged($object)) {
                return true;
            }

            if ($this->isObjectRemoved($object)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Parses the found value with the data from the field declaration.
     *
     * @param string $field
     * @param ClassMetadataInterface $metadata
     * @param mixed $value
     *
     * @return bool|DateTime|int|string
     */
    private function parseFoundFieldValue(string $field, ClassMetadataInterface $metadata, $value)
    {
        switch ($metadata->getTypeOfField($field)) {
            case 'array':
            case 'set':
                // Force parse to array.
                if (!$value) {
                    $value = [];
                }

                if (!is_array($returnValue = $value)) {
                    $returnValue = $value instanceof Traversable ? iterator_to_array($value) : (array) $value;
                }

                // clean up.
                array_walk($returnValue, function ($value) {
                    if ($value instanceof AbstractJsonDeserializeObject) {
                        $value->parentSet(null)->rootSet(null);
                    }
                });

                break;

            case 'boolean':
                $returnValue = (bool) $value;
                break;

            case 'dateTime':
                $returnValue = (new DateTimeDecorator($value))->getDateTime();
                break;

            case 'int':
                $returnValue = (int) $value;
                break;

            case 'string':
                $returnValue = (string) $value;
                break;

            default:
                $returnValue = $value;
        }

        return $returnValue;
    }

    /**
     * Parses the data of the given object to create a cart draft
     *
     * @param ClassMetadataInterface $metadata
     * @param mixed $object The source object.
     * @param array $fields
     *
     * @return array
     */
    private function parseValuesForCartDraft(ClassMetadataInterface $metadata, $object, array $fields): array
    {
        $values = $this->parseValuesForSimpleDraft($metadata, $object, $fields);
        $objectArray = $object->toArray();

        $values['currency'] = $objectArray['currency'];

        if (isset($objectArray['shippingInfo']['shippingMethod'])) {
            $values['shippingMethod'] = $objectArray['shippingInfo']['shippingMethod'];
        }

        return $values;
    }

    /**
     * Parses the data of the given object to create a value array for the draft of the object.
     *
     * @todo To hard coupled with the standard object.
     * @todo Not completely tested.
     *
     * @param ClassMetadataInterface $metadata
     * @param Product $product The source object.
     * @param array $fields
     *
     * @return array
     */
    private function parseValuesForProductDraft(
        ClassMetadataInterface $metadata,
        Product $product,
        array $fields
    ): array {
        $values = [
            'key' => (string) $product->getKey(),
            'productType' => $product->getProductType(),
            'state' => $product->getState(),
            'taxCategory' => $product->getTaxCategory(),
            'variants' => null,
        ];

        if ($productData = $product->getMasterData()) {
            $values += [
                'publish' => (bool) $productData->getPublished()
            ];

            $projection = $productData->getStaged();
            $valueNames = [
                'categoryOrderHints',
                'categories',
                'description',
                'name',
                'metaKeywords',
                'metaDescription',
                'metaTitle',
                'slug',
            ];

            $searchKeywords = $projection->getSearchKeywords();
            if ($searchKeywords && count($searchKeywords)) {
                $valueNames[] = 'searchKeywords';
            }

            foreach ($valueNames as $name) {
                $values[$name] = @$projection->get($name);
            }

            // getAllVariants() did not work as expected and Collection::toArray() changes to mush.
            $variants = [$projection->getMasterVariant()];
            foreach ($projection->getVariants() ?? [] as $variant) {
                $variants[] = $variant;
            }

            /** @var ProductVariant $variant */
            foreach ($variants as $index => $variant) {
                $variantDraft = ProductVariantDraft::fromArray(
                    array_filter(
                        [
                            'attributes' => $variant->getAttributes(),
                            'images' => $variant->getImages(),
                            'key' => $variant->getKey(),
                            'sku' => (string) $variant->getSku()
                        ]
                    )
                );

                if (($prices = $variant->getPrices()) && (count($prices))) {
                    $variantDraft->setPrices(new PriceDraftCollection());

                    foreach ($prices as $price) {
                        $variantDraft->getPrices()->add(PriceDraft::fromArray($price->toArray()));
                    }
                }

                if (($assets = $variant->getAssets()) && (count($assets))) {
                    $variantDraft->setAssets(new AssetDraftCollection());

                    foreach ($assets as $asset) {
                        $variantDraft->getAssets()->add(AssetDraft::fromArray($asset->toArray()));
                    }
                }

                if (!$index) {
                    $values['masterVariant'] = $variantDraft;
                } else {
                    $values['variants'][] = $variantDraft;
                }
            }
        }

        if (!@$values['searchKeywords']) {
            unset($values['searchKeywords']);
        }

        array_walk($values, function (&$value, $key) {
            if ((is_object($value)) && (method_exists($value, 'toArray'))) {
                $value = $value->toArray();
            }
        });

        return array_filter($values);
    }

    /**
     * Parses the data of the given object to create a value array for the draft of the object.
     *
     * @param ClassMetadataInterface $metadata
     * @param mixed $object The source object.
     * @param array $fields
     *
     * @return array
     */
    private function parseValuesForSimpleDraft(ClassMetadataInterface $metadata, $object, array $fields): array
    {
        $customValues = [];
        $values = [];

        foreach ($fields as $field) {
            $usedValue = $object->{'get' . ucfirst($field)}();

            if ($metadata->isCustomTypeField($field)) {
                if (!@$values['custom']) {
                    $values['custom'] = (new CustomFieldObject())
                        ->setType(TypeReference::ofKey($metadata->getCustomType($field)));
                }

                $customValues[$field] = $usedValue;
            } else {
                $values[$field] = $usedValue;
            }
        }

        if ($customValues) {
            $values['custom']->setFields(FieldContainer::fromArray($customValues));
        }

        return $values;
    }

    /**
     * Persist new document, marking it managed and generating the id.
     *
     * This method is either called through `DocumentManager#persist()` or during `DocumentManager#flush()`,
     * when persistence by reachability is applied.
     *
     * @param mixed $document
     *
     * @return UnitOfWork
     */
    protected function persistNew($document): UnitOfWork
    {
        $this->invokeLifecycleEvents($document, Events::PRE_PERSIST);

        $this->registerAsManaged($document);

        return $this;
    }

    /**
     * Processed the deferred detach for the given object.
     *
     * @param mixed $model
     *
     * @return void
     */
    public function processDeferredDetach($model)
    {
        $needsToDetach = $this->detachQueue->contains($model);

        $this->logger->debug(
            'Processes the detach queue for the given model.',
            [
                'class' => get_class($model),
                'id' => $model->getId(),
                'needsDetach' => $needsToDetach,
            ]
        );

        if ($needsToDetach) {
            $this->detach($model);
        }
    }

    /**
     * Processes the responses from the batch.
     *
     * @param ApiResponseInterface[] $batchResponses
     * @throws APIException
     *
     * @return void
     */
    private function processResponsesFromBatch(array $batchResponses)
    {
        $responseHandler = $this->getResponseHandler();

        $this->logger->debug(
            'Handling batch responses.',
            [
                'memory' => memory_get_usage(true) / 1024 / 1024,
                'responseCount' => count($batchResponses),
            ]
        );

        /** @var ApiResponseInterface $response */
        foreach ($batchResponses as $key => $response) {
            $this->logger->debug(
                'Got a batch response.',
                [
                    'memory' => memory_get_usage(true) / 1024 / 1024,
                    'objectId' => $key,
                    'response' => $response->getResponse(),
                    'request' => $response->getRequest(),
                ]
            );

            try {
                $responseHandler->handleResponse($response);
            } catch (Exception $exception) {
                // Just debug level. You can make it to an error on higher layers.
                $this->logger->debug(
                    'Received an error and throws it as an exception.',
                    [
                        'exception' => $exception,
                        'memory' => memory_get_usage(true) / 1024 / 1024
                    ]
                );

                throw $exception;
            }
        }
    }

    /**
     * Refreshes the persistent state of an object from the database,
     * overriding any local changes that have not yet been persisted.
     *
     * @param mixed $object The object to refresh.
     * @param mixed $overwrite Commercetools returns a representation of the object for many update actions, so use
     *                         this responds directly.
     * @return void
     */
    public function refresh($object, $overwrite = null)
    {
        $metadata = $this->getClassMetadata($object);

        if (!$overwrite) {
            throw new RuntimeException('Not yet implemented');
        }

        foreach ($metadata->getFieldNames() as $fieldName) {
            $value = $overwrite->{'get' . upperCaseFirst($fieldName)}();

            if ($value instanceof DateTimeDecorator) {
                $value = $value->getDateTime();
            }

            if ($value !== null) {
                $object->{'set' . upperCaseFirst($fieldName)}($value);
            }
        }
    }

    /**
     * Registers the given document as managed.
     *
     * @param mixed $document
     * @param string|int $identifier
     * @param mixed|null $revision
     *
     * @return UnitOfWorkInterface
     */
    public function registerAsManaged($document, string $identifier = '', $revision = null): UnitOfWorkInterface
    {
        $oid = $this->getKeyForObject($document);

        $this->documentState[$oid] = self::STATE_MANAGED;

        if ($identifier) {
            $oldIdentifier = @$this->documentIdentifiers[$oid];

            if ($oldIdentifier !== $identifier) {
                unset($this->identityMap[$oldIdentifier]);
            }

            $this->documentIdentifiers[$oid] = (string) $identifier;
            $this->identityMap[$identifier] = $document;
            $this->getChangeManager()->registerStatus($document);

            unset($this->newDocuments[$oid]);
        } else {
            $this->newDocuments[$oid] = $document;
        }

        $this->invokeLifecycleEvents($document, Events::POST_REGISTER);

        return $this;
    }

    /**
     * Registers the given document as removed.
     *
     * @param mixed $document
     * @todo Handle id and version even for custom objects.
     *
     * @return UnitOfWorkInterface
     */
    public function registerAsRemoved($document): UnitOfWorkInterface
    {
        $identifier = $document->getId();
        $oid = $this->getKeyForObject($document);

        $this->documentState[$oid] = self::STATE_REMOVED;
        $this->documentIdentifiers[$oid] = (string) $identifier;
        $this->identityMap[$identifier] = $document;

        return $this;
    }

    /**
     * INTERNAL:
     * Removes an document from the identity map. This effectively detaches the
     * document from the persistence management of Doctrine.
     *
     * @ignore
     * @param mixed $document
     * @todo Add key/container clear.
     *
     * @return void
     */
    private function removeFromIdentityMap($document)
    {
        $oid = $this->getKeyForObject($document);

        if (isset($this->documentIdentifiers[$oid])) {
            unset($this->identityMap[$this->documentIdentifiers[$oid]]);
        }

        unset(
            $this->documentIdentifiers[$oid],
            $this->documentState[$oid]
        );
    }

    /**
     * Changes the object with the registered modify callbacks.
     *
     * @param mixed $object
     *
     * return mixed the modified object.
     */
    public function runModifyCallbacks($object)
    {
        foreach ($this->modifiers[$object] as $callback) {
            $callback($object);
        }

        return $object;
    }

    /**
     * Removes the object from the commercetools database.
     *
     * @param mixed $object
     *
     * @return UnitOfWorkInterface
     */
    public function scheduleRemove($object): UnitOfWorkInterface
    {
        $visited = [];

        $this->doScheduleRemove($object, $visited);

        return $this;
    }

    /**
     * Puts the given object in the save queue.
     *
     * @param mixed $entity
     *
     * @return UnitOfWorkInterface
     */
    public function scheduleSave($entity): UnitOfWorkInterface
    {
        $visited = [];

        $this->doScheduleSave($entity, $visited);

        return $this;
    }

    /**
     * Sets the change manager.
     *
     * @param ChangeManagerInterface $changeManager
     *
     * @return $this
     */
    public function setChangeManager(ChangeManagerInterface $changeManager): self
    {
        $this->changeManager = $changeManager;

        return $this;
    }

    /**
     * Sets the response handler for this class.
     *
     * @param ResponseHandlerInterface $responseHandler
     *
     * @return $this
     */
    public function setResponseHandler(ResponseHandlerInterface $responseHandler): self
    {
        $this->responseHandler = $responseHandler;

        return $this;
    }

    /**
     * How often should the flush be retried?
     *
     * You can use the constants for disabling or an infinite loop.
     *
     * @param int $retryCount
     *
     * @return $this
     */
    public function setRetryCount(int $retryCount): self
    {
        $this->retryCount = $retryCount;

        return $this;
    }

    /**
     * Tries to find a managed object by its key and container.
     *
     * @param string $container
     * @param string $key
     *
     * @return mixed|void
     */
    public function tryGetByContainerAndKey(string $container, string $key)
    {
        $key = $container . '|' . $key;
        $return = null;

        if (array_key_exists($key, $this->containerKeyMap)) {
            $return = $this->tryGetById($this->containerKeyMap[$key]);
        }

        return $return;
    }

    /**
     * Tries to find an document with the given customer identifier in the identity map of this UnitOfWork.
     *
     * @param string $id The document customer id to look for.
     *
     * @return mixed Returns the document with the specified identifier if it exists in
     *               this UnitOfWork, void otherwise.
     */
    public function tryGetByCustomerId(string $id)
    {
        $return = null;

        if (array_key_exists($id, $this->customerIdMap)) {
            $return = $this->tryGetById($this->customerIdMap[$id]);
        }

        return $return;
    }

    /**
     * Tries to find an document with the given identifier in the identity map of this UnitOfWork.
     *
     * @param mixed $id The document identifier to look for.
     *
     * @return mixed Returns the document with the specified identifier if it exists in
     *               this UnitOfWork, void otherwise.
     */
    public function tryGetById($id)
    {
        $model = @$this->identityMap[$id];

        if (!$model) {
            $model = @$this->newDocuments[$id];
        }

        return $model;
    }

    /**
     * Tries to find an document with the given identifier in the identity map of this UnitOfWork.
     *
     * @param string $key The document key to look for.
     *
     * @return mixed Returns the document with the specified identifier if it exists in
     *               this UnitOfWork, void otherwise.
     */
    public function tryGetByKey(string $key)
    {
        $return = null;

        if (array_key_exists($key, $this->keyMap)) {
            $return = $this->tryGetById($this->keyMap[$key]);
        }

        return $return;
    }
}
