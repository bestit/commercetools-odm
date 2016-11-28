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
class Category
{




    /**
     *The Ancestors for the type.
     * @Commercetools\Field(type="array")
     * @Commercetools\Ancestors
     * @var array
     */
    private $ancestors = '';
    /**
     *The CreatedAt for the type.
     * @Commercetools\Field(type="datetime")
     * @Commercetools\CreatedAt
     * @var datetime
     */
    private $createdAt = '';
    /**
     *The Costum for the type.
     * @Commercetools\Field(type="")TODO
     * @Commercetools\Costum
     * @var TODO
     */
    private $custom = '';
    /**
     *The Description for the type.
     * @Commercetools\Field(type="")TODO
     * @Commercetools\Description
     * @var TODO
     */
    private $description = '';
    /**
     *The ExternalId of this document.
     * @Commercetools\Field(type="string")
     * @Commercetools\ExternalId
     * @var string
     */
    private $externalId = '';
    /**
     *The ID of this document.
     * @Commercetools\Field(type="string")
     * @Commercetools\Id
     * @var string
     */
    private $id = '';
    /**
     *The LastModifiedAt of this type.
     * @Commercetools\Field(type="datetime")
     * @Commercetools\LastModifiedAt
     * @var datetime
     */
    private $lastModifiedAt = '';
    /**
     *The MetaDiscribtion of this document.
     * @Commercetools\Field(type="array")
     * @Commercetools\MetaDiscribtion
     * @var array
     */
    private $metaDescription = '';
    /**
     *The MetaKeywords for the type.
     * @Commercetools\Field(type="array")
     * @Commercetools\MetaKeywords
     * @var array
     */
    private $metaKeywords = '';
    /**
     *The MetaTitle of this document.
     * @Commercetools\Field(type="array")
     * @Commercetools\MetaTitle
     * @var array
     */
    private $metaTitle = '';
    /**
     * The Name for the type.
     * @Commercetools\Field(type="")TODO
     * @Commercetools\Name
     * @var TODO
     */
    private $name = '';
    /**
     *The OrderHint for the type.
     * @Commercetools\Field(type="array")
     * @Commercetools\OrderHint
     * @var array
     */
    private $orderHint = '';
    /**
     *The Parent for the type.
     * @Commercetools\Field(type="array")
     * @Commercetools\Parent
     * @var array
     */
    private $parent = '';
    /**
     *The Slug for the type
     * @Commercetools\Field(type="")TODO
     * @Commercetools\Slug
     * @var TODO
     */
    private $slug = '';
    /**
     *The Version of this document.
     * @Commercetools\Field(type="int")
     * @Commercetools\Version
     * @var int
     */
    private $version = 0;

    /**
     * Returns the Ancestors for the type.
     * @return string
     */
    public function getAncestors(): string
    {
        return $this->ancestors;
    }

    /**
     * Sets the Ancestors for the type.
     * @param string $ancestors
     * @return Category
     */
    public function setAncestors(string $ancestors): Category
    {
        $this->ancestors = $ancestors;

        return $this;
    }

    /**
     * Returns the CreatedAt for the type.
     * @return string
     */
    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    /**
     * Sets the CreatedAt for the type.
     * @param string $createdAt
     * @return Category
     */
    public function setCreatedAt(string $createdAt): Category
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /** Returns the Costum of the type.
     * @return string
     */
    public function getCustom(): string
    {
        return $this->custom;
    }

    /**
     * Set the Custum for the type.
     * @param string $custom
     * @return Category
     */
    public function setCustom(string $custom): Category
    {
        $this->custom = $custom;

        return $this;
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
     * Sets the Description for the type.
     * @param string $description
     * @return Category
     */
    public function setDescription(string $description): Category
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Returns the ExternalId of this document.
     * @return string
     */
    public function getExternalId(): string
    {
        return $this->externalId;
    }

    /**
     * Sete the ExternalId for the type.
     * @param string $externalId
     * @return Category
     */
    public function setExternalId(string $externalId): Category
    {
        $this->externalId = $externalId;

        return $this;
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
     * Sets the Id of this document.
     * @param string $id
     * @return Category
     */
    public function setId(string $id): Category
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Returns the LastModifiedAt for the type.
     * @return string
     */
    public function getLastModifiedAt(): string
    {
        return $this->lastModifiedAt;
    }

    /**
     * Sets the LastModifiedAt of this document.
     * @param string $lastModifiedAt
     * @return Category
     */
    public function setLastModifiedAt(string $lastModifiedAt): Category
    {
        $this->lastModifiedAt = $lastModifiedAt;

        return $this;
    }

    /**
     * Returns the MetaDiscription of this document.
     * @return string
     */
    public function getMetaDescription(): string
    {
        return $this->metaDescription;
    }

    /**
     * Returns the MetaKeywords of this document.
     * @return string
     */
    public function getMetaKeywords(): string
    {
        return $this->metaKeywords;
    }

    /**
     * Sets the MetaKeywords for the type.
     * @param string $metaKeywords
     * @return Category
     */
    public function setMetaKeywords(string $metaKeywords): Category
    {
        $this->metaKeywords = $metaKeywords;

        return $this;
    }

    /**
     * Returns the MetaTitle for the type.
     * @return string
     */
    public function getMetaTitle(): string
    {
        return $this->metaTitle;
    }

    /**
     * Ste sthe Mata Title for the type.
     * @param string $metaTitle
     * @return Category
     */
    public function setMetaTitle(string $metaTitle): Category
    {
        $this->metaTitle = $metaTitle;

        return $this;
    }

    /**
     * Returns the Name for the type.
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Sets the Name for the type.
     * @param string $name
     * @return Category
     */
    public function setName(string $name): Category
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Returns the OrderHint for the type.
     * @return string
     */
    public function getOrderHint(): string
    {
        return $this->orderHint;
    }

    /**
     * Sets the OrderHint for the type.
     * @param string $orderHint
     * @return Category
     */
    public function setOrderHint(string $orderHint): Category
    {
        $this->orderHint = $orderHint;

        return $this;
    }

    /**
     * Returns the Parent for the type.
     * @return string
     */
    public function getParent(): string
    {
        return $this->parent;
    }

    /**
     * Sets the Parent for the type.
     * @param string $parent
     * @return Category
     */
    public function setParent(string $parent): Category
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Returns the Slug for the type.
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * Sets the Slug for the type.
     * @param string $slug
     * @return Category
     */
    public function setSlug(string $slug): Category
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Returns the Version of this document.
     * @return string
     */
    public function getVersion(): int
    {
        return $this->version;
    }

    /**
     *Sets the Version of this document.
     * @param string $version
     * @return Category
     */
    public function setVersion(string $version): Category
    {
        $this->version = $version;

        return $this;
    }

    /**
     * Sets the MateDescription for the type.
     * @param string $metaDescription
     * @return Category
     */
    public function setmMetaDescription(string $metaDescription): Category
    {
        $this->metaDescription = $metaDescription;

        return $this;
    }

}