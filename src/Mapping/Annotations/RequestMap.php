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
class RequestMap
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
}
