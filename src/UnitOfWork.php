<?php

namespace BestIt\CommercetoolsODM;

use BestIt\CommercetoolsODM\Event\LifecycleEventArgs;
use BestIt\CommercetoolsODM\Event\OnFlushEventArgs;
use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Commercetools\Core\Request\AbstractCreateRequest;
use Commercetools\Core\Request\ClientRequestInterface;
use Doctrine\Common\EventManager;
use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use InvalidArgumentException;

class UnitOfWork implements UnitOfWorkInterface
{
    use ClientAwareTrait;

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
     * The event dispatcher.
     * @var EventManager
     */
    private $eventManager = null;

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
     * Which objects should be removed?
     * @var array
     */
    protected $scheduledRemovals = [];

    /**
     * UnitOfWork constructor.
     * @param DocumentManagerInterface $documentManager
     * @param EventManager $eventManager
     */
    public function __construct(
        DocumentManagerInterface $documentManager,
        EventManager $eventManager
    ) {
        $this
            ->setClient($documentManager->getClient())
            ->setDocumentManager($documentManager)
            ->setEventManager($eventManager);
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
        $targetDocument = $sourceDocument ?? $metadata->getNewInstance();
        $version = null;

        // TODO Check if $targetDocument is an instance of $className

        array_map(function ($field) use ($responseObject, $targetDocument) {
            $targetDocument->{'set' . ucfirst($field)}($responseObject->{'get' . ucfirst($field)}());
        }, $metadata->getFieldNames());

        if ($versionField = $metadata->getVersion()) {
            $targetDocument->{'set' . ucfirst($versionField)}($version = $responseObject->getVersion());
        }

        if ($idField = $metadata->getIdentifier()) {
            $targetDocument->{'set' . ucfirst($idField)}($id = $responseObject->getId());
        }

        // TODO Find in new objects.

        $this->registerAsManaged($targetDocument, $id, $version);

        $this->getEventManager()->dispatchEvent(
            Events::POST_LOAD,
            new LifecycleEventArgs($targetDocument, $this->getDocumentManager())
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

    private function detectChangedDocuments()
    {
        $client = $this->getClient();

        foreach ($this->identityMap as $id => $document) {
            $state = $this->getDocumentState($document);
            if ($state == self::STATE_MANAGED) {
                $client->addBatchRequest(
                    $this->computeChangeSet($this->getClassMetadata(get_class($document)), $document)
                );
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
     * Commits every change to commercetools.
     * @return void
     */
    public function flush()
    {
        $this->detectChangedDocuments();

        $dm = $this->getDocumentManager();
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

                if ($versionField = $metadata->getVersion()) {
                    $sourceDocument->{'set' . ucfirst($versionField)}($version = $mappedResponse->getVersion());
                }

                if ($idField = $metadata->getIdentifier()) {
                    $sourceDocument->{'set' . ucfirst($idField)}($id = $mappedResponse->getId());
                }

                unset($this->newDocuments[$key]);

                $this->registerAsManaged($sourceDocument, $id, $version);
            }
        }

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

        exit('xdvgdgf345');
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
        $oid = spl_object_hash($document);
        $state = @$this->documentState[$oid];
        $id = $document->{'get' . ucfirst($class->getIdentifier())}();

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
     * Returns the event manager,
     * @return EventManager
     */
    protected function getEventManager(): EventManager
    {
        return $this->eventManager;
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
     * Sets the event manager.
     * @param EventManager $eventManager
     * @return UnitOfWork
     */
    protected function setEventManager(EventManager $eventManager): UnitOfWork
    {
        $this->eventManager = $eventManager;

        return $this;
    }

    /**
     * Tries to find an document with the given identifier in the identity map of
     * this UnitOfWork.
     *
     * @param mixed $id The document identifier to look for.
     * @return mixed Returns the document with the specified identifier if it exists in
     *               this UnitOfWork, FALSE otherwise.
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
