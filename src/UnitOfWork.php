<?php

namespace BestIt\CommercetoolsODM;

use BestIt\CommercetoolsODM\ActionBuilder\ActionBuilderProcessorInterface;
use BestIt\CommercetoolsODM\Event\LifecycleEventArgs;
use BestIt\CommercetoolsODM\Event\ListenersInvoker;
use BestIt\CommercetoolsODM\Event\OnFlushEventArgs;
use BestIt\CommercetoolsODM\Exception\APIException;
use BestIt\CommercetoolsODM\Exception\ConflictException;
use BestIt\CommercetoolsODM\Exception\NotFoundException;
use BestIt\CommercetoolsODM\Helper\EventManagerAwareTrait;
use BestIt\CommercetoolsODM\Helper\ListenerInvokerAwareTrait;
use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Commercetools\Core\Model\Cart\CartDraft;
use Commercetools\Core\Model\Common\AbstractJsonDeserializeObject;
use Commercetools\Core\Model\Common\AssetDraft;
use Commercetools\Core\Model\Common\AssetDraftCollection;
use Commercetools\Core\Model\Common\DateTimeDecorator;
use Commercetools\Core\Model\Common\JsonObject;
use Commercetools\Core\Model\Common\PriceDraft;
use Commercetools\Core\Model\Common\PriceDraftCollection;
use Commercetools\Core\Model\Common\Resource;
use Commercetools\Core\Model\Customer\Customer;
use Commercetools\Core\Model\Customer\CustomerSigninResult;
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
use Commercetools\Core\Response\ErrorResponse;
use DateTime;
use Doctrine\Common\EventManager;
use InvalidArgumentException;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use SplObjectStorage;
use Traversable;
use function Funct\Strings\upperCaseFirst;

/**
 * The unit of work inspired by the couch db odm structure.
 * @author blange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM
 * @version $id$
 */
class UnitOfWork implements UnitOfWorkInterface
{
    use ActionBuilderProcessorAwareTrait;
    use ClientAwareTrait;
    use EventManagerAwareTrait;
    use ListenerInvokerAwareTrait;
    use LoggerAwareTrait;

    /**
     * Maps containers and keys to ids.
     * @var array
     */
    protected $containerKeyMap = [];

    /**
     * Maps customer ids.
     * @var array
     */
    protected $customerIdMap = [];

    /**
     * Which objects should be detached after flush.
     * @var SplObjectStorage
     */
    private $detachQueue = null;

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
     * Maps keys to ids.
     * @var array
     */
    protected $keyMap = [];

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
     * Which objects should be removed? (The keys are the spl_object_hashes)
     * @var object[]
     */
    protected $scheduledRemovals = [];

    /**
     * UnitOfWork constructor.
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
            ->setDetachQueue(new SplObjectStorage())
            ->setDocumentManager($documentManager)
            ->setEventManager($eventManager)
            ->setListenerInvoker($listenersInvoker);
    }

    /**
     * Adds the removal requests to the request batch.
     * @return UnitOfWork
     * @todo Check for needed usage of version.
     */
    private function addRemovalsToRequestBatch()
    {
        array_walk($this->scheduledRemovals, function ($document, string $id) {
            $request = $this->getDocumentManager()->createRequest(
                $this->getClassMetadata($document)->getName(),
                DocumentManager::REQUEST_TYPE_DELETE_BY_ID,
                $document->getId(),
                $document->getVersion()
            );

            $this->getClient()->addBatchRequest($request->setIdentifier($id));
        });

        return $this;
    }

    /**
     * Cascades a detach operation to associated documents.
     *
     * @param object $document
     * @param array $visited
     */
    private function cascadeDetach($document, array &$visited)
    {
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
     * Cleanes the file queue.
     * @return void
     */
    private function cleanQueue()
    {
        $this->getLogger()->debug('Cleaned the queue.');

        $this->newDocuments = $this->scheduledRemovals = [];
    }

    /**
     * Creates the update action for the given object if there is a change in the data.
     * @param ClassMetadataInterface $metadata
     * @param object $object
     * @return ClientRequestInterface|null
     * @todo Topmost array should be used as a whole.
     */
    private function computeChangedObject(ClassMetadataInterface $metadata, $object)
    {
        $changedData = $this->extractChanges(
            $newData = $this->extractData($object, $metadata),
            $oldData = $this->getOriginalData($object)
        );

        $this->getLogger()->debug(
            'Extracted changed data from the object.',
            [
                'changedData' => $changedData,
                'newData' => $newData,
                'objectKey' => $this->getKeyForObject($object),
                'oldData' => $oldData
            ]
        );

        return $changedData ? $this->createUpdateRequest($changedData, $oldData, $object) : null;
    }

    /**
     * Iterates through the entities and creates their update / creation actions if needed.
     * @return void
     */
    private function computeChangedObjects()
    {
        $client = $this->getClient();

        // TODO: Refactor to method.
        foreach ($this->identityMap as $id => $object) {
            $state = $this->getDocumentState($object);

            if ($state == self::STATE_MANAGED) {
                $this->getListenerInvoker()->invoke(
                    new LifecycleEventArgs($object, $this->getDocumentManager()),
                    Events::PRE_PERSIST,
                    $object,
                    $this->getClassMetadata($object)
                );

                $updateRequest = $this->computeChangedObject($this->getClassMetadata($object), $object);

                if ($updateRequest) {
                    $client->addBatchRequest($updateRequest->setIdentifier($id));
                } else {
                    $this->processDeferredDetach($object);
                }
            }
        }

        // TODO Refactor to method.
        foreach ($this->newDocuments as $id => $object) {
            $request = $this->createNewRequest($this->getClassMetadata($object), $object);

            $client->addBatchRequest($request->setIdentifier($id));
        }

        $this->addRemovalsToRequestBatch();
    }

    /**
     * Returns true if the unit of work contains the given document.
     * @param  object $document
     * @return bool
     */
    public function contains($document): bool
    {
        $objectKey = $this->getKeyForObject($document);

        return isset($this->documentIdentifiers[$objectKey]) || isset($this->newDocuments[$objectKey]);
    }

    /**
     * Returns the count of managed entities.
     * @return int
     */
    public function count(): int
    {
        return $this->countManagedObjects() + $this->countNewObjects();
    }

    /**
     * Returns the count of managed objects.
     * @return int
     */
    public function countManagedObjects(): int
    {
        return count($this->documentIdentifiers);
    }

    /**
     * Returns the count for new objects.
     * @return int
     */
    public function countNewObjects(): int
    {
        return count($this->newDocuments);
    }

    /**
     * Returns the count of scheduled removals.
     * @return int
     */
    public function countRemovals(): int
    {
        return count($this->scheduledRemovals);
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
            /** @var CustomFieldObject $customObject */
            $targetDocument = $metadata->getNewInstance();
            $customObject = $metadata->getCustomTypeFields() ? $responseObject->getCustom() : new CustomFieldObject();

            if ($metadata->getIdentifier()) {
                $id = $responseObject->getId();
            }

            if ($metadata->getVersion()) {
                $version = $responseObject->getVersion();
            }

            // Make it more nice.
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

        if (@$id) {
            $this->registerAsManaged($targetDocument, $id, @$version);
        }

        $this->getListenerInvoker()->invoke(
            new LifecycleEventArgs($targetDocument, $this->getDocumentManager()),
            Events::POST_LOAD,
            $targetDocument,
            $metadata
        );

        return $targetDocument;
    }

    /**
     * Creates the draft for a new request.
     * @param ClassMetadataInterface $metadata
     * @param object $object The source object.
     * @param array $fields
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
     * @param ClassMetadataInterface $metadata
     * @param mixed $object
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
     * Creates the update request for the given changed data.
     * @param array $changedData
     * @param array $oldData
     * @param object $document
     * @param ClassMetadataInterface|null $metadata
     * @return ClientRequestInterface
     */
    private function createUpdateRequest(
        array $changedData,
        array $oldData,
        $document,
        ClassMetadataInterface $metadata = null
    ): ClientRequestInterface {
        if (!$metadata) {
            /** @var ClassMetadataInterface $metadata */
            $metadata = $this->getClassMetadata($document);
        }

        $request = $this->getDocumentManager()->createRequest(
            get_class($document),
            DocumentManager::REQUEST_TYPE_UPDATE_BY_ID,
            $document->getId(),
            $document->getVersion()
        );

        $actions = $this->getActionBuilderProcessor()->createUpdateActions(
            $metadata,
            $changedData,
            $oldData,
            $document
        );

        $this->getLogger()->debug(
            'Created the update request.',
            [
                'actions' => $actions,
                'objectId' => $document->getId(),
                'objectKey' => $this->getKeyForObject($document),
                'objectVersion' => $document->getVersion(),
                'request' => get_class($request),
            ]
        );

        return $request->setActions($actions);
    }

    /**
     * Detaches a document from the persistence management.
     * It's persistence will no longer be managed by Doctrine.
     * @param object $object The document to detach.
     */
    public function detach($object)
    {
        $visited = [];
        $this->doDetach($object, $visited);
    }

    /**
     * Detaches the given object after flush.
     * @param object $object
     * @return void
     */
    public function detachDeferred($object)
    {
        $this->getDetachQueue()->attach($object);
    }

    /**
     * Executes a detach operation on the given entity.
     * @param object $object
     * @param array $visited
     */
    private function doDetach($object, array &$visited)
    {
        $oid = $this->getKeyForObject($object);

        if (!isset($visited[$oid])) {
            $visited[$oid] = $object; // mark visited
            $this->getDetachQueue()->detach($object);

            $this->removeFromIdentityMap($object);

            $this->cascadeDetach($object, $visited);
        }
    }

    /**
     * Schedules the removal of the given object.
     * @param mixed $object
     * @param array $visited
     */
    private function doScheduleRemove($object, array &$visited)
    {
        $oid = $this->getKeyForObject($object);

        if (!isset($visited[$oid])) {
            $visited[$oid] = true;

            $this->scheduledRemovals[$oid] = $object;
            $this->documentState[$oid] = self::STATE_REMOVED;

            $this->getListenerInvoker()->invoke(
                new LifecycleEventArgs($object, $this->getDocumentManager()),
                Events::PRE_REMOVE,
                $object,
                $this->getClassMetadata($object)
            );
        }
    }

    /**
     * Queues the entity for saving or throws an exception if there is something wrong.
     * @param mixed $entity
     * @param array $visited
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
     * Extracts the changes of the two arrays.
     * @param array $newData
     * @param array $oldData
     * @param string $parentKey The hierarchy key for the parent of the checked data.
     * @return array
     */
    private function extractChanges(array $newData, array $oldData, string $parentKey = ''): array
    {
        $changedData = [];

        foreach ($newData as $key => $value) {
            // We use the name attribute to check for a nested set, because we do not have something else.
            if (is_array($value) && !$this->isSimpleAttributeArray(ltrim($parentKey . '/' . $key, '/'), $value)) {
                $changedSubData = $this->extractChanges(
                    $value,
                    $oldData[$key] ?? [],
                    ltrim($parentKey . '/' . $key, '/')
                );

                // We think that an empty value can be ignored, except if we want to _add_ a new value.
                if ($changedSubData || !array_key_exists($key, $oldData)) {
                    $changedData[$key] = $changedSubData;
                }
            } else {
                if ((!array_key_exists($key, $oldData)) || (($value !== $oldData[$key]) &&
                        // Sometimes the sdk parses an int to float.
                        (!is_numeric($value) || ((float)$value !== (float)$oldData[$key])))
                ) {
                    $changedData[$key] = $value;
                }
            }

            // Remove the value from the old data to get a correct clean up.
            if (array_key_exists($key, $changedData)) {
                unset($oldData[$key]);
            }
        }

        // Mark everything as removed, which is in the old, but not in the new data.
        foreach (array_keys($oldData) as $key) {
            if (!array_key_exists($key, $newData)) {
                $changedData[$key] = null;
            }
        }

        return $changedData;
    }

    /**
     * Uses the getter of the sourceTarget to fetch the field names of the metadata.
     * @param object $sourceTarget
     * @param ClassMetadataInterface $metadata
     * @return array
     */
    private function extractData($sourceTarget, ClassMetadataInterface $metadata = null)
    {
        $return = [];

        if (!$metadata) {
            $metadata = $this->getClassMetadata($sourceTarget);
        }

        if (method_exists($sourceTarget, 'toArray')) {
            /** @var JsonObject $sourceTarget */
            $return = $sourceTarget->toArray();
        } else {
            array_map(
                function ($field) use (&$return, $sourceTarget) {
                    $fieldValue = $sourceTarget->{'get' . ucfirst($field)}();

                    $return[$field] = is_object($fieldValue) ? clone $fieldValue : $fieldValue;
                },
                $sourceTarget instanceof Resource
                    ? array_keys($sourceTarget->fieldDefinitions())
                    : $metadata->getFieldNames()
            );
        }

        return $return;
    }

    /**
     * Commits every change to commercetools.
     * @return void
     * @todo Add the detach queue for ignored objects
     */
    public function flush()
    {
        $this->computeChangedObjects();

        $this->getEventManager()->dispatchEvent(Events::ON_FLUSH, new OnFlushEventArgs($this));

        $logger = $this->getLogger();

        $logger->debug('Flushes the batch.');

        if ($batchResponses = $this->getClient()->executeBatch()) {
            $this->processResponsesFromBatch($batchResponses);
        }

        $this->cleanQueue();

        $this->getLogger()->info('Flushed the batch.');
    }

    /**
     * Returns the metadata for the given class.
     * @param string|object $class
     * @return ClassMetadataInterface
     */
    protected function getClassMetadata($class): ClassMetadataInterface
    {
        return $this->getDocumentManager()->getClassMetadata(is_string($class) ? $class : get_class($class));
    }

    /**
     * Returns the queue for detaching after flush.
     * @return SplObjectStorage
     */
    private function getDetachQueue(): SplObjectStorage
    {
        return $this->detachQueue;
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
     * @param object $object
     * @return string
     */
    private function getKeyForObject($object): string
    {
        return spl_object_hash($object);
    }

    /**
     * Returns the used logger.
     * @return LoggerInterface
     */
    private function getLogger(): LoggerInterface
    {
        if (!$this->logger) {
            $this->setLogger(new NullLogger());
        }

        return $this->logger;
    }

    /**
     * Returns the cached original data for the given document.
     * @param mixed $document
     * @return array
     */
    private function getOriginalData($document): array
    {
        return $this->originalEntityData[$this->getKeyForObject($document)] ?? [];
    }

    /**
     * Throws an error matching the response.
     * @param ApiResponseInterface $response
     * @throws Exception\APIException
     * @return bool If this is no error response.
     */
    private function handleErrorResponse(ApiResponseInterface $response): bool
    {
        if ($response instanceof ErrorResponse) {
            $status = $response->getStatusCode();

            switch (true) {
                case $status === 404:
                    $class = NotFoundException::class;
                    break;

                case $status === 409:
                    $class = ConflictException::class;
                    break;

                default:
                    $class = APIException::class;
            }

            $exception = $class::fromResponse($response);

            // Just debug level. You can make it to an error on higher layers.
            $this->getLogger()->debug(
                'Received an error and throws it as an exception.',
                [
                    'exception' => $exception
                ]
            );

            throw $exception;
        }

        return false;
    }

    /**
     * Checks if the given array is an associative one or has other array childs.
     * @param array $array
     * @return bool
     */
    private function hasNestedArray(array $array): bool
    {
        $isNested = false;

        foreach ($array as $key => $value) {
            if (is_array($value) || !is_numeric($key)) {
                $isNested = true;
                break;
            }
        }

        return $isNested;
    }

    /**
     * We should not deep iterate into simple value arrays, so check, if the given value array is "the end".
     * @param string $key
     * @param array $value
     * @return bool
     */
    private function isSimpleAttributeArray(string $key, array $value): bool
    {
        // The simple value array must be a direct child of the attributes themselves or an nested attribute which is
        // contained in an attribute value.
        return preg_match('~(attributes|value)/\w+/value$~', $key) === 1 && !$this->hasNestedArray($value);
    }

    /**
     * Parses the found value with the data from the field declaration.
     * @param string $field
     * @param ClassMetadataInterface $metadata
     * @param mixed $value
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
                    $returnValue = $value instanceof Traversable ? iterator_to_array($value) : (array)$value;
                }

                // clean up.
                array_walk($returnValue, function ($value) {
                    if ($value instanceof AbstractJsonDeserializeObject) {
                        $value->parentSet(null)->rootSet(null);
                    }
                });

                break;

            case 'boolean':
                $returnValue = (bool)$value;
                break;

            case 'dateTime':
                $returnValue = (new DateTimeDecorator($value))->getDateTime();
                break;

            case 'int':
                $returnValue = (int)$value;
                break;

            case 'string':
                $returnValue = (string)$value;
                break;

            default:
                $returnValue = $value;
        }

        return $returnValue;
    }

    /**
     * Parses the data of the given object to create a cart draft
     * @param ClassMetadataInterface $metadata
     * @param object $object The source object.
     * @param array $fields
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
     * @param ClassMetadataInterface $metadata
     * @param Product $product The source object.
     * @param array $fields
     * @return array
     * @todo To hard coupled with the standard object.
     * @todo Not completely tested.
     */
    private function parseValuesForProductDraft(
        ClassMetadataInterface $metadata,
        Product $product,
        array $fields
    ): array {
        $values = [
            'key' => (string)$product->getKey(),
            'productType' => $product->getProductType(),
            'state' => $product->getState(),
            'taxCategory' => $product->getTaxCategory(),
            'variants' => null,
        ];

        if ($productData = $product->getMasterData()) {
            $values += [
                'publish' => (bool)$productData->getPublished()
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

            if (count($projection->getSearchKeywords())) {
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
                            'sku' => (string)$variant->getSku()
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
     * @param ClassMetadataInterface $metadata
     * @param object $object The source object.
     * @param array $fields
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
     * @param mixed $document
     * @return UnitOfWork
     */
    protected function persistNew($document): UnitOfWork
    {
        $this->getListenerInvoker()->invoke(
            new LifecycleEventArgs($document, $this->getDocumentManager()),
            Events::PRE_PERSIST,
            $document,
            $this->getClassMetadata($document)
        );

        $this->registerAsManaged($document);

        return $this;
    }

    /**
     * Processed the deferred detach for the given object.
     * @param $object
     */
    private function processDeferredDetach($object)
    {
        if ($this->getDetachQueue()->contains($object)) {
            $this->detach($object);
        }
    }

    /**
     * Processes the delete response of an object.
     * @param string $objectId The object identifier.
     * @param ApiResponseInterface $response
     * @throws APIException
     */
    private function processDeleteResponse(string $objectId, ApiResponseInterface $response)
    {
        $this->getLogger()->info('Deleted object.', ['objectKey' => $objectId]);

        $this->getListenerInvoker()->invoke(
            new LifecycleEventArgs($object = $this->scheduledRemovals[$objectId], $this->getDocumentManager()),
            Events::POST_REMOVE,
            $object,
            $this->getClassMetadata($object)
        );

        $this->removeFromIdentityMap($object);
    }

    /**
     * Processes the normal persisting response of an object.
     * @param string $objectId
     * @param ApiResponseInterface $response
     * @throws APIException
     */
    private function processPersistResponse(string $objectId, ApiResponseInterface $response)
    {
        $this->getLogger()->info('Persisted object.', ['objectId' => $objectId]);

        $statusCode = $response->getStatusCode();

        if ($statusCode >= 200 && $statusCode < 300) {
            if ($statusCode === 200) {
                $document = @$this->identityMap[$objectId] ?? $response->toObject();
                $mappedResponse = $response->toObject();
                /** @var ClassMetadataInterface $metadata */
                $metadata = $this->getClassMetadata($document);

                if ($document instanceof Customer) {
                    $document->setAddresses($mappedResponse->getAddresses());
                }

                if ($metadata->isCTStandardModel()) {
                    $document
                        ->setId($mappedResponse->getId())
                        ->setversion($mappedResponse->getVersion());
                } else {
                    if ($versionField = $metadata->getVersion()) {
                        $document->{'set' . ucfirst($versionField)}($mappedResponse->getVersion());
                    }

                    if ($idField = $metadata->getIdentifier()) {
                        $document->{'set' . ucfirst($idField)}($mappedResponse->getId());
                    }
                }
            } elseif ($statusCode === 201) {
                // Handle the new rows.
                $document = $this->newDocuments[$objectId];
                $mappedResponse = $response->toObject();
                /** @var ClassMetadataInterface $metadata */
                $metadata = $this->getClassMetadata($document);

                if ($mappedResponse instanceof CustomerSigninResult) {
                    $mappedResponse = $mappedResponse->getCustomer();

                    if ($document instanceof Customer) {
                        $document->setAddresses($mappedResponse->getAddresses());
                    }
                }

                if ($metadata->isCTStandardModel()) {
                    $document
                        ->setId($mappedResponse->getId())
                        ->setversion($mappedResponse->getVersion());
                } else {
                    if ($versionField = $metadata->getVersion()) {
                        $document->{'set' . ucfirst($versionField)}($mappedResponse->getVersion());
                    }

                    if ($idField = $metadata->getIdentifier()) {
                        $document->{'set' . ucfirst($idField)}($mappedResponse->getId());
                    }
                }

                unset($this->newDocuments[$objectId]);
            }

            // We need to do these things immediately.
            $this->registerAsManaged($document, $document->getId(), $document->getVersion());
            $this->processDeferredDetach($document);

            // TODO Everything has a version?
            $this->getListenerInvoker()->invoke(
                new LifecycleEventArgs($document, $this->getDocumentManager()),
                Events::POST_PERSIST,
                $document,
                $this->getClassMetadata($document)
            );
        }
    }

    /**
     * Processes the responses from the batch.
     * @param ApiResponseInterface[] $batchResponses
     * @throws Exception\APIException
     */
    private function processResponsesFromBatch(array $batchResponses)
    {
        $this->getLogger()->debug('Handling batch responses.', ['responseCount' => count($batchResponses)]);

        /** @var ApiResponseInterface $response */
        foreach ($batchResponses as $key => $response) {
            $this->getLogger()->debug(
                'Got a batch response.',
                [
                    'objectId' => $key,
                    'response' => $response->getResponse(),
                    'request' => $response->getRequest(),
                ]
            );

            $this->handleErrorResponse($response);

            // TODO Check status code, remove from maps, remove from removal, check existance in scheduledRemovals
            if ($response->getRequest() instanceof AbstractDeleteRequest) {
                $this->processDeleteResponse($key, $response);
            } else {
                $this->processPersistResponse($key, $response);
            }
        }
    }

    /**
     * Refreshes the persistent state of an object from the database,
     * overriding any local changes that have not yet been persisted.
     *
     * @param object $object The object to refresh.
     * @param object $overwrite Commercetools returns a representation of the objectfor many update actions, so use
     * this respons directly.
     * @return void
     */
    public function refresh($object, $overwrite = null)
    {
        $metadata = $this->getClassMetadata($object);

        if (!$overwrite) {
            throw new \RuntimeException('Not yet implemented');
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
     * @param object $document
     * @param string|int $identifier
     * @param mixed|null $revision
     * @return UnitOfWorkInterface
     */
    public function registerAsManaged($document, string $identifier = '', $revision = null): UnitOfWorkInterface
    {
        $oid = $this->getKeyForObject($document);

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
     * INTERNAL:
     * Removes an document from the identity map. This effectively detaches the
     * document from the persistence management of Doctrine.
     *
     * @ignore
     * @param object $document
     * @return bool
     */
    private function removeFromIdentityMap($document)
    {
        $oid = $this->getKeyForObject($document);

        if (isset($this->documentIdentifiers[$oid])) {
            unset($this->identityMap[$this->documentIdentifiers[$oid]]);
        }

        unset(
            $this->documentIdentifiers[$oid],
            $this->documentRevisions[$oid],
            $this->documentState[$oid]
        );
    }

    /**
     * Removes the object from the commercetools database.
     * @param mixed $object
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
     * @param mixed $entity
     * @return UnitOfWorkInterface
     */
    public function scheduleSave($entity): UnitOfWorkInterface
    {
        $visited = [];

        $this->doScheduleSave($entity, $visited);

        return $this;
    }

    /**
     * Sets the queue for the objects which should be detached after flush.
     * @param SplObjectStorage $detachQueue
     * @return UnitOfWork
     */
    private function setDetachQueue(SplObjectStorage $detachQueue): UnitOfWork
    {
        $this->detachQueue = $detachQueue;

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
     * Caches the original data for the given data object based in the field mapping of the metadata.
     * @param mixed $dataObject
     * @param ClassMetadataInterface|void $metadata
     * @return UnitOfWork
     */
    private function setOriginalData(
        $dataObject,
        ClassMetadataInterface $metadata = null
    ): UnitOfWork {
        $this->originalEntityData[$this->getKeyForObject($dataObject)] = $this->extractData($dataObject, $metadata);

        return $this;
    }

    /**
     * Tries to find a managed object by its key and container.
     * @param string $container
     * @param string $key
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
     * @param string $id The document customer id to look for.
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
     * @return mixed Returns the document with the specified identifier if it exists in
     *               this UnitOfWork, void otherwise.
     */
    public function tryGetById($id)
    {
        return @$this->identityMap[$id];
    }

    /**
     * Tries to find an document with the given identifier in the identity map of this UnitOfWork.
     * @param string $key The document key to look for.
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
