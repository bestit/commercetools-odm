<?php

namespace BestIt\CommercetoolsODM;

use BestIt\CommercetoolsODM\Event\LifecycleEventArgs;
use BestIt\CommercetoolsODM\Event\ListenersInvoker;
use BestIt\CommercetoolsODM\Event\OnFlushEventArgs;
use BestIt\CommercetoolsODM\Helper\EventManagerAwareTrait;
use BestIt\CommercetoolsODM\Helper\ListenerInvokerAwareTrait;
use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Commercetools\Core\Model\Common\Resource;
use Commercetools\Core\Model\ProductType\ProductType;
use Commercetools\Core\Request\ClientRequestInterface;
use Commercetools\Core\Response\ErrorResponse;
use Doctrine\Common\EventManager;
use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use InvalidArgumentException;

class UnitOfWork implements UnitOfWorkInterface
{
    use ClientAwareTrait, EventManagerAwareTrait, ListenerInvokerAwareTrait;

    /**
     * Matches object ids to commercetools ids.
     * @var array
     */
    protected $documentIdentifiers = [];

    /**
     * The used document manager for this unit of work.
     * @var void|DocumentManagerInterface
     */
    private $documentManager = null;

    /**
     * The versions of documents.
     * @var array
     */
    protected $documentRevisions = [];

    /**
     * The states for given object ids.
     * @var array
     * @todo Rename var.
     */
    protected $documentState = [];

    /**
     * Maps documents to ids.
     * @var array
     */
    protected $identityMap = [];

    /**
     * Saves the completely new documents.
     * @var array
     */
    protected $newDocuments = [];

    /**
     * Map of the original entity data of managed entities.
     * Keys are object ids (spl_object_hash). This is used for calculating changesets
     * at commit time.
     *
     * Internal note: Note that PHPs "copy-on-write" behavior helps a lot with memory usage.
     *                A value will only really be copied if the value in the entity is modified
     *                by the user.
     * @todo Add API.
     * @var array
     */
    private $originalEntityData = [];

    /**
     * Which objects should be removed?
     * @var array
     */
    protected $scheduledRemovals = [];

    /**
     * UnitOfWork constructor.
     * @param DocumentManagerInterface $documentManager
     * @param EventManager $eventManager
     * @param ListenersInvoker $listenersInvoker
     */
    public function __construct(
        DocumentManagerInterface $documentManager,
        EventManager $eventManager,
        ListenersInvoker $listenersInvoker
    ) {
        $this
            ->setClient($documentManager->getClient())
            ->setDocumentManager($documentManager)
            ->setEventManager($eventManager)
            ->setListenerInvoker($listenersInvoker);
    }

    /**
     * Cascades the save into the documents childs.
     * @param ClassMetadataInterface $class
     * @param object $document
     * @param array $visited
     * @return UnitOfWork
     */
    private function cascadeScheduleInsert(ClassMetadataInterface $class, $document, array &$visited)
    {
        // TODO

        return $this;
    }

    private function computeChangeSet(ClassMetadataInterface $metadata, $document)
    {
        $changedData = [];
        $newData = $this->extractData($document, $metadata);
        $oldData = $this->getOriginalData($document);

        $changedData = array_filter($newData, function ($value, string $key) use (&$changedData, $oldData) {
            return serialize($value) !== serialize(@$oldData[$key]);
        }, ARRAY_FILTER_USE_BOTH);


        return $changedData ? $this->createUpdateRequest($changedData, $document) : null;
    }

    /**
     * Creates a document and registers it as managed.
     * @param string $className
     * @param mixed $responseObject The mapped Response from commercetools.
     * @param array $hints
     * @param mixed $sourceDocument The source document.
     * @return mixed The document matching to $className.
     */
    public function createDocument(string $className, $responseObject, array $hints = [], $sourceDocument = null)
    {
        /** @var ClassMetadataInterface $metadata */
        $metadata = $this->getClassMetadata($className);

        if ($responseObject instanceof $className) {
            $targetDocument = clone $responseObject;
            $id = $responseObject->getId();
            $version = $responseObject->getVersion();
        } else {
            $targetDocument = $metadata->getNewInstance();

            if ($metadata->getIdentifier()) {
                $id = $responseObject->getId();
            }

            if ($metadata->getVersion()) {
                $version = $responseObject->getVersion();
            }
        }

        // TODO Find in new objects.

        $this->registerAsManaged($targetDocument, $id, $version);

        $this->getListenerInvoker()->invoke(
            new LifecycleEventArgs($targetDocument, $this->getDocumentManager()),
            Events::POST_LOAD,
            $targetDocument,
            $metadata
        );

        return $targetDocument;
    }

    /**
     * Returns the create query for the given document.
     * @param ClassMetadataInterface $metadata
     * @param mixed $document
     * @return ClientRequestInterface
     */
    private function createNewRequest(ClassMetadataInterface $metadata, $document): ClientRequestInterface
    {
        $fields = array_filter($metadata->getFieldNames(), function (string $field) use ($metadata) {
            return !$metadata->isVersion($field) && !$metadata->isIdentifier($field);
        });

        if ($metadata->isCTStandardModel()) {
            unset(
                $fields[array_search('createdAt', $fields)],
                $fields[array_search('id', $fields)],
                $fields[array_search('lastModifiedAt', $fields)],
                $fields[array_search('version', $fields)]
            );
        }

        $values = [];

        foreach ($fields as $field) {
            $values[$field] = $document->{'get' . ucfirst($field)}();
        }

        $draftClass = $metadata->getDraft();
        $draftObject = new $draftClass($values);

        return $this->getDocumentManager()->createRequest(
            $metadata->getName(),
            DocumentManager::REQUEST_TYPE_CREATE,
            $draftObject
        );
    }

    private function createUpdateRequest(
        array $changedData,
        $document,
        ClassMetadataInterface $metadata = null
    ): ClientRequestInterface {
        if (!$metadata) {
            /** @var ClassMetadataInterface $metadata */
            $metadata = $this->getClassMetadata(get_class($document));
        }

        exit(var_dump($changedData, $document, $metadata));
    }

    private function detectChangedDocuments()
    {
        $client = $this->getClient();

        foreach ($this->identityMap as $id => $document) {
            $state = $this->getDocumentState($document);
            if ($state == self::STATE_MANAGED) {
                $updateRequest = $this->computeChangeSet($this->getClassMetadata(get_class($document)), $document);

                if ($updateRequest) {
                    $client->addBatchRequest($updateRequest);
                }
            }
        }

        foreach ($this->newDocuments as $id => $document) {
            $request = $this->createNewRequest($this->getClassMetadata(get_class($document)), $document);

            $client->addBatchRequest($request->setIdentifier($id));
        }
    }

    /**
     * Queues the entity for saving or throws an exception if there is something wrong.
     * @param mixed $entity
     * @param array $visited
     */
    private function doScheduleSave($entity, array &$visited)
    {
        $oid = spl_object_hash($entity);

        if (!isset($visited[$oid])) {
            $visited[$oid] = true;

            $class = $this->getClassMetadata(get_class($entity));
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
                    unset($this->scheduledRemovals[$oid]);
                    $this->documentState[$oid] = self::STATE_MANAGED;
                    break;
                case self::STATE_DETACHED:
                    throw new InvalidArgumentException("Detached document passed to persist().");
                    break;
            }

            $this->cascadeScheduleInsert($class, $entity, $visited);
        }
    }

    /**
     * Uses the getter of the sourceTarget to fetch the field names of the metadata.
     * @param $sourceTarget
     * @param ClassMetadataInterface $metadata
     * @return array
     */
    private function extractData($sourceTarget, ClassMetadataInterface $metadata = null)
    {
        $return = [];

        if (!$metadata) {
            $metadata = $this->getClassMetadata(get_class($sourceTarget));
        }

        array_map(
            function ($field) use (&$return, $sourceTarget) {
                $return[$field] = $sourceTarget->{'get' . ucfirst($field)}();
            },
            $sourceTarget instanceof Resource
                ? array_keys($sourceTarget->fieldDefinitions())
                : $metadata->getFieldNames()
        );

        return $return;
    }

    /**
     * Commits every change to commercetools.
     * @return void
     */
    public function flush()
    {
        $this->detectChangedDocuments();

        $eventManager = $this->getEventManager();

        $eventManager->dispatchEvent(Events::ON_FLUSH, new OnFlushEventArgs($this));

        $responses = $this->getClient()->executeBatch();

        foreach ($responses as $key => $response) {
            $statusCode = $response->getStatusCode();

            // Handle the new rows.
            if ($statusCode === 201) {
                $id = '';
                $request = $response->getRequest();
                $sourceDocument = $this->newDocuments[$key];
                $mappedResponse = $request->mapResponse($response);
                /** @var ClassMetadataInterface $metadata */
                $metadata = $this->getClassMetadata(get_class($sourceDocument));
                $version = null;

                if ($metadata->isCTStandardModel()) {
                    $sourceDocument
                        ->setId($mappedResponse->getId())
                        ->setversion($mappedResponse->getVersion());
                } else {
                    if ($versionField = $metadata->getVersion()) {
                        $sourceDocument->{'set' . ucfirst($versionField)}($version = $mappedResponse->getVersion());
                    }

                    if ($idField = $metadata->getIdentifier()) {
                        $sourceDocument->{'set' . ucfirst($idField)}($id = $mappedResponse->getId());
                    }
                }

                unset($this->newDocuments[$key]);

                $this->registerAsManaged($sourceDocument, $id, $version);
            } else {
                /** @var ErrorResponse $response */
                exit(var_dump($response->getMessage(), $response->getErrors(), $response->getRequest()->httpRequest()
                    ->getBody()->getContents()));
            }
        }

        $this->newDocuments = []; // TODO

        /*foreach ($this->scheduledRemovals AS $oid => $document) {
            $eventManager->dispatchEvent(Events::POST_REMOVE, new LifecycleEventArgs($document, $dm));
        }

        foreach ($this->scheduledUpdates AS $oid => $document) {
            $eventManager->dispatchEvent(Events::PRE_UPDATE, new LifecycleEventArgs($document, $dm));

            $eventManager->dispatchEvent(Events::POST_UPDATE, new LifecycleEventArgs($document, $dm));
        }

        $this->scheduledUpdates =
        $this->scheduledRemovals =
        $this->visitedCollections = array();*/
    }

    /**
     * Returns the metadata for the given class.
     * @param string $class
     * @return ClassMetadata
     */
    protected function getClassMetadata(string $class): ClassMetadata
    {
        return $this->getDocumentManager()->getClassMetadata($class);
    }

    /**
     * Returns the used document manager.
     * @return DocumentManagerInterface|void
     */
    public function getDocumentManager()
    {
        return $this->documentManager;
    }

    /**
     * Get the state of a document.
     * @param  object $document
     * @return int
     * @todo Split for Key and ID. Catch the exception of the commercetools process?
     */
    protected function getDocumentState($document)
    {
        /** @var ClassMetadataInterface $class */
        $class = $this->getClassMetadata($className = get_class($document));
        $isStandard = $document instanceof Resource;
        $oid = spl_object_hash($document);
        $state = @$this->documentState[$oid];
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
     * Returns the cached original data for the given document.
     * @param mixed $document
     * @return array
     */
    private function getOriginalData($document): array
    {
        return $this->originalEntityData[spl_object_hash($document)] ?? [];
    }

    /**
     * Persist new document, marking it managed and generating the id.
     *
     * This method is either called through `DocumentManager#persist()` or during `DocumentManager#flush()`,
     * when persistence by reachability is applied.
     * @param mixed $document
     * @return UnitOfWork
     */
    protected function persistNew($document): UnitOfWork
    {
        $this->getListenerInvoker()->invoke(
            new LifecycleEventArgs($document, $this->getDocumentManager()),
            Events::PRE_PERSIST,
            $document,
            $this->getClassMetadata(get_class($document))
        );

        $this->registerAsManaged($document);

        $this->getEventManager()->dispatchEvent(
            Events::PRE_PERSIST,
            new LifecycleEventArgs($document, $this->getDocumentManager())
        );

        return $this;
    }

    /**
     * Registers the given document as managed.
     * @param mixed $document
     * @param string|int $identifier
     * @param mixed|void $revision
     * @return UnitOfWorkInterface
     * @todo Add after insert.
     */
    public function registerAsManaged($document, $identifier = '', $revision = null): UnitOfWorkInterface
    {
        $oid = spl_object_hash($document);

        $this->documentState[$oid] = self::STATE_MANAGED;
        $this->documentRevisions[$oid] = $revision;

        if ($identifier) {
            $this->documentIdentifiers[$oid] = (string)$identifier;
            $this->identityMap[$identifier] = $document;

            $this->setOriginalData($document);
        } else {
            $this->newDocuments[$oid] = $document;
        }

        return $this;
    }

    /**
     * Puts the given object in the save queue.
     * @param mixed $entity
     * @return UnitOfWorkInterface
     */
    public function scheduleSave($entity): UnitOfWorkInterface
    {
        $visited = array();

        $this->doScheduleSave($entity, $visited);

        return $this;
    }

    /**
     * Sets the used document manager.
     * @param DocumentManagerInterface $documentManager
     * @return UnitOfWork
     */
    protected function setDocumentManager(DocumentManagerInterface $documentManager): UnitOfWork
    {
        $this->documentManager = $documentManager;

        return $this;
    }

    /**
     * Caches the original data for the given dataobject based in the field mapping of the metadata.
     * @param mixed $dataObject
     * @param ClassMetadataInterface|void $metadata
     * @param bool $force
     * @return UnitOfWork
     */
    private function setOriginalData(
        $dataObject,
        ClassMetadataInterface $metadata = null,
        bool $force = false
    ): UnitOfWork {
        $objectId = spl_object_hash($dataObject);

        if (!array_key_exists($objectId, $this->originalEntityData)) {
            $force = true;
        }

        if ($force) {
            $this->originalEntityData[$objectId] = $this->extractData($dataObject, $metadata);
        }

        return $this;
    }

    /**
     * Tries to find an document with the given identifier in the identity map of
     * this UnitOfWork.
     *
     * @param mixed $id The document identifier to look for.
     * @return mixed Returns the document with the specified identifier if it exists in
     *               this UnitOfWork, FALSE otherwise.
     * @todo TryByKey?
     */
    public function tryGetById($id)
    {
        return @$this->identityMap[$id];
    }

    /**
     * Tries to find an document with the given identifier in the identity map of this UnitOfWork.
     * @param string $key The document key to look for.
     * @return mixed Returns the document with the specified identifier if it exists in
     *               this UnitOfWork, FALSE otherwise.
     */
    public function tryGetByKey(string $key)
    {
        // TODO
    }
}
