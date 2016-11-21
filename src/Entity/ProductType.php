<?php

namespace BestIt\CommercetoolsODM\Entity;

use BestIt\CommercetoolsODM\Mapping\Annotations as Commercetools;

/**
 * Entity for Product Types.
 * @author lange <lange@bestit-online.de>
 * @Commercetools\DraftClass("Commercetools\Core\Model\ProductType\ProductTypeDraft")
 * @Commercetools\Entity(requestMap=@Commercetools\RequestMap(
 *     create="ProductTypeCreateRequest",
 *     defaultNamespace="Commercetools\Core\Request\ProductTypes",
 *     deleteById="ProductTypeDeleteRequest",
 *     deleteByKey="ProductTypeDeleteByKeyRequest",
 *     findById="ProductTypeByIdGetRequest",
 *     findByKey="ProductTypeByKeyGetRequest",
 *     query="ProductTypeQueryRequest",
 *     updateById="ProductTypeUpdateRequest",
 *     updateByKey="ProductTypeUpdateByKeyRequest"
 * ))
 * @Commercetools\Repository("BestIt\CommercetoolsODM\Model\ProductTypeRepository")
 * @package BestIt\CommercetoolsODM
 * @subpackage Entity
 * @version $id$
 */
class ProductType
{
    /**
     * The description for the type.
     * @Commercetools\Field(type="string")
     * @var string
     */
    private $description = '';

    /**
     * The Id of this document.
     * @Commercetools\Field(type="string")
     * @Commercetools\Id
     * @var string
     */
    private $id = '';

    /**
     * The key for the type.
     * @Commercetools\Field(type="string")
     * @Commercetools\Key
     * @var string
     */
    private $key = '';

    /**
     * The name for the type.
     * @Commercetools\Field(type="string")
     * @var string
     */
    private $name = '';

    /**
     * The version of this document.
     * @Commercetools\Field(type="int")
     * @Commercetools\Version
     * @var int
     */
    private $version = 0;

    /**
     * Returns the description for the type.
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Returns the id of this document.
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Returns the key for the type.
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * Returns the name for the type.
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Returns the version of this document.
     * @return int
     */
    public function getVersion(): int
    {
        return $this->version;
    }

    /**
     * Sets the description for the type.
     * @param string $description
     * @return ProductType
     */
    public function setDescription(string $description): ProductType
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Sets the ID of this document.
     * @param string $id
     * @return ProductType
     */
    public function setId(string $id): ProductType
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Sets the key for the type.
     * @param string $key
     * @return ProductType
     */
    public function setKey(string $key): ProductType
    {
        $this->key = $key;

        return $this;
    }

    /**
     * Sets the name for the type.
     * @param string $name
     * @return ProductType
     */
    public function setName(string $name): ProductType
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Sets the version of this document.
     * @param int $version
     * @return ProductType
     */
    public function setVersion(int $version): ProductType
    {
        $this->version = $version;

        return $this;
    }
}
