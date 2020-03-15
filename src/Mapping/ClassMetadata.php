<?php

namespace BestIt\CommercetoolsODM\Mapping;

use BadMethodCallException;
use BestIt\CommercetoolsODM\Mapping\Annotations\Field;
use BestIt\CommercetoolsODM\Mapping\Annotations\RequestMap;
use Commercetools\Core\Model\Common\JsonObject;
use ReflectionClass;

/**
 * Encapsulates the processed the annotations of an import entity.
 *
 * @author lange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\Mapping
 * @subpackage Mapping
 */
class ClassMetadata implements ClassMetadataInterface
{
    /**
     * The model class from which the actions are used.
     *
     * @var string
     */
    private $actionsFrom = '';

    /**
     * Matches the custom type fields to their type.
     *
     * @var array
     */
    private $customTypeFields = [];

    /**
     * The draft class for inserting the row.
     *
     * @var string
     */
    private $draft = '';

    /**
     * Maps the field names to their relevant annotations.
     *
     * @var JoinNodes[]|DataNode[]
     */
    private $fieldMappings = [];

    /**
     * The field names of this object.
     *
     * @var array
     */
    private $fieldNames = [];

    /**
     * The field name of the document identifier.
     *
     * @var string
     */
    private $identifier = '';

    /**
     * Is this a standard commercetools model?
     *
     * @var bool
     */
    private $isCtStandardModel = true;

    /**
     * The field name for the user-defined identifier.
     *
     * @var string
     */
    private $key = '';

    /**
     * The lifecycle events of the persistent class.
     *
     * @var array
     */
    private $lifecycleEvents = [];

    /**
     * The fully-qualified class name of this persistent class.
     *
     * @var string
     */
    private $name = '';

    /**
     * The reflection for the persistent class.
     *
     * @var null|ReflectionClass
     */
    protected $reflectionClass = null;

    /**
     * The repository defined by the persistent class.
     *
     * @var string
     */
    protected $repository = '';

    /**
     * The request map for the source class if there is one.
     *
     * @var RequestMap
     */
    private $requestClassMap = null;

    /**
     * The name of the version field.
     *
     * @var string
     */
    private $version = '';

    /**
     * ClassMetadata constructor.
     *
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this
            ->setName($name)
            ->isCTStandardModel(is_a($name, JsonObject::class, true));
    }

    /**
     * Adds a lifecycle event callback for the persistent class.
     *
     * @param string $eventName
     * @param string $callbackName
     *
     * @return ClassMetadataInterface
     */
    public function addLifecycleEvent(string $eventName, string $callbackName): ClassMetadataInterface
    {
        if (!array_key_exists($eventName, $this->lifecycleEvents)) {
            $this->lifecycleEvents[$eventName] = [];
        }

        if (!in_array($callbackName, $this->lifecycleEvents[$eventName])) {
            $this->lifecycleEvents[$eventName][] = $callbackName;
        }

        return $this;
    }

    /**
     * Returns the model class from which the actions are used.
     *
     * @return string
     */
    public function getActionsFrom(): string
    {
        return $this->actionsFrom ? $this->actionsFrom : $this->getName();
    }

    /**
     * Returns the target field of the owning side of the association.
     *
     * @param string $assocName
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
     *
     * @return string
     */
    public function getAssociationMappedByTargetField($assocName): string
    {
        throw new BadMethodCallException('Implement ' . __METHOD__);
    }

    /**
     * Returns a numerically indexed list of association names of this persistent class.
     *
     * This array includes identifier associations if present on this class.
     *
     * @return array
     */
    public function getAssociationNames(): array
    {
        throw new BadMethodCallException('Implement ' . __METHOD__);
    }

    /**
     * Returns the target class name of the given association.
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
     * @param string $assocName
     *
     * @return string
     */
    public function getAssociationTargetClass($assocName): string
    {
        throw new BadMethodCallException('Implement ' . __METHOD__);
    }

    /**
     * Returns the custom type key for the given field or an empty string, if there is no type declared.
     *
     * @param string $fieldName
     *
     * @return string
     */
    public function getCustomType(string $fieldName): string
    {
        return (string) @ $this->getCustomTypeFields()[$fieldName];
    }

    /**
     * Returns the Matching of the custom type fields to their type.
     *
     * @return array
     */
    public function getCustomTypeFields(): array
    {
        return $this->customTypeFields;
    }

    /**
     * Returns the draft class for inserting the row.
     *
     * @return string
     */
    public function getDraft(): string
    {
        return $this->draft;
    }

    /**
     * Returns the field mappings.
     *
     * @return JoinNodes[]|DataNode[]
     */
    public function getFieldMappings(): array
    {
        return $this->fieldMappings;
    }

    /**
     * A numerically indexed list of field names of this persistent class.
     *
     * This array includes identifier fields if present on this class.
     *
     * @return array
     */
    public function getFieldNames(): array
    {
        if (!$this->fieldNames) {
            $this->loadFieldNames();
        }

        return $this->fieldNames;
    }

    /**
     * Gets the mapped identifier field of this class.
     *
     * Todo original api seems buggy and requires to return an array, but this is not the way, this method is used
     * today.
     *
     * @return string
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    /**
     * Returns an array of identifier field names numerically indexed.
     *
     * @return array
     */
    public function getIdentifierFieldNames(): array
    {
        throw new BadMethodCallException('Implement ' . __METHOD__);
    }

    /**
     * Returns the identifier of this object as an array with field name as key.
     *
     * Has to return an empty array if no identifier isset.
     *
     * @param mixed $object
     *
     * @return array
     */
    public function getIdentifierValues($object): array
    {
        throw new BadMethodCallException('Implement ' . __METHOD__);
    }

    /**
     * Returns the field name for the user-defined identifier.
     *
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * Returns the lifecycle events of the persistent class.
     *
     * @param string $eventName
     *
     * @return array
     */
    public function getLifecycleEvents(string $eventName = ''): array
    {
        return $eventName ? $this->lifecycleEvents[$eventName] : $this->lifecycleEvents;
    }

    /**
     * Gets the fully-qualified class name of this persistent class.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Returns a new instance for the persistent class.
     *
     * @param array $args
     *
     * @return mixed
     */
    public function getNewInstance(array ...$args)
    {
        return $this->getReflectionClass()->newInstance(...$args);
    }

    /**
     * Gets the ReflectionClass instance for this mapped class.
     *
     * @return ReflectionClass
     */
    public function getReflectionClass(): ReflectionClass
    {
        if (!$this->reflectionClass) {
            $this->reflectionClass = new ReflectionClass($this->getName());
        }

        return $this->reflectionClass;
    }

    /**
     * Returns the repository defined by the persistent class.
     *
     * @return string
     */
    public function getRepository(): string
    {
        return $this->repository;
    }

    /**
     * Returns the RequestMap if there is one.
     *
     * @return RequestMap|void
     */
    public function getRequestClassMap()
    {
        return $this->requestClassMap;
    }

    /**
     * Returns a type name of this field.
     *
     * This type names can be implementation specific but should at least include the php types:
     * integer, string, boolean, float/double, datetime.
     *
     * @param string $fieldName
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
     * @todo Exception
     *
     * @return string
     */
    public function getTypeOfField($fieldName): string
    {
        $fieldMappings = $this->getFieldMappings();
        $type = 'string';

        if (array_key_exists($fieldName, $fieldMappings)) {
            $type = $fieldMappings[$fieldName]->getType();
        }

        return $type;
    }

    /**
     * Returns the name of the version field.
     *
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * Checks if the given field is a mapped association for this class.
     *
     * @param string $fieldName
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
     *
     * @return boolean
     */
    public function hasAssociation($fieldName): bool
    {
        throw new BadMethodCallException('Implement ' . __METHOD__);
    }

    /**
     * Checks if the given field is a mapped property for this class.
     *
     * @param string $fieldName
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
     *
     * @return boolean
     */
    public function hasField($fieldName): bool
    {
        throw new BadMethodCallException('Implement ' . __METHOD__);
    }

    /**
     * Checks if there is a public getter for the given field name.
     *
     * @param string $fieldName
     *
     * @return bool
     */
    public function hasGetter(string $fieldName): bool
    {
        $method = 'get' . ucfirst($fieldName);
        $reflection = $this->getReflectionClass();

        return $reflection->hasMethod($method) && $reflection->getMethod($method)->isPublic();
    }

    /**
     * Has the persistent class events for the given event name.
     *
     * @param string $eventName
     *
     * @return bool
     */
    public function hasLifecycleEvents(string $eventName): bool
    {
        return array_key_exists($eventName, $this->lifecycleEvents);
    }

    /**
     * Checks if there is a public setter for the given field name.
     *
     * @param string $fieldName
     *
     * @return bool
     */
    public function hasSetter(string $fieldName): bool
    {
        $method = 'set' . ucfirst($fieldName);
        $reflection = $this->getReflectionClass();

        return $reflection->hasMethod($method) && $reflection->getMethod($method)->isPublic();
    }

    /**
     * Is this field ignored if the data is empty.
     *
     * @param string $fieldName
     *
     * @return bool
     */
    public function ignoreFieldOnEmpty(string $fieldName): bool
    {
        $mappings = $this->getFieldMappings();
        $return = false;

        if (array_key_exists($fieldName, $mappings)) {
            $return = $mappings[$fieldName]->ignoreOnEmpty();
        }

        return $return;
    }

    /**
     * Checks if the association is the inverse side of a bidirectional association.
     *
     * @param string $assocName
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
     *
     * @return boolean
     */
    public function isAssociationInverseSide($assocName): bool
    {
        throw new BadMethodCallException('Implement ' . __METHOD__);
    }

    /**
     * Is this persistent class a commercetools standard model?
     *
     * @param bool $status The new status.
     *
     * @return bool The old status.
     */
    public function isCTStandardModel(bool $status = true): bool
    {
        $oldStatus = $this->isCtStandardModel;

        if (func_num_args()) {
            $this->isCtStandardModel = $status;
        }

        return $oldStatus;
    }

    /**
     * Checks if the given field is a mapped collection valued association for this class.
     *
     * @param string $fieldName
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
     *
     * @return boolean
     */
    public function isCollectionValuedAssociation($fieldName): bool
    {
        throw new BadMethodCallException('Implement ' . __METHOD__);
    }

    /**
     * Returns if the given field name is a custom type field of the persistent class for this metadata.
     *
     * @param string $fieldName
     *
     * @return bool
     */
    public function isCustomTypeField(string $fieldName): bool
    {
        return array_key_exists($fieldName, $this->getCustomTypeFields());
    }

    /**
     * Is the given field read only?
     *
     * @param string $fieldName
     *
     * @return bool
     */
    public function isFieldReadOnly(string $fieldName): bool
    {
        $mappings = $this->getFieldMappings();
        $return = false;

        if (array_key_exists($fieldName, $mappings)) {
            $return = $mappings[$fieldName]->isReadOnly();
        }

        return $return;
    }

    /**
     * Checks if the given field name is a mapped identifier for this class.
     *
     * @param string $fieldName
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
     *
     * @return bool
     */
    public function isIdentifier($fieldName): bool
    {
        return $this->getIdentifier() === $fieldName;
    }

    /**
     * Checks if the given field is a mapped single valued association for this class.
     *
     * @param string $fieldName
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
     *
     * @return boolean
     */
    public function isSingleValuedAssociation($fieldName): bool
    {
        throw new BadMethodCallException('Implement ' . __METHOD__);
    }

    /**
     * Returns true if the given field name is used for the commercetools version.
     *
     * @param string $fieldName
     *
     * @return bool
     */
    public function isVersion(string $fieldName): bool
    {
        return $this->getVersion() === $fieldName;
    }

    /**
     * Loads the field names for this object.
     *
     * @return void
     */
    private function loadFieldNames()
    {
        $this->setFieldNames(array_keys(
            $this->isCTStandardModel() ? $this->getNewInstance()->fieldDefinitions() : $this->getFieldMappings()
        ));
    }

    /**
     * Sets the model class from which the actions are used.
     *
     * @param string $actionsFrom
     *
     * @return ClassMetadataInterface
     */
    public function setActionsFrom(string $actionsFrom): ClassMetadataInterface
    {
        $this->actionsFrom = $actionsFrom;
        return $this;
    }

    /**
     * Sets the matching of the custom type fields to their type.
     *
     * @param array $customTypeFields
     *
     * @return ClassMetadataInterface
     */
    public function setCustomTypeFields(array $customTypeFields): ClassMetadataInterface
    {
        $this->customTypeFields = $customTypeFields;

        return $this;
    }

    /**
     * Sets the draft class for inserting the row.
     *
     * @param string $draft
     *
     * @return ClassMetadataInterface
     */
    public function setDraft(string $draft): ClassMetadataInterface
    {
        $this->draft = $draft;

        return $this;
    }

    /**
     * Sets the field mappings for persistent class of this object.
     *
     * @param Field[] $fieldMappings
     *
     * @return ClassMetadataInterface
     */
    public function setFieldMappings(array $fieldMappings): ClassMetadataInterface
    {
        $this->fieldMappings = $fieldMappings;

        return $this;
    }

    /**
     * Sets the field names.
     *
     * @param array $fieldNames
     *
     * @return ClassMetadata
     */
    private function setFieldNames(array $fieldNames): ClassMetadata
    {
        $this->fieldNames = $fieldNames;

        return $this;
    }

    /**
     * Sets the mapped identifier field of this class.
     *
     * @param string $identifier
     *
     * @return ClassMetadataInterface
     */
    public function setIdentifier(string $identifier): ClassMetadataInterface
    {
        $this->identifier = $identifier;

        return $this;
    }

    /**
     * Sets the field name for the user-defined identifier.
     *
     * @param string $key
     *
     * @return ClassMetadataInterface
     */
    public function setKey(string $key): ClassMetadataInterface
    {
        $this->key = $key;
        return $this;
    }

    /**
     * Sets the lifecycle events of the persistent class.
     *
     * @param array $lifecycleEvents
     *
     * @return ClassMetadataInterface
     */
    public function setLifecycleEvents(array $lifecycleEvents): ClassMetadataInterface
    {
        $this->lifecycleEvents = $lifecycleEvents;

        return $this;
    }

    /**
     * Sets the fully-qualified class name of this persistent class.
     *
     * @param string $name
     *
     * @return ClassMetadata
     */
    protected function setName(string $name): ClassMetadata
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Sets the reflection class.
     *
     * @param null|ReflectionClass $reflectionClass
     *
     * @return ClassMetadata
     */
    public function setReflectionClass(ReflectionClass $reflectionClass): ClassMetadata
    {
        $this->reflectionClass = $reflectionClass;

        return $this;
    }

    /**
     * Sets the repository defined by the persistent class.
     *
     * @param string $repository
     *
     * @return ClassMetadataInterface
     */
    public function setRepository(string $repository): ClassMetadataInterface
    {
        $this->repository = $repository;

        return $this;
    }

    /**
     * Sets the request map for the source class if there is one.
     *
     * @param RequestMap $requestMap
     *
     * @return ClassMetadataInterface
     */
    public function setRequestClassMap(RequestMap $requestMap): ClassMetadataInterface
    {
        $this->requestClassMap = $requestMap;

        return $this;
    }

    /**
     * Sets the name of the version field.
     *
     * @param string $version
     *
     * @return ClassMetadataInterface
     */
    public function setVersion(string $version): ClassMetadataInterface
    {
        $this->version = $version;

        return $this;
    }
}
