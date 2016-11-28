<?php
namespace BestIt\CommercetoolsODM\Entity;

use BestIt\CommercetoolsODM\Mapping\Annotations as Commercetools;

/**
 * Entity for Categories.
 * @Commercetools\DraftClass("Commercetools\Core\Model\Category\CategoryDraft")
 * @Commercetools\Entity(requestMap=@Commercetools\RequestMap(
 *     defaultNamespace="Commercetools\Core\Request\Categories",
 *     findById="CategoryByIdRequest"
 *     query="CategoryCreateRequest"
 *     create="CategoryQueryRequest"
 *     update="CategoryUpdateRequest"
 *     delete="CategoryDeleteRequest"
 * ))
 * @Commercetools\Repository("BestIt\CommercetoolsODM\Model\CategoryRepository")
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
    private $ancestors = [];

    /**
     *The CreatedAt for the type.
     * @Commercetools\Field(type="datetime")
     * @Commercetools\CreatedAt
     * @var \DateTime
     */
    private $createdAt;

    /**
     *The Costum for the type.
     * @Commercetools\Field(type="")TODO CustomFields
     * @Commercetools\Costum
     * @var
     */
    private $custom = '';

    /**
     *The Description for the type.
     * @Commercetools\Field(type="")TODO LocalizedString
     * @Commercetools\Description
     * @var
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
     * @var \DateTime
     */
    private $lastModifiedAt;

    /**
     *The MetaDiscribtion of this document.
     * @Commercetools\Field(type="") TODO LocalizedString
     * @Commercetools\MetaDescribition
     * @var
     */
    private $metaDescription = '';
    /**
     *The MetaKeywords for the type.
     * @Commercetools\Field(type="") TODO LocalizedString
     * @Commercetools\MetaKeywords
     * @var
     */
    private $metaKeywords = '';
    /**
     *The MetaTitle of this document.
     * @Commercetools\Field(type="") TODO LocalizedString
     * @Commercetools\MetaTitle
     * @var
     */
    private $metaTitle = '';
    /**
     * The Name for the type.
     * @Commercetools\Field(type="") TODO LocalizedString
     * @Commercetools\Name
     * @var
     */
    private $name = '';

    /**
     *The OrderHint for the type.
     * @Commercetools\Field(type="string")
     * @Commercetools\OrderHint
     * @var string
     */
    private $orderHint = '';

    /**
     * The Parent for the type.
     * @Commercetools\Field(type="")TODO Reference
     * @Commercetools\Parent
     * @var
     */
    private $parent = '';

    /**
     *The Slug for the type
     * @Commercetools\Field(type="") TODO LocalizedString
     * @Commercetools\Slug
     * @var
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
     * Returns the CreatedAt for the type.
     * @return string
     */
    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    /** Returns the Costum of the type.
     * @return string
     */
    public function getCustom(): string
    {
        return $this->custom;
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
     * Returns the ExternalId of this document.
     * @return string
     */
    public function getExternalId(): string
    {
        return $this->externalId;
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
     * Returns the LastModifiedAt for the type.
     * @return string
     */
    public function getLastModifiedAt(): string
    {
        return $this->lastModifiedAt;
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
     * Returns the MetaTitle for the type.
     * @return string
     */
    public function getMetaTitle(): string
    {
        return $this->metaTitle;
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
     * Returns the OrderHint for the type.
     * @return string
     */
    public function getOrderHint(): string
    {
        return $this->orderHint;
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
     * Returns the Slug for the type.
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * Returns the Version of this document.
     * @return int
     */
    public function getVersion(): int
    {
        return $this->version;
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
     * Sets the CreatedAt for the type.
     * @param string $createdAt
     * @return Category
     */
    public function setCreatedAt(string $createdAt): Category
    {
        $this->createdAt = $createdAt;

        return $this;
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
     * Sets the MateDescription for the type.
     * @param string $metaDescription
     * @return Category
     */
    public function setMetaDescription(string $metaDescription): Category
    {
        $this->metaDescription = $metaDescription;

        return $this;
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
     *Sets the Version of this document.
     * @param string $version
     * @return Category
     */
    public function setVersion(string $version): Category
    {
        $this->version = $version;

        return $this;
    }
}
