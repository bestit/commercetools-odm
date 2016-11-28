<?php
namespace BestIt\CommercetoolsODM\Entity;

use BestIt\CommercetoolsODM\Mapping\Annotations as Commercetools;

class ProductVariant
{
    /**
     * The Assets for the type.
     * @Commercetools\Field(type="array")
     * @Commercetools\Assets
     * @var array
     */
    private $assets = [];

    /**
     * The Attributes for the type.
     * @Commercetools\Field(type="array")
     * @Commercetools\Attributes
     * @var array
     */
    private $attributes = [];

    /**
     * The Availability is set if the variant is tracked by the inventory.
     * The field might not contain the latest inventory state (it is eventually consistent) and
     * can be used as an optimization to reduce calls to the inventory service.
     * @Commercetools\Field(type="") TODO ProductVariantAvailability
     * @Commercetools\Availability
     * @var
     */
    private $availability;

    /**
     * The sequential ID of the variant within the product.
     * @Commercetools\Field(type="int")
     * @Commercetools\Id
     * @var int
     */
    private $id = 0;

    /**
     * The Images for the type.
     * @Commercetools\Field(type="array")
     * @Commercetools\Images
     * @var array
     */
    private $images = [];

    /**
     * Only appears in response to a Product Projection Search request
     * to mark this variant as one that matches the search query.
     * @Commercetools\Field(type="boolean")
     * @Commercetools\IsMatchingVariant
     * @var boolean
     */
    private $isMatchingVariant = false;

    /**
     * User-specific unique identifier for the variant. Product variant keys are different from product keys.
     * @Commercetools\Field(type="string")
     * @Commercetools\Key
     * @var string
     */
    private $key = '';

    /**
     *  Only appears when price selection is used. This field cannot be used in a query predicate.
     * @Commercetools\Field(type="") TODO Price
     * @Commercetools\Price
     * @var
     */
    private $price;

    /**
     * The prices of the variant. The prices does not contain two prices for the same price scope
     * (same currency, country, customer group and channel).
     * @Commercetools\Field(type="array")
     * @Commercetools\Prices
     * @var array
     */
    private $prices = [];

    /**
     * Only appears when price selection is used.
     * @Commercetools\Field(type="") TODO ScopedPrice
     * @Commercetools\ScopedPrice
     * @var
     */
    private $scopedPrice;

    /**
     * Only appears in response to a Product Projection Search request when price selection is used.
     * @Commercetools\Field(type="boolean")
     * @Commercetools\ScopedPriceDiscount
     * @var boolean
     */
    private $scopedPriceDiscount = false;

    /**
     * The Sku of the variant.
     * @Commercetools\Field(type="string")
     * @Commercetools\Sku
     * @var string
     */
    private $sku = '';

    /**
     * gets Assets
     *
     * @return array
     */
    public function getAssets(): array
    {
        return $this->assets;
    }

    /**
     * gets Attributes
     *
     * @return array
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * gets Availability
     *
     * @return mixed
     */
    public function getAvailability()
    {
        return $this->availability;
    }

    /**
     * gets Id
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * gets Images
     *
     * @return array
     */
    public function getImages(): array
    {
        return $this->images;
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
     * gets Price
     *
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * gets Prices
     *
     * @return array
     */
    public function getPrices(): array
    {
        return $this->prices;
    }

    /**
     * gets ScopedPrice
     *
     * @return mixed
     */
    public function getScopedPrice()
    {
        return $this->scopedPrice;
    }

    /**
     * gets Sku
     *
     * @return string
     */
    public function getSku(): string
    {
        return $this->sku;
    }

    /**
     * iss IsMatchingVariant
     *
     * @return boolean
     */
    public function isIsMatchingVariant(): bool
    {
        return $this->isMatchingVariant;
    }

    /**
     * iss ScopedPriceDiscount
     *
     * @return boolean
     */
    public function isScopedPriceDiscount(): bool
    {
        return $this->scopedPriceDiscount;
    }

    /**
     * Sets Assets
     *
     * @param array $assets
     */
    public function setAssets(array $assets)
    {
        $this->assets = $assets;
    }

    /**
     * Sets Attributes
     *
     * @param array $attributes
     */
    public function setAttributes(array $attributes)
    {
        $this->attributes = $attributes;
    }

    /**
     * Sets Availability
     *
     * @param mixed $availability
     */
    public function setAvailability($availability)
    {
        $this->availability = $availability;
    }

    /**
     * Sets Id
     *
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * Sets Images
     *
     * @param array $images
     */
    public function setImages(array $images)
    {
        $this->images = $images;
    }

    /**
     * Sets IsMatchingVariant
     *
     * @param boolean $isMatchingVariant
     */
    public function setIsMatchingVariant(bool $isMatchingVariant)
    {
        $this->isMatchingVariant = $isMatchingVariant;
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
     * Sets Price
     *
     * @param mixed $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * Sets Prices
     *
     * @param array $prices
     */
    public function setPrices(array $prices)
    {
        $this->prices = $prices;
    }

    /**
     * Sets ScopedPrice
     *
     * @param mixed $scopedPrice
     */
    public function setScopedPrice($scopedPrice)
    {
        $this->scopedPrice = $scopedPrice;
    }

    /**
     * Sets ScopedPriceDiscount
     *
     * @param boolean $scopedPriceDiscount
     */
    public function setScopedPriceDiscount(bool $scopedPriceDiscount)
    {
        $this->scopedPriceDiscount = $scopedPriceDiscount;
    }

    /**
     * Sets Sku
     *
     * @param string $sku
     */
    public function setSku(string $sku)
    {
        $this->sku = $sku;
    }
}