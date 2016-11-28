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
class ProductProjection

{
    /**
     * The Id for the type.
     * @Commercetools\Field(type="string")
     * @Commercetools\Id
     * @var string
     */
    private $id = '';
    /**
     * The Key for the type.
     * @Commercetools\Field(type="string")
     * @Commercetools\Key
     * @var string
     */
    private $key = '';
    /**
     * The Version for the type.
     * @Commercetools\Field(type="string")
     * @Commercetools\Version
     * @var string
     */
    private $version = 0;
    /**
     * The CreatedAt for the type.
     * @Commercetools\Field(type="string")
     * @Commercetools\CreatedAt
     * @var string
     */
    private $createdAt = '';
    /**
     * The LastModifLiedAt for the type.
     * @Commercetools\Field(type="string")
     * @Commercetools\LastModifLiedAt
     * @var string
     */
    private $lastModifiedAt = '';
    /**
     * The ProductType for the type.
     * @Commercetools\Field(type="string")
     * @Commercetools\ProductType
     * @var string
     */
    private $productType = '';
    /**
     * The Name for the type.
     * @Commercetools\Field(type="string")
     * @Commercetools\Name
     * @var string
     */
    private $name = '';
    /**
     * The Description for the type.
     * @Commercetools\Field(type="string")
     * @Commercetools\Description
     * @var string
     */
    private $description = '';
    /**
     * The Slug for the type.
     * @Commercetools\Field(type="string")
     * @Commercetools\Slug
     * @var string
     */
    private $slug = '';
    /**
     * The Categories for the type.
     * @Commercetools\Field(type="string")
     * @Commercetools\Categories
     * @var string
     */
    private $categories = '';
    /**
     * The CategoryOrderHints for the type.
     * @Commercetools\Field(type="string")
     * @Commercetools\CategoryOrderHints
     * @var string
     */
    private $categoryOrderHints = '';
    /**
     * The MetaTitle for the type.
     * @Commercetools\Field(type="string")
     * @Commercetools\MetaTitle
     * @var string
     */
    private $metaTitle = '';
    /**
     * The MetaDescription for the type.
     * @Commercetools\Field(type="string")
     * @Commercetools\MetaDescription
     * @var string
     */
    private $metaDescription = '';
    /**
     * The MetaKeywords for the type.
     * @Commercetools\Field(type="string")
     * @Commercetools\MetaKeywords
     * @var string
     */
    private $metaKeywords = '';
    /**
     * The SearchKeywords for the type.
     * @Commercetools\Field(type="string")
     * @Commercetools\SearchKeywords
     * @var string
     */
    private $searchKeywords = '';
    /**
     * The HasStagedChanges for the type.
     * @Commercetools\Field(type="string")
     * @Commercetools\HasStagedChanges
     * @var string
     */
    private $hasStagedChanges = '';
    /**
     * The Published for the type.
     * @Commercetools\Field(type="string")
     * @Commercetools\Published
     * @var string
     */
    private $published = '';
    /**
     * The MasterVariant for the type.
     * @Commercetools\Field(type="string")
     * @Commercetools\MasterVariant
     * @var string
     */
    private $masterVariant = '';
    /**
     * The Variants for the type.
     * @Commercetools\Field(type="string")
     * @Commercetools\Variants
     * @var string
     */
    private $variants = '';
    /**
     * The TaxCategory for the type.
     * @Commercetools\Field(type="string")
     * @Commercetools\TaxCategory
     * @var string
     */
    private $taxCategory = '';
    /**
     * The State for the type.
     * @Commercetools\Field(type="string")
     * @Commercetools\State
     * @var string
     */
    private $state = '';
    /**
     * The ReviewRatingStatistics for the type.
     * @Commercetools\Field(type="string")
     * @Commercetools\ReviewRatingStatistics
     * @var string
     */
    private $reviewRatingStatistics= '';

    /**
     * Returns the Id of the type.
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
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
     * Returns the Version for the type.
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
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
     * Returns the LastModifiedAt for the type.
     * @return string
     */
    public function getLastModifiedAt(): string
    {
        return $this->lastModifiedAt;
    }

    /**
     * Returns the ProductType for the type.
     * @return string
     */
    public function getProductType(): string
    {
        return $this->productType;
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
     * Returns the Description for the type.
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
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
     * Returns the Categories for the type.
     * @return string
     */
    public function getCategories(): string
    {
        return $this->categories;
    }

    /**
     * Returns the CategoryOrderHints for the type.
     * @return string
     */
    public function getCategoryOrderHints(): string
    {
        return $this->categoryOrderHints;
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
     * Returns the MetaKeywords for the type.
     * @return string
     */
    public function getMetaKeywords(): string
    {
        return $this->metaKeywords;
    }

    /**
     * Returns the SearchKeywords for the type.
     * @return string
     */
    public function getSearchKeywords(): string
    {
        return $this->searchKeywords;
    }

    /**
     * Returns the HasStagedChanges for the type.
     * @return string
     */
    public function getHasStagedChanges(): string
    {
        return $this->hasStagedChanges;
    }

    /**
     * Returns the Published for the type.
     * @return string
     */
    public function getPublished(): string
    {
        return $this->published;
    }

    /**
     * Returns the MasterVariant for the type.
     * @return string
     */
    public function getMasterVariant(): string
    {
        return $this->masterVariant;
    }

    /**
     * Returns the Variants for the type.
     * @return string
     */
    public function getVariants(): string
    {
        return $this->variants;
    }

    /**
     * Returns the TaxCategory for the type.
     * @return string
     */
    public function getTaxCategory(): string
    {
        return $this->taxCategory;
    }

    /**
     * Returns the State for the type.
     * @return string
     */
    public function getState(): string
    {
        return $this->state;
    }

    /**
     * Returns the ReviewRatingStatistics for the type.
     * @return string
     */
    public function getReviewRatingStatistics(): string
    {
        return $this->reviewRatingStatistics;
    }


    /**
     * Sets the for the type.
     * @param string $id
     * @return ProductProjection
     */
    public function setiId(string $id): ProductProjection
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Sets the Key for the type.
     * @param string $key
     * @return ProductProjection
     */
    public function setKey(string $key): ProductProjection
    {
        $this->key = $key;

        return $this;
    }

    /**
     * Sets the Version for the type.
     * @param string $version
     * @return ProductProjection
     */
    public function setVersion(string $version): ProductProjection
    {
        $this->version = $version;

        return $this;
    }

    /**
     * Sets the CreatedAt for the type.
     * @param string $createdAt
     * @return ProductProjection
     */
    public function setCreatedAt(string $createdAt): ProductProjection
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Sets the LastModifiedAt for the type.
     * @param string $lastModifiedAt
     * @return ProductProjection
     */
    public function setLastModifiedAt(string $lastModifiedAt): ProductProjection
    {
        $this->lastModifiedAt = $lastModifiedAt;

        return $this;
    }

    /**
     * Sets the ProductType for the type.
     * @param string $productType
     * @return ProductProjection
     */
    public function setProductType(string $productType): ProductProjection
    {
        $this->productType = $productType;

        return $this;
    }

    /**
     * Sets the Name for the type.
     * @param string $name
     * @return ProductProjection
     */
    public function setName(string $name): ProductProjection
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Sets the Description for the type.
     * @param string $description
     * @return ProductProjection
     */
    public function setDescription(string $description): ProductProjection
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Sets the Slug for the type.
     * @param string $slug
     * @return ProductProjection
     */
    public function setSlug(string $slug): ProductProjection
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Sets the Categories for the type.
     * @param string $categories
     * @return ProductProjection
     */
    public function setCategories(string $categories): ProductProjection
    {
        $this->categories = $categories;

        return $this;
    }

    /**
     * Sets the CategoryOOrderHints for the type.
     * @param string $categoryOrderHints
     * @return ProductProjection
     */
    public function setCategoryOrderHints(string $categoryOrderHints): ProductProjection
    {
        $this->categoryOrderHints = $categoryOrderHints;

        return $this;
    }

    /**
     * Sets the MetaTitle for the type.
     * @param string $metaTitle
     * @return ProductProjection
     */
    public function setMetaTitle(string $metaTitle): ProductProjection
    {
        $this->metaTitle = $metaTitle;

        return $this;
    }

    /**
     * Sets the for the type.
     * @param string $metaDescription
     * @return ProductProjection
     */
    public function setMetaDescription(string $metaDescription): ProductProjection
    {
        $this->metaDescription = $metaDescription;

        return $this;
    }

    /**
     * Sets the MetaKeywords for the type.
     * @param string $metaKeywords
     * @return ProductProjection
     */
    public function setMetaKeywords(string $metaKeywords): ProductProjection
    {
        $this->metaKeywords = $metaKeywords;

        return $this;
    }

    /**
     * Sets the SearchKeywords for the type.
     * @param string $searchKeywords
     * @return ProductProjection
     */
    public function setSearchKeywords(string $searchKeywords): ProductProjection
    {
        $this->searchKeywords = $searchKeywords;

        return $this;
    }

    /**
     * Sets the HasStagedChanges for the type.
     * @param string $hasStagedChanges
     * @return ProductProjection
     */
    public function setHasStagedChanges(string $hasStagedChanges): ProductProjection
    {
        $this->hasStagedChanges = $hasStagedChanges;

        return $this;
    }

    /**
     * Sets the Published for the type.
     * @param string $published
     * @return ProductProjection
     */
    public function setPublished(string $published): ProductProjection
    {
        $this->published = $published;

        return $this;
    }

    /**
     * Sets the MasterVariant for the type.
     * @param string $masterVariant
     * @return ProductProjection
     */
    public function setMasterVariant(string $masterVariant): ProductProjection
    {
        $this->masterVariant = $masterVariant;

        return $this;
    }

    /**
     * Sets the Variants for the type.
     * @param string $variants
     * @return ProductProjection
     */
    public function setVariants(string $variants): ProductProjection
    {
        $this->variants = $variants;

        return $this;
    }

    /**
     * Sets the TaxCategory for the type.
     * @param string $taxCategory
     * @return ProductProjection
     */
    public function setTaxCategory(string $taxCategory): ProductProjection
    {
        $this->taxCategory = $taxCategory;

        return $this;
    }

    /**
     * Sets the State for the type.
     * @param string $state
     * @return ProductProjection
     */
    public function setState(string $state): ProductProjection
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Sets the ReviewRatingStatistics for the type.
     * @param string $reviewRatingStatistics
     * @return ProductProjection
     */
    public function setReviewRatingStatistics(string $reviewRatingStatistics): ProductProjection
    {
        $this->reviewRatingStatistics = $reviewRatingStatistics;

        return $this;
    }

}