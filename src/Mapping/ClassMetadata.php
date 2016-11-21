<?php

namespace BestIt\CommercetoolsODM\Mapping;

use BadMethodCallException;
use BestIt\CommercetoolsODM\Mapping\Annotations\RequestMap;
use ReflectionClass;

/**
 * Encapsulates the processed the annotations of an import entity.
 * @author lange <lange@bestit-online.de>
 * @package Bh\ImportMappingBundle
 * @subpackage Mapping
 * @version $id$
 */
class ClassMetadata implements ClassMetadataInterface
{
    /**
     * The draft class for inserting the row.
     * @var string
     */
    private $draft = '';

    /**
     * Maps the field names to their relevant annotations.
     * @var JoinNodes[]|DataNode[]
     */
    private $fieldMappings = [];

    /**
     * The field name of the document identifier.
     * @var string
     */
    private $identifier = '';

    /**
     * The field name for the user-defined identifier.
     * @var string
     */
    private $key = '';

    /**
     * The fully-qualified class name of this persistent class.
     * @var string
     */
    private $name = '';

    /**
     * The reflection for the persistent class.
     * @var null|ReflectionClass
     */
    protected $reflectionClass = null;

    /**
     * The repository defined by the persistent class.
     * @var string
     */
    protected $repository = '';

    /**
     * The request map for the source class if there is one.
     * @var RequestMap
     */
    private $requestClassMap = null;

    /**
     * The name of the version field.
     * @var string
     */
    private $version = '';

    /**
     * ClassMetadata constructor.
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->setName($name);
    }

    /**
     * Returns the draft class for inserting the row.
     * @return string
     */
    public function getDraft(): string
    {
        return $this->draft;
    }

    /**
     * Returns the field mappings.
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
     * @return array
     */
    public function getFieldNames()
    {
        return array_keys($this->getFieldMappings());
    }

    /**
     * Gets the mapped identifier field of this class.
     *
     * Todo original api seems buggy and requires to return an array, but this is not the way, this method is used
     * today.
     * @return string
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    /**
     * Returns the field name for the user-defined identifier.
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * Gets the fully-qualified class name of this persistent class.
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Returns a new instance for the persistent class.
     * @param array ...$args
     * @return mixed
     */
    public function getNewInstance(...$args)
    {
        return $this->getReflectionClass()->newInstance(...$args);
    }

    /**
     * Gets the ReflectionClass instance for this mapped class.
     * @return ReflectionClass
     */
    public function getReflectionClass(): ReflectionClass
    {
        return $this->reflectionClass;
    }

    /**
     * Returns the repository defined by the persistent class.
     * @return string
     */
    public function getRepository(): string
    {
        return $this->repository;
    }

    /**
     * Returns the RequestMap if there is one.
     * @return RequestMap|void
     */
    public function getRequestClassMap()
    {
        return $this->requestClassMap;
    }

    /**
     * Returns the name of the version field.
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * Checks if there is a public getter for the given field name.
     * @param string $fieldName
     * @return bool
     */
    public function hasGetter(string $fieldName)
    {
        $method = 'get' . ucfirst($fieldName);
        $reflection = $this->getReflectionClass();

        return $reflection->hasMethod($method) && $reflection->getMethod($method)->isPublic();
    }

    /**
     * Checks if there is a public setter for the given field name.
     * @param string $fieldName
     * @return bool
     */
    public function hasSetter(string $fieldName)
    {
        $method = 'set' . ucfirst($fieldName);
        $reflection = $this->getReflectionClass();

        return $reflection->hasMethod($method) && $reflection->getMethod($method)->isPublic();
    }

    /**
     * Checks if the given field name is a mapped identifier for this class.
     * @param string $fieldName
     * @return bool
     */
    public function isIdentifier($fieldName): bool
    {
        return $this->getIdentifier() === $fieldName;
    }

    /**
     * Returns true if the given field name is used for the commercetools version.
     * @param string $fieldName
     * @return bool
     */
    public function isVersion(string $fieldName): bool {
        return $this->getVersion() === $fieldName;
    }

    /**
     * Sets the draft class for inserting the row.
     * @param string $draft
     * @return ClassMetadataInterface
     */
    public function setDraft(string $draft): ClassMetadataInterface
    {
        $this->draft = $draft;

        return $this;
    }

    /**
     * @param mixed $fieldMappings
     * @return ClassMetadata
     */
    public function setFieldMappings(array $fieldMappings): ClassMetadata
    {
        $this->fieldMappings = $fieldMappings;

        return $this;
    }

    /**
     * Sets the mapped identifier field of this class.
     * @param string $identifier
     * @return ClassMetadataInterface
     */
    public function setIdentifier(string $identifier): ClassMetadataInterface
    {
        $this->identifier = $identifier;

        return $this;
    }

    /**
     * Sets the field name for the user-defined identifier.
     * @param string $key
     * @return ClassMetadataInterface
     */
    public function setKey(string $key): ClassMetadataInterface
    {
        $this->key = $key;
        return $this;
    }

    /**
     * Sets the fully-qualified class name of this persistent class.
     * @param string $name
     * @return ClassMetadata
     */
    protected function setName(string $name): ClassMetadata
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Sets the reflection class.
     * @param null|ReflectionClass $reflectionClass
     * @return ClassMetadata
     */
    public function setReflectionClass(ReflectionClass $reflectionClass): ClassMetadata
    {
        $this->reflectionClass = $reflectionClass;

        return $this;
    }

    /**
     * Sets the repository defined by the persistent class.
     * @param string $repository
     * @return ClassMetadataInterface
     */
    public function setRepository(string $repository): ClassMetadataInterface
    {
        $this->repository = $repository;

        return $this;
    }

    /**
     * Sets the request map for the source class if there is one.
     * @param RequestMap $requestMap
     * @return ClassMetadataInterface
     */
    public function setRequestClassMap(RequestMap $requestMap): ClassMetadataInterface
    {
        $this->requestClassMap = $requestMap;

        return $this;
    }

    /**
     * Sets the name of the version field.
     * @param string $version
     * @return ClassMetadataInterface
     */
    public function setVersion(string $version): ClassMetadataInterface
    {
        $this->version = $version;

        return $this;
    }

    /**
     * Checks if the given field is a mapped property for this class.
     * @param string $fieldName
     * @return boolean
     */
    public function hasField($fieldName)
    {
        throw new BadMethodCallException('Implement ' . __METHOD__);
    }

    /**
     * Checks if the given field is a mapped association for this class.
     * @param string $fieldName
     * @return boolean
     */
    public function hasAssociation($fieldName)
    {
        throw new BadMethodCallException('Implement ' . __METHOD__);
    }

    /**
     * Checks if the given field is a mapped single valued association for this class.
     * @param string $fieldName
     * @return boolean
     */
    public function isSingleValuedAssociation($fieldName)
    {
        throw new BadMethodCallException('Implement ' . __METHOD__);
    }

    /**
     * Checks if the given field is a mapped collection valued association for this class.
     * @param string $fieldName
     * @return boolean
     */
    public function isCollectionValuedAssociation($fieldName)
    {
        throw new BadMethodCallException('Implement ' . __METHOD__);
    }

    /**
     * Returns an array of identifier field names numerically indexed.
     * @return array
     */
    public function getIdentifierFieldNames()
    {
        throw new BadMethodCallException('Implement ' . __METHOD__);
    }

    /**
     * Returns a numerically indexed list of association names of this persistent class.
     *
     * This array includes identifier associations if present on this class.
     * @return array
     */
    public function getAssociationNames()
    {
        throw new BadMethodCallException('Implement ' . __METHOD__);
    }

    /**
     * Returns a type name of this field.
     *
     * This type names can be implementation specific but should at least include the php types:
     * integer, string, boolean, float/double, datetime.
     * @param string $fieldName
     * @return string
     */
    public function getTypeOfField($fieldName): string
    {
        $reflection = $this->getReflectionClass();
        $type = 'string';

        if (($reflection->hasProperty($fieldName)) &&
            preg_match('/^\s*\* ?@var (.*)' . '$/m', $reflection->getProperty($fieldName)->getDocComment(), $matches)
        ) {
            $type = $matches[1];
        }

        return $type;
    }

    /**
     * Returns the target class name of the given association.
     * @param string $assocName
     * @return string
     */
    public function getAssociationTargetClass($assocName)
    {
        throw new BadMethodCallException('Implement ' . __METHOD__);
    }

    /**
     * Checks if the association is the inverse side of a bidirectional association.
     * @param string $assocName
     * @return boolean
     */
    public function isAssociationInverseSide($assocName)
    {
        throw new BadMethodCallException('Implement ' . __METHOD__);
    }

    /**
     * Returns the target field of the owning side of the association.
     * @param string $assocName
     * @return string
     */
    public function getAssociationMappedByTargetField($assocName)
    {
        throw new BadMethodCallException('Implement ' . __METHOD__);
    }

    /**
     * Returns the identifier of this object as an array with field name as key.
     *
     * Has to return an empty array if no identifier isset.
     * @param object $object
     * @return array
     */
    public function getIdentifierValues($object)
    {
        throw new BadMethodCallException('Implement ' . __METHOD__);
    }
}
