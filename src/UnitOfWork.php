<?php

namespace BestIt\CommercetoolsODM;

use BestIt\CommercetoolsODM\ActionBuilder\ActionBuilderProcessorInterface;
use BestIt\CommercetoolsODM\Event\LifecycleEventArgs;
use BestIt\CommercetoolsODM\Event\ListenersInvoker;
use BestIt\CommercetoolsODM\Event\OnFlushEventArgs;
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
use Commercetools\Core\Model\CustomField\CustomFieldObject;
use Commercetools\Core\Model\CustomField\FieldContainer;
use Commercetools\Core\Model\Product\Product;
use Commercetools\Core\Model\Product\ProductDraft;
use Commercetools\Core\Model\Product\ProductVariant;
use Commercetools\Core\Model\Product\ProductVariantDraft;
use Commercetools\Core\Model\Type\TypeReference;
use Commercetools\Core\Request\ClientRequestInterface;
use Commercetools\Core\Response\ErrorResponse;
use DateTime;
use Doctrine\Common\EventManager;
use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use InvalidArgumentException;
use SplObjectStorage;
use Traversable;

/**
 * The unit of work inspired by the couch db odm structure.
 * @author blange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM
 * @version $id$
 */
class UnitOfWork implements UnitOfWorkInterface
{
    use ActionBuilderProcessorAwareTrait, ClientAwareTrait, EventManagerAwareTrait, ListenerInvokerAwareTrait;

    /**
     * Maps containers and keys to ids.
     * @var array
     */
    protected $containerKeyMap = [];

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
     * Maps customer ids.
     * @var array
     */
    protected $customerIdMap = [];

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

    private function computeChangeSet(ClassMetadataInterface $metadata, $document)
    {
        $changedData = $this->extractChanges(
            $newData = $this->extractData($document, $metadata),
            $oldData = $this->getOriginalData($document)
        );

        return $changedData ? $this->createUpdateRequest($changedData, $oldData, $document) : null;
    }

    /**
     * Returns the count of manafwment
     * @return int
     */
    public function count()
    {
        return count($this->newDocuments) + count($this->identityMap);
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
                $foundValue = $metadata->isCustomTypeField($fieldName)
                    ? $customObject->getFields()->get($fieldName)
                    : $responseObject->$fieldName;

                $parsedValue = $this->parseFoundFieldValue($fieldName, $metadata, $foundValue);

                $targetDocument->{'set' . ucfirst($fieldName)}($parsedValue);
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

        $draftObject = $this->createDraftObjectForNewRequest($metadata, $object, $fields);

        return $this->getDocumentManager()->createRequest(
            $metadata->getName(),
            DocumentManager::REQUEST_TYPE_CREATE,
            $draftObject
        );
    }

    private function createUpdateRequest(
        array $changedData,
        array $oldData,
        $document,
        ClassMetadataInterface $metadata = null
    ): ClientRequestInterface {
        if (!$metadata) {
            /** @var ClassMetadataInterface $metadata */
            $metadata = $this->getClassMetadata(get_class($document));
        }

        $request = $this->getDocumentManager()->createRequest(
            get_class($document),
            DocumentManager::REQUEST_TYPE_UPDATE_BY_ID,
            $document->getId(),
            $document->getVersion()
        );

        return $request->setActions($this->getActionBuilderProcessor()->createUpdateActions(
            $metadata,
            $changedData,
            $oldData,
            $document
        ));
    }

    private function detectChangedDocuments()
    {
        $client = $this->getClient();

        foreach ($this->identityMap as $id => $document) {
            $state = $this->getDocumentState($document);

            if ($state == self::STATE_MANAGED) {
                $updateRequest = $this->computeChangeSet($this->getClassMetadata(get_class($document)), $document);

                if ($updateRequest) {
                    $client->addBatchRequest($updateRequest->setIdentifier($id));
                }
            }
        }

        foreach ($this->newDocuments as $id => $document) {
            $request = $this->createNewRequest($this->getClassMetadata(get_class($document)), $document);

            $client->addBatchRequest($request->setIdentifier($id));
        }

        foreach ($this->scheduledRemovals as $id => $document) {
            $request = $this->getDocumentManager()->createRequest(
                $this->getClassMetadata(get_class($document))->getName(),
                DocumentManager::REQUEST_TYPE_DELETE_BY_ID,
                $document->getId(),
                $document->getVersion()
            );

            $client->addBatchRequest($request->setIdentifier($id));
        }
    }

    /**
     * Detaches a document from the persistence management.
     * It's persistence will no longer be managed by Doctrine.
     * @param object $document The document to detach.
     */
    public function detach($document)
    {
        $visited = [];
        $this->doDetach($document, $visited);
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
     * @param object $document
     * @param array $visited
     */
    private function doDetach($document, array &$visited)
    {
        $oid = spl_object_hash($document);
        if (isset($visited[$oid])) {
            return; // Prevent infinite recursion
        }

        $visited[$oid] = $document; // mark visited
        $this->getDetachQueue()->detach($document);

        switch ($this->getDocumentState($document)) {
            case self::STATE_MANAGED:
            case self::STATE_REMOVED:
                if (isset($this->identityMap[@$this->documentIdentifiers[$oid]])) {
                    $this->removeFromIdentityMap($document);
                }
                unset(
                    $this->scheduledRemovals[$oid],
                    $this->originalEntityData[$oid],
                    $this->documentRevisions[$oid],
                    $this->documentIdentifiers[$oid],
                    $this->documentState[$oid]
                );
                break;

            case self::STATE_NEW:
            case self::STATE_DETACHED:
                return;
        }

        $this->cascadeDetach($document, $visited);
    }

    /**
     * Schedules the removal of the given object.
     * @param mixed $object
     * @param array $visited
     */
    private function doScheduleRemove($object, array &$visited)
    {
        $oid = spl_object_hash($object);

        if (isset($visited[$oid])) {
            return;
        }

        $em = $this->getEventManager();
        $visited[$oid] = true;

        $this->scheduledRemovals[$oid] = $object;
        $this->documentState[$oid] = self::STATE_REMOVED;

        $this->detachDeferred($object);

        $this->getListenerInvoker()->invoke(
            new LifecycleEventArgs($object, $this->getDocumentManager()),
            Events::PRE_REMOVE,
            $object,
            $this->getClassMetadata(get_class($object))
        );
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
     * Extracts the changes of the two arrays.
     * @param array $newData
     * @param array $oldData
     * @return array
     * @todo Deletes (missing in new, but filled in old) are not extracted correctly.
     */
    private function extractChanges(array $newData, array $oldData): array
    {
        $changedData = [];

        foreach ($newData as $key => $value) {
            if (is_array($value)) {
                $changedSubData = $this->extractChanges($value, $oldData[$key] ?? []);

                if ($changedSubData) {
                    $changedData[$key] = $changedSubData;
                }
            } else {
                if ($value !== @$oldData[$key]) {
                    $changedData[$key] = $newData[$key] ?? null;
                }
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
            $metadata = $this->getClassMetadata(get_class($sourceTarget));
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
     */
    public function flush()
    {
        $this->detectChangedDocuments();

        $documentManager = $this->getDocumentManager();
        $eventManager = $this->getEventManager();

        $eventManager->dispatchEvent(Events::ON_FLUSH, new OnFlushEventArgs($this));

        foreach ($this->getClient()->executeBatch() as $key => $response) {
            $statusCode = $response->getStatusCode();

            if ($statusCode >= 200 && $statusCode < 300) {
                if ($statusCode === 200) {
                    $document = @$this->identityMap[$key] ?? $response->toObject();
                } elseif ($statusCode === 201) {
                    // Handle the new rows.
                    $document = $this->newDocuments[$key];
                    $mappedResponse = $response->toObject();
                    /** @var ClassMetadataInterface $metadata */
                    $metadata = $this->getClassMetadata(get_class($document));

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

                    unset($this->newDocuments[$key]);
                }

                if ($this->getDocumentState($document) !== self::STATE_REMOVED) {
                    // TODO Everything has a version?
                    $eventManager->dispatchEvent(
                        Events::POST_PERSIST,
                        new LifecycleEventArgs($document, $documentManager)
                    );

                    $this->registerAsManaged($document, $document->getId(), $document->getVersion());
                } else {
                    $eventManager->dispatchEvent(
                        Events::POST_REMOVE,
                        new LifecycleEventArgs($document, $documentManager)
                    );
                }

                $this->processDeferredDetach($document);
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

            $eventManager->dispatchEvent(Events::POST_PERSIST, new LifecycleEventArgs($document, $dm));
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
            'key' => (string) $product->getKey(),
            'productType' => $product->getProductType(),
            'state' => $product->getState(),
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
     * INTERNAL:
     * Removes an document from the identity map. This effectively detaches the
     * document from the persistence management of Doctrine.
     *
     * @ignore
     * @param object $document
     * @return boolean
     */
    private function removeFromIdentityMap($document)
    {
        $oid = spl_object_hash($document);

        if (isset($this->identityMap[$this->documentIdentifiers[$oid]])) {
            unset(
                $this->identityMap[$this->documentIdentifiers[$oid]],
                $this->documentIdentifiers[$oid],
                $this->documentRevisions[$oid],
                $this->documentState[$oid]
            );

            return true;
        }

        return false;
    }

    /**
     * Registers the given document as managed.
     * @param mixed $document
     * @param string|int $identifier
     * @param mixed|void $revision
     * @return UnitOfWorkInterface
     * @todo Add after insert; Add to Maps.
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
