<?php
namespace BestIt\CommercetoolsODM\Entity;

use BestIt\CommercetoolsODM\Mapping\Annotations as Commercetools;

class Product
{
    /** TODO @var!!
     * The CreatedAt for the type.
     * @Commercetools\Field(type="datetime")
     * @Commercetools\CreatedAT
     * @var
     */
    private $createdAt = '';
    /**
     * The unique ID of the product.
     * @Commercetools\Field(type="string")
     * @Commercetools\Id
     * @var string
     */
    private $id = '';
    /**
     *User-specific unique identifier for the product. Product keys are different from product variant keys.
     * @Commercetools\Field(type="string")
     * @Commercetools\Key
     * @var string
     */
    private $key = '';
    /**
     * The LastModifiedAt for the type.
     * @Commercetools\Field(type="datetime")
     * @Commercetools\LastModifiedAt
     * @var
     */
    private $lastModifiedAt = '';
    /**
     * The product data in the master catalog.
     * @Commercetools\Field(type="") TODO
     * @Commercetools\MasterData
     * @var
     */
    private $masterData = '';
    /**
     * TODO
     * @Commercetools\Field(type="")
     * @Commercetools\ProductType
     * @var
     */
    private $productType = '';
    /**
     * Statistics about the review ratings taken into account for this product.
     * @Commercetools\Field(type="") TODO
     * @Commercetools\ReviewRatingStatistics
     * @var
     */
    private $reviewRatingStatistics = '';
    /**
     * TODO
     * @Commercetools\Field(type="")
     * @Commercetools\State
     * @var
     */
    private $state = '';
    /**
     * TODO
     * @Commercetools\Field(type="")
     * @Commercetools\TaxCategory
     * @var
     */
    private $taxCategory = '';
    /**
     * The current version of the product.
     * @Commercetools\Field(type="int")
     * @Commercetools\Version
     * @var
     */
    private $version = '';

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
     */
    public function setCreatedAt(string $createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * Returns the Id for the type.
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Sets the Id for the type.
     * @param string $id
     */
    public function setId(string $id)
    {
        $this->id = $id;
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
     * Sets the Key for the type.
     * @param string $key
     */
    public function setKey(string $key)
    {
        $this->key = $key;
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
     *  Sets the LastModifiedAt for the type.
     * @param string $lastModifiedAt
     */
    public function setLastModifiedAt(string $lastModifiedAt)
    {
        $this->lastModifiedAt = $lastModifiedAt;
    }

    /**
     * Returns the MasterData for the type.
     * @return string
     */
    public function getMasterData(): string
    {
        return $this->masterData;
    }

    /**
     * Sets the MasterData for the type.
     * @param string $masterData
     */
    public function setMasterData(string $masterData)
    {
        $this->masterData = $masterData;
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
     * Sets the ProductType  for the type.
     * @param string $productType
     */
    public function setProductType(string $productType)
    {
        $this->productType = $productType;
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
     * Sets the ReviewRatingStatistics  for the type.
     * @param string $reviewRatingStatistics
     */
    public function setReviewRatingStatistics(string $reviewRatingStatistics)
    {
        $this->reviewRatingStatistics = $reviewRatingStatistics;
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
     * Sets the State for the type.
     * @param string $state
     */
    public function setState(string $state)
    {
        $this->state = $state;
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
     * Sets the TaxCategory for the type.
     * @param string $taxCategory
     */
    public function setTaxCategory(string $taxCategory)
    {
        $this->taxCategory = $taxCategory;
    }

    /**
     * Returns the Version for the type.
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**Sets the Version for the type.
     * @param string $version
     */
    public function setVersion(string $version)
    {
        $this->version = $version;
    }

}