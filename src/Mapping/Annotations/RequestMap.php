<?php

namespace BestIt\CommercetoolsODM\Mapping\Annotations;

use Doctrine\Common\Annotations\Annotation\Required;

/**
 * Maps the default requests for entities to its special request classes.
 * @Annotation
 * @author lange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM
 * @subpackage Mapping\Annotations
 * @Target("ANNOTATION")
 * @version $id$
 */
class RequestMap implements Annotation
{
    /**
     * Create-Request.
     * @Required
     * @var string
     */
    public $create = '';

    /**
     * Namespace for the request classes.
     * @var string
     */
    public $defaultNamespace = '';

    /**
     * Delete request by the user defined key and container.
     * @var string
     */
    public $deleteByContainerAndKey = '';

    /**
     * Delete-Request using the id. The request is used by default.
     * @Required
     * @var string
     */
    public $deleteById = '';

    /**
     * Delete-Request using the user defined key.
     * @var string
     */
    public $deleteByKey = '';

    /**
     * Get Request using a user defined key and container.
     * @var string
     */
    public $findByContainerAndKey = '';

    /**
     * Get-Request using the id. The request is used by default.
     * @Required
     * @var string
     */
    public $findById = '';

    /**
     * Get-Request using the user defined key.
     * @var string
     */
    public $findByKey = '';

    /**
     * Get-Request using the customer id.
     * @var string
     */
    public $findByCustomerId = '';

    /**
     * Class to request every entity.
     * @Required
     * @var string
     */
    public $query = '';

    /**
     * Update-Request using the id. The request is used by default.
     * @Required
     * @var string
     */
    public $updateById = '';

    /**
     * Update-Request using the user defined key.
     * @var string
     */
    public $updateByKey = '';

    //region Getters

    /**
     * Returns the name of the create class.
     * @return string
     */
    public function getCreate(): string
    {
        return $this->getDefaultNamespace() . $this->create;
    }

    /**
     * Returns the Namespace for the request classes.
     * @return string
     */
    public function getDefaultNamespace(): string
    {
        $namespace = $this->defaultNamespace;

        return $namespace ? '\\' . trim($namespace, '\\') . '\\' : '';
    }

    /**
     * Returns the Delete request by the user defined key and container.
     * @return string
     */
    public function getDeleteByContainerAndKey(): string
    {
        return $this->deleteByContainerAndKey;
    }

    /**
     * Returns the Delete-Request using the id. The request is used by default.
     * @return string
     */
    public function getDeleteById(): string
    {
        return $this->getDefaultNamespace() . $this->deleteById;
    }

    /**
     * Returns the Delete-Request using the user defined key.
     * @return string
     */
    public function getDeleteByKey(): string
    {
        return $this->getDefaultNamespace() . $this->deleteByKey;
    }

    /**
     * Returns the Get Request using a user defined key and container.
     * @return string
     */
    public function getFindByContainerAndKey(): string
    {
        return $this->findByContainerAndKey;
    }

    /**
     * Returns the Get-Request using the customer id.
     * @return string
     */
    public function getFindByCustomerId(): string
    {
        return $this->getDefaultNamespace() . $this->findByCustomerId;
    }

    /**
     * Returns the Get-Request using the id. The request is used by default.
     * @return string
     */
    public function getFindById(): string
    {
        return $this->getDefaultNamespace() . $this->findById;
    }

    /**
     * Returns the Get-Request using the user defined key.
     * @return string
     */
    public function getFindByKey(): string
    {
        return $this->getDefaultNamespace() . $this->findByKey;
    }

    /**
     * Returns the Class to request every entity.
     * @return string
     */
    public function getQuery(): string
    {
        return $this->getDefaultNamespace() . $this->query;
    }

    /**
     * Returns the Update-Request using the id. The request is used by default.
     * @return string
     */
    public function getUpdateById(): string
    {
        return $this->getDefaultNamespace() . $this->updateById;
    }

    /**
     * Returns the Update-Request using the user defined key.
     * @return string
     */
    public function getUpdateByKey(): string
    {
        return $this->getDefaultNamespace() . $this->updateByKey;
    }
    //endregion

    //region Setter
    /**
     * Sets the Create-Request.
     * @param string $create
     * @return RequestMap
     */
    public function setCreate(string $create): RequestMap
    {
        $this->create = $create;

        return $this;
    }

    /**
     * Sets the Namespace for the request classes.
     * @param string $defaultNamespace
     * @return RequestMap
     */
    public function setDefaultNamespace(string $defaultNamespace): RequestMap
    {
        $this->defaultNamespace = $defaultNamespace;

        return $this;
    }

    /**
     * Sets the delete request by the user defined key and container.
     * @param string $deleteByContainerAndKey
     * @return RequestMap
     */
    public function setDeleteByContainerAndKey(string $deleteByContainerAndKey): RequestMap
    {
        $this->deleteByContainerAndKey = $deleteByContainerAndKey;

        return $this;
    }

    /**
     * Sets the Delete-Request using the id. The request is used by default.
     * @param string $deleteById
     * @return RequestMap
     */
    public function setDeleteById(string $deleteById): RequestMap
    {
        $this->deleteById = $deleteById;

        return $this;
    }

    /**
     * Sets the Delete-Request using the user defined key.
     * @param string $deleteByKey
     * @return RequestMap
     */
    public function setDeleteByKey(string $deleteByKey): RequestMap
    {
        $this->deleteByKey = $deleteByKey;

        return $this;
    }

    /**
     * Sets the Get Request using a user defined key and container.
     * @param string $findByContainerAndKey
     * @return RequestMap
     */
    public function setFindByContainerAndKey(string $findByContainerAndKey): RequestMap
    {
        $this->findByContainerAndKey = $findByContainerAndKey;

        return $this;
    }

    /**
     * Sets the Get-Request using the customer id.
     * @param string $findByCustomerId
     * @return RequestMap
     */
    public function setFindByCustomerId(string $findByCustomerId): RequestMap
    {
        $this->findByCustomerId = $findByCustomerId;

        return $this;
    }

    /**
     * Sets the Get-Request using the id. The request is used by default.
     * @param string $findById
     * @return RequestMap
     */
    public function setFindById(string $findById): RequestMap
    {
        $this->findById = $findById;

        return $this;
    }

    /**
     * Sets the Get-Request using the user defined key.
     * @param string $findByKey
     * @return RequestMap
     */
    public function setFindByKey(string $findByKey): RequestMap
    {
        $this->findByKey = $findByKey;

        return $this;
    }

    /**
     * Sets the Class to request every entity.
     * @param string $query
     * @return RequestMap
     */
    public function setQuery(string $query): RequestMap
    {
        $this->query = $query;

        return $this;
    }

    /**
     * Sets the Update-Request using the id. The request is used by default.
     * @param string $updateById
     * @return RequestMap
     */
    public function setUpdateById(string $updateById): RequestMap
    {
        $this->updateById = $updateById;

        return $this;
    }

    /**
     * Sets the Update-Request using the user defined key.
     * @param string $updateByKey
     * @return RequestMap
     */
    public function setUpdateByKey(string $updateByKey): RequestMap
    {
        $this->updateByKey = $updateByKey;

        return $this;
    }
    //endregion
}
