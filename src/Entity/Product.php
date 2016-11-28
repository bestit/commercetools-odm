<?php
namespace BestIt\CommercetoolsODM\Entity;

use BestIt\CommercetoolsODM\Mapping\Annotations as Commercetools;

class Product
{
    /**
     * The CreatedAt for the type.
     * @Commercetools\Field(type="datetime")
     * @Commercetools\CreatedAt
     * @var \DateTime
     */
    private $createdAt;

    /**
     * The unique ID of the product.
     * @Commercetools\Field(type="string")
     * @Commercetools\Id
     * @var string
     */
    private $id = '';

    /**
     * User-specific unique identifier for the product. Product keys are different from product variant keys.
     * @Commercetools\Field(type="string")
     * @Commercetools\Key
     * @var string
     */
    private $key = '';

    /**
     * The LastModifiedAt for the type.
     * @Commercetools\Field(type="datetime")
     * @Commercetools\LastModifiedAt
     * @var \DateTime
     */
    private $lastModifiedAt;

    /**
     * The product data in the master catalog.
     * @Commercetools\Field(type="") TODO ProductCatalogData
     * @Commercetools\MasterData
     * @var
     */
    private $masterData;

    /**
     * @Commercetools\Field(type="") TODO Refenrence
     * @Commercetools\ProductType
     * @var
     */
    private $productType;

    /**
     * Statistics about the review ratings taken into account for this product.
     * @Commercetools\Field(type="") TODO ReviewRatingStatistics
     * @Commercetools\ReviewRatingStatistics
     * @var
     */
    private $reviewRatingStatistics;

    /**
     * @Commercetools\Field(type="") TODO Reference
     * @Commercetools\State
     * @var
     */
    private $state;

    /**
     * @Commercetools\Field(type="") TODO Reference
     * @Commercetools\TaxCategory
     * @var
     */
    private $taxCategory;

    /**
     * The current version of the product.
     * @Commercetools\Field(type="int")
     * @Commercetools\Version
     * @var
     */
    private $version = 0;

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
     * gets MasterData
     *
     * @return mixed
     */
    public function getMasterData()
    {
        return $this->masterData;
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
     * gets Version
     *
     * @return mixed
     */
    public function getVersion()
    {
        return $this->version;
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
     * Sets MasterData
     *
     * @param mixed $masterData
     */
    public function setMasterData($masterData)
    {
        $this->masterData = $masterData;
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
     * Sets ReviewRatingStatistics
     *
     * @param mixed $reviewRatingStatistics
     */
    public function setReviewRatingStatistics($reviewRatingStatistics)
    {
        $this->reviewRatingStatistics = $reviewRatingStatistics;
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
     * Sets Version
     *
     * @param mixed $version
     */
    public function setVersion($version)
    {
        $this->version = $version;
    }
}