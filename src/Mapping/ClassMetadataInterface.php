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
     * Returns true if the given field name is used for the commercetools version.
     * @param string $fieldName
     * @return bool
     */
    public function isVersion(string $fieldName): bool;

    /**
     * Sets the draft class for inserting the row.
     * @param string $draft
     * @return ClassMetadataInterface
     */
    public function setDraft(string $draft): ClassMetadataInterface;

    /**
     * Sets the name of the field for the user-defined identifier.
     * @param string $fieldName
     * @return ClassMetadataInterface
     */
    public function setKey(string $fieldName): ClassMetadataInterface;

    /**
     * Sets the mapped identifier field of this class.
     * @param string $identifier
     * @return ClassMetadataInterface
     */
    public function setIdentifier(string $identifier): ClassMetadataInterface;

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
