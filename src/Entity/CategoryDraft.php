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
class CategoryDraft
{
    /**
     * The Name of the type.
     * @var string
     */
    private $name = '';
    /**
     * The Description of the type.
     * @var string
     */
    private $description = '';
    /**
     * The Parent of the type.
     * @var string
     */
    private $parent = '';
    /**
     * The Slug for the type.
     * @var string
     */
    private $slug = '';
    /**
     * The OrderHint for the type.
     * @var string
     */
    private $orderHint = '';
    /**
     * The ExternalId for the type.
     * @var string
     */
    private $externalId = '';
    /**
     * The MetaTitle for the type.
     * @var string
     */
    private $metaTitle = '';
    /**
     * The Meta Description for the type.
     * @var string
     */
    private $metaDescription = '';
    /**
     * The MetaKeywords for the type.
     * @var string
     */
    private $metaKeywords = '';
    /**
     * The Costum for the type.
     * @var string
     */
    private $custom = '';

    /**
     * Returns the Name for the type.
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Returns the Description dor the type.
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Returns the Parent for the type
     * @return string
     */
    public function getParent(): string
    {
        return $this->parent;
    }

    /**
     * Returns the Sluf for the type.
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * Returns teh OrderHint for the type.
     * @return string
     */
    public function getOrderHint(): string
    {
        return $this->orderHint;
    }

    /**
     * Returns the ExternalId for the type.
     * @return string
     */
    public function getExternalId(): string
    {
        return $this->externalId;
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
     * Returns the MetaDescription for the type.
     * @return string
     */
    public function getMetaDescription(): string
    {
        return $this->metaDescription;
    }

    /**
     *  Returns the MetaKeywords for the type.
     * @return string
     */
    public function getMetaKeywords(): string
    {
        return $this->metaKeywords;
    }

    /**
     * Returns the Custom for the type.
     * @return string
     */
    public function getCustom(): string
    {
        return $this->custom;
    }


    /**
     * Sets the Name for the type.
     * @param string $name
     * @return CategoryDraft
     */
    public function setName(string $name): CategoryDraft

    {
        $this->name = $name;

        return $this;
    }

    /**
     * Sets the Description for the type.
     * @param string $description
     * @return CategoryDraft
     */
    public function setDescription(string $description): CategoryDraft

    {
        $this->description = $description;

        return $this;
    }

    /**
     * Sets the Parent for the type.
     * @param string $parent
     * @return CategoryDraft
     */
    public function setParent(string $parent): CategoryDraft

    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Sets the Slug for the type.
     * @param string $slug
     * @return CategoryDraft
     */
    public function setSlug(string $slug): CategoryDraft

    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Sets the OrderHint for the type.
     * @param string $orderHint
     * @return CategoryDraft
     */
    public function setOrderHint(string $orderHint): CategoryDraft

    {
        $this->orderHint = $orderHint;

        return $this;
    }

    /**
     * Sets the ExtarnalId for the type.
     * @param string $externalId
     * @return CategoryDraft
     */
    public function setExternalId(string $externalId): CategoryDraft

    {
        $this->externalId = $externalId;

        return $this;
    }

    /**
     * Sets the MetaTitle for the type.
     * @param string $metaTitle
     * @return CategoryDraft
     */
    public function setMetaTitle(string $metaTitle): CategoryDraft

    {
        $this->metaTitle = $metaTitle;

        return $this;
    }

    /**
     * Sets the MetaDescription for the type.
     * @param string $metaDescription
     * @return CategoryDraft
     */
    public function setMetaDescription(string $metaDescription): CategoryDraft

    {
        $this->metaDescription = $metaDescription;

        return $this;
    }

    /**
     * Sets the MetaKeywords for the type.
     * @param string $metaKeywords
     * @return CategoryDraft
     */
    public function setMetaKeywords(string $metaKeywords): CategoryDraft

    {
        $this->metaKeywords = $metaKeywords;

        return $this;
    }

    /**
     * Sets the Custom for the type.
     * @param string $custom
     * @return CategoryDraft
     */
    public function setCustom(string $custom): CategoryDraft

    {
        $this->custom = $custom;

        return $this;
    }

}