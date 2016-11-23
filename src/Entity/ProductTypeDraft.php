<?php
namespace BestIt\CommercetoolsODM\Entity;

use BestIt\CommercetoolsODM\Mapping\Annotations as Commercetools;

/**
 * Entity for Product Types.
 * @author Paul Tenbrock <Paul_Tenbrock@outlook.com>
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
class ProductTypeDraft
{
    /**
     * The Name for the type.
     * @Commercetools\Field(type="string")
     * @Commercetools\Name
     * @var string
     */
    private $name = '';
    /**
     * The Key for the type.
     * @Commercetools\Field(type="string")
     * @Commercetools\Key
     * @var string
     */
    private $key = '';
    /**
     * The Description for the type.
     * @Commercetools\Field(type="string")
     * @Commercetools\Description
     * @var string
     */
    private $description = '';
    /**
     * The Attributes for the type.
     * @Commercetools\Field(type="string")
     * @Commercetools\Attributes
     * @var string
     */
    private $attributes = '';

    /**
     * Returns the Name for the type.
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Returns the Key for the type.
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * Returns the Description for the type.
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Return the Attributes for the type.
     * @return string
     */
    public function getAttributes(): string
    {
        return $this->attributes;
    }


    /**
     * Sets the Name for the type.
     * @param string $name
     * @return ProductTypeDraft
     */
    public function setName(string $name): ProductTypeDraft
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Sets the Key for the type.
     * @param string $key
     * @return ProductTypeDraft
     */
    public function setKey(string $key): ProductTypeDraft
    {
        $this->key = $key;

        return $this;
    }

    /**
     * Sets the Description for the type.
     * @param string $description
     * @return ProductTypeDraft
     */
    public function setDescription(string $description): ProductTypeDraft
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Sets the Attributes for the types
     * @param string $attributes
     * @return ProductTypeDraft
     */
    public function setAttributes(string $attributes): ProductTypeDraft
    {
        $this->attributes = $attributes;

        return $this;
    }

}


