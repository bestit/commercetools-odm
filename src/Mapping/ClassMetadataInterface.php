<?php

namespace BestIt\CommercetoolsODM\Mapping;

use BestIt\CommercetoolsODM\Mapping\Annotations\RequestMap;

/**
 * Controls the metadata for this odm package.
 *
 * BSL: I extended the full qualified interface name to prevent problems with dupllicated names.
 * @author lange <lange@bestit-online.de>
 * @subpackage Mapping
 * @version $id$
 */
interface ClassMetadataInterface extends \Doctrine\Common\Persistence\Mapping\ClassMetadata
{
    /**
     * Adds a lifecycle event callback for the persistent class.
     * @param string $eventName
     * @param string $callbackName
     * @return ClassMetadataInterface
     */
    public function addLifecycleEvent(string $eventName, string $callbackName): ClassMetadataInterface;

    /**
     * Returns the model class from which the actions are used.
     * @return string
     */
    public function getActionsFrom(): string;

    /**
     * Returns the custom type key for the given field or an empty string, if there is no type declared.
     * @param string $fieldName
     * @return string
     */
    public function getCustomType(string $fieldName): string;

    /**
     * Returns the Matching of the custom type fields to their type.
     * @return array
     */
    public function getCustomTypeFields(): array;

    /**
     * Returns the draft class for inserting the row.
     * @return string
     */
    public function getDraft(): string;

    /**
     * Returns the key field name for the user-defined identifier.
     * @return string
     */
    public function getKey(): string;

    /**
     * Returns the lifecycle events of the persistent class.
     * string $eventName
     * @return array
     */
    public function getLifecycleEvents(string $eventName = ''): array;

    /**
     * Returns a new instance for the persistent class.
     * @param array ...$args
     * @return mixed
     */
    public function getNewInstance(...$args);

    /**
     * Returns the repository defined by the persistent class.
     * @return string
     */
    public function getRepository(): string;

    /**
     * Returns the RequestMap if there is one.
     * @return RequestMap|void
     */
    public function getRequestClassMap();

    /**
     * Returns the name of the version field.
     * @return string
     */
    public function getVersion(): string;

    /**
     * Has the persistent class lifecycle events.
     * @return bool
     */
    public function hasLifecycleEvents(string $eventName): bool;

    /**
     * Is this persistent class a commercetools standard model?
     * @param bool $status The new status.
     * @return bool The old status.
     */
    public function isCTStandardModel(bool $status = true): bool;

    /**
     * Returns if the given field name is a custom type field of the persistent class for this metadata.
     * @param string $fieldName
     * @return bool
     */
    public function isCustomTypeField(string $fieldName): bool;

    /**
     * Returns true if the given field name is used for the commercetools version.
     * @param string $fieldName
     * @return bool
     */
    public function isVersion(string $fieldName): bool;

    /**
     * Sets the model class from which the actions are used.
     * @param string $actionsFrom
     * @return ClassMetadataInterface
     */
    public function setActionsFrom(string $actionsFrom): ClassMetadataInterface;

    /**
     * Sets the matching of the custom type fields to their type.
     * @param array $customTypeFields
     * @return ClassMetadataInterface
     */
    public function setCustomTypeFields(array $customTypeFields): ClassMetadataInterface;

    /**
     * Sets the draft class for inserting the row.
     * @param string $draft
     * @return ClassMetadataInterface
     */
    public function setDraft(string $draft): ClassMetadataInterface;

    /**
     * Sets the field mappings for persistent class of this object.
     * @param mixed $fieldMappings
     * @return ClassMetadataInterface
     */
    public function setFieldMappings(array $fieldMappings): ClassMetadataInterface;

    /**
     * Sets the mapped identifier field of this class.
     * @param string $identifier
     * @return ClassMetadataInterface
     */
    public function setIdentifier(string $identifier): ClassMetadataInterface;

    /**
     * Sets the name of the field for the user-defined identifier.
     * @param string $fieldName
     * @return ClassMetadataInterface
     */
    public function setKey(string $fieldName): ClassMetadataInterface;

    /**
     * Sets the lifecycle events of the persistent class.
     * @param array $lifecycleEvents
     * @return ClassMetadataInterface
     */
    public function setLifecycleEvents(array $lifecycleEvents): ClassMetadataInterface;

    /**
     * Sets the repository defined by the persistent class.
     * @param string $repository
     * @return ClassMetadataInterface
     */
    public function setRepository(string $repository): ClassMetadataInterface;

    /**
     * Sets the request map for the source class if there is one.
     * @param RequestMap $requestMap
     * @return ClassMetadataInterface
     */
    public function setRequestClassMap(RequestMap $requestMap): ClassMetadataInterface;

    /**
     * Sets the name of the version field.
     * @param string $version
     * @return ClassMetadataInterface
     */
    public function setVersion(string $version): ClassMetadataInterface;
}
