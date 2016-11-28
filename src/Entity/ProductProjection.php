<?php
namespace BestIt\CommercetoolsODM\Entity;

use BestIt\CommercetoolsODM\Mapping\Annotations as Commercetools;

/**
 * Entity for Product Projection.
 * @Commercetools\Entity(requestMap=@Commercetools\RequestMap(
 *     defaultNamespace="Commercetools\Core\Request\Products",
 *     findById="ProductProjectionByIdGetRequest",
 *     findByKey="ProductProjectionByKeyGetRequest",
 *     query="ProductProjectionQueryRequest"
 * ))
 * @Commercetools\Repository("BestIt\CommercetoolsODM\Model\ProductProjectionRepository")
 * @package BestIt\CommercetoolsODM
 * @subpackage Entity
 * @version $id$
 */
class ProductProjection

{
    /**
     * The Categories for the type.
     * @Commercetools\Field(type="array")
     * @Commercetools\Categories
     * @var array
     */
    private $categories = [];
    /**
     * The CategoryOrderHints for the type.
     * @Commercetools\Field(type="") TODO CategorieOrderHints
     * @Commercetools\CategoryOrderHints
     * @var
     */
    private $categoryOrderHints;
    /**
     * The CreatedAt for the type.
     * @Commercetools\Field(type="datetime")
     * @Commercetools\CreatedAt
     * @var \DateTime
     */
    private $createdAt;
    /**
     * The Description for the type.
     * @Commercetools\Field(type="") TODO LocalizedString
     * @Commercetools\Description
     * @var
     */
    private $description;
    /**
     * The HasStagedChanges for the type.
     * @Commercetools\Field(type="Boolean")
     * @Commercetools\HasStagedChanges
     * @var boolean
     */
    private $hasStagedChanges = false;
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
     * The LastModifLiedAt for the type.
     * @Commercetools\Field(type="datetime")
     * @Commercetools\LastModifiedAt
     * @var \DateTime
     */
    private $lastModifiedAt;
    /**
     * The MasterVariant for the type.
     * @Commercetools\Field(type="") TODO ProductVariant
     * @Commercetools\MasterVariant
     * @var
     */
    private $masterVariant;
    /**
     * The MetaDescription for the type.
     * @Commercetools\Field(type="") TODO LocalizedString
     * @Commercetools\MetaDescription
     * @var
     */
    private $metaDescription;
    /**
     * The MetaKeywords for the type.
     * @Commercetools\Field(type="") TODO LocalizedString
     * @Commercetools\MetaKeywords
     * @var
     */
    private $metaKeywords;
    /**
     * The MetaTitle for the type.
     * @Commercetools\Field(type="") TODO LocalizedString
     * @Commercetools\MetaTitle
     * @var
     */
    private $metaTitle;
    /**
     * The Name for the type.
     * @Commercetools\Field(type="") TODO LocalizedString
     * @Commercetools\Name
     * @var
     */
    private $name;
    /**
     * The ProductType for the type.
     * @Commercetools\Field(type="") TODO Reference
     * @Commercetools\ProductType
     * @var
     */
    private $productType;
    /**
     * The Published for the type.
     * @Commercetools\Field(type="Boolean")
     * @Commercetools\Published
     * @var boolean
     */
    private $published = false;
    /**
     * The ReviewRatingStatistics for the type.
     * @Commercetools\Field(type="") TODO ReviewRatingStatistics
     * @Commercetools\ReviewRatingStatistics
     * @var
     */
    private $reviewRatingStatistics;
    /**
     * The SearchKeywords for the type.
     * @Commercetools\Field(type="") TODO SearchKeywords
     * @Commercetools\SearchKeywords
     * @var
     */
    private $searchKeywords;
    /**
     * The Slug for the type.
     * @Commercetools\Field(type="") TODO LocalizedString
     * @Commercetools\Slug
     * @var
     */
    private $slug;
    /**
     * The State for the type.
     * @Commercetools\Field(type="") TODO Reference
     * @Commercetools\State
     * @var
     */
    private $state;
    /**
     * The TaxCategory for the type.
     * @Commercetools\Field(type="") TODO Reference
     * @Commercetools\TaxCategory
     * @var
     */
    private $taxCategory;
    /**
     * The Variants for the type.
     * @Commercetools\Field(type="array")
     * @Commercetools\Variants
     * @var array
     */
    private $variants = [];
    /**
     * The Version for the type.
     * @Commercetools\Field(type="int")
     * @Commercetools\Version
     * @var int
     */
    private $version = 0;

    /**
     * gets Categories
     *
     * @return array
     */
    public function getCategories(): array
    {
        return $this->categories;
    }

    /**
     * gets CategoryOrderHints
     *
     * @return mixed
     */
    public function getCategoryOrderHints()
    {
        return $this->categoryOrderHints;
    }

    /**
     * gets CreatedAt
     *
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * gets Description
     *
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * gets Id
     *
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * gets Key
     *
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * gets LastModifiedAt
     *
     * @return \DateTime
     */
    public function getLastModifiedAt(): \DateTime
    {
        return $this->lastModifiedAt;
    }

    /**
     * gets MasterVariant
     *
     * @return mixed
     */
    public function getMasterVariant()
    {
        return $this->masterVariant;
    }

    /**
     * gets MetaDescription
     *
     * @return mixed
     */
    public function getMetaDescription()
    {
        return $this->metaDescription;
    }

    /**
     * gets MetaKeywords
     *
     * @return mixed
     */
    public function getMetaKeywords()
    {
        return $this->metaKeywords;
    }

    /**
     * gets MetaTitle
     *
     * @return mixed
     */
    public function getMetaTitle()
    {
        return $this->metaTitle;
    }

    /**
     * gets Name
     *
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * gets ProductType
     *
     * @return mixed
     */
    public function getProductType()
    {
        return $this->productType;
    }

    /**
     * gets ReviewRatingStatistics
     *
     * @return mixed
     */
    public function getReviewRatingStatistics()
    {
        return $this->reviewRatingStatistics;
    }

    /**
     * gets SearchKeywords
     *
     * @return mixed
     */
    public function getSearchKeywords()
    {
        return $this->searchKeywords;
    }

    /**
     * gets Slug
     *
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * gets State
     *
     * @return mixed
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * gets TaxCategory
     *
     * @return mixed
     */
    public function getTaxCategory()
    {
        return $this->taxCategory;
    }

    /**
     * gets Variants
     *
     * @return array
     */
    public function getVariants(): array
    {
        return $this->variants;
    }

    /**
     * gets Version
     *
     * @return int
     */
    public function getVersion(): int
    {
        return $this->version;
    }

    /**
     * iss HasStagedChanges
     *
     * @return boolean
     */
    public function isHasStagedChanges(): bool
    {
        return $this->hasStagedChanges;
    }

    /**
     * iss Published
     *
     * @return boolean
     */
    public function isPublished(): bool
    {
        return $this->published;
    }

    /**
     * Sets Categories
     *
     * @param array $categories
     */
    public function setCategories(array $categories)
    {
        $this->categories = $categories;
    }

    /**
     * Sets CategoryOrderHints
     *
     * @param mixed $categoryOrderHints
     */
    public function setCategoryOrderHints($categoryOrderHints)
    {
        $this->categoryOrderHints = $categoryOrderHints;
    }

    /**
     * Sets CreatedAt
     *
     * @param \DateTime $createdAt
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * Sets Description
     *
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Sets HasStagedChanges
     *
     * @param boolean $hasStagedChanges
     */
    public function setHasStagedChanges(bool $hasStagedChanges)
    {
        $this->hasStagedChanges = $hasStagedChanges;
    }

    /**
     * Sets Id
     *
     * @param string $id
     */
    public function setId(string $id)
    {
        $this->id = $id;
    }

    /**
     * Sets Key
     *
     * @param string $key
     */
    public function setKey(string $key)
    {
        $this->key = $key;
    }

    /**
     * Sets LastModifiedAt
     *
     * @param \DateTime $lastModifiedAt
     */
    public function setLastModifiedAt(\DateTime $lastModifiedAt)
    {
        $this->lastModifiedAt = $lastModifiedAt;
    }

    /**
     * Sets MasterVariant
     *
     * @param mixed $masterVariant
     */
    public function setMasterVariant($masterVariant)
    {
        $this->masterVariant = $masterVariant;
    }

    /**
     * Sets MetaDescription
     *
     * @param mixed $metaDescription
     */
    public function setMetaDescription($metaDescription)
    {
        $this->metaDescription = $metaDescription;
    }

    /**
     * Sets MetaKeywords
     *
     * @param mixed $metaKeywords
     */
    public function setMetaKeywords($metaKeywords)
    {
        $this->metaKeywords = $metaKeywords;
    }

    /**
     * Sets MetaTitle
     *
     * @param mixed $metaTitle
     */
    public function setMetaTitle($metaTitle)
    {
        $this->metaTitle = $metaTitle;
    }

    /**
     * Sets Name
     *
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Sets ProductType
     *
     * @param mixed $productType
     */
    public function setProductType($productType)
    {
        $this->productType = $productType;
    }

    /**
     * Sets Published
     *
     * @param boolean $published
     */
    public function setPublished(bool $published)
    {
        $this->published = $published;
    }

    /**
     * Sets ReviewRatingStatistics
     *
     * @param mixed $reviewRatingStatistics
     */
    public function setReviewRatingStatistics($reviewRatingStatistics)
    {
        $this->reviewRatingStatistics = $reviewRatingStatistics;
    }

    /**
     * Sets SearchKeywords
     *
     * @param mixed $searchKeywords
     */
    public function setSearchKeywords($searchKeywords)
    {
        $this->searchKeywords = $searchKeywords;
    }

    /**
     * Sets Slug
     *
     * @param mixed $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * Sets State
     *
     * @param mixed $state
     */
    public function setState($state)
    {
        $this->state = $state;
    }

    /**
     * Sets TaxCategory
     *
     * @param mixed $taxCategory
     */
    public function setTaxCategory($taxCategory)
    {
        $this->taxCategory = $taxCategory;
    }

    /**
     * Sets Variants
     *
     * @param array $variants
     */
    public function setVariants(array $variants)
    {
        $this->variants = $variants;
    }

    /**
     * Sets Version
     *
     * @param int $version
     */
    public function setVersion(int $version)
    {
        $this->version = $version;
    }
}