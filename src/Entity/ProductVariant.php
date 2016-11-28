<?php
namespace BestIt\CommercetoolsODM\Entity;

use BestIt\CommercetoolsODM\Mapping\Annotations as Commercetools;

class ProductVariant
{
    /**
     * The Assets for the type.
     * @Commercetools\Field(type="array")
     * @Commercetools\Assets
     * @var
     */
    private $assets = '';
    /**
     * The Attributes for the type.
     * @Commercetools\Field(type="array")
     * @Commercetools\Attributes
     * @var
     */
    private $attributes = '';
    /**
     * The Availability is set if the variant is tracked by the inventory. The field might not contain the latest inventory state (it is eventually consistent) and can be used as an optimization to reduce calls to the inventory service.
     * @Commercetools\Field(type="") TODO
     * @Commercetools\Availability
     * @var
     */
    private $availability = '';
    /**
     * The sequential ID of the variant within the product.
     * @Commercetools\Field(type="int")
     * @Commercetools\Id
     * @var
     */
    private $id = '';
    /**
     * The Images for the type.
     * @Commercetools\Field(type="array")
     * @Commercetools\Images
     * @var
     */
    private $images = '';
    /**
     * Only appears in response to a Product Projection Search request to mark this variant as one that matches the search query.
     * @Commercetools\Field(type="boolean")
     * @Commercetools\IsMatchingVariant
     * @var
     */
    private $isMatchingVariant = '';
    /**
     * User-specific unique identifier for the variant. Product variant keys are different from product keys.
     * @Commercetools\Field(type="string")
     * @Commercetools\Key
     * @var string
     */
    private $key = '';
    /**
     *  Only appears when price selection is used. This field cannot be used in a query predicate.
     * @Commercetools\Field(type="") TODO
     * @Commercetools\Price
     * @var
     */
    private $price = '';
    /**
     * The prices of the variant. The prices does not contain two prices for the same price scope (same currency, country, customer group and channel).
     * @Commercetools\Field(type="array")
     * @Commercetools\Prices
     * @var
     */
    private $prices = '';
    /**
     * Only appears when price selection is used.
     * @Commercetools\Field(type="") TODO
     * @Commercetools\ScopedPrice
     * @var
     */
    private $scopedPrice = '';
    /**
     * Only appears in response to a Product Projection Search request when price selection is used.
     * @Commercetools\Field(type="boolean")
     * @Commercetools\ScopedPriceDiscount
     * @var
     */
    private $scopedPriceDiscount = '';
    /**
     * The Sku of the variant.
     * @Commercetools\Field(type="string")
     * @Commercetools\Sku
     * @var string
     */
    private $sku = '';

    /**
     * Returns the Assets for the type.
     * @return string
     */
    public function getAssets(): string
    {
        return $this->assets;
    }

    /**
     * Sets the Assets for the type.
     * @param string $assets
     */
    public function setAssets(string $assets)
    {
        $this->assets = $assets;
    }

    /**
     * Returns the Attributes for the type.
     * @return string
     */
    public function getAttributes(): string
    {
        return $this->attributes;
    }

    /**
     * Sets the Attributes for the type.
     * @param string $attributes
     */
    public function setAttributes(string $attributes)
    {
        $this->attributes = $attributes;
    }

    /**
     * Returns the Availability for the type.
     * @return string
     */
    public function getAvailability(): string
    {
        return $this->availability;
    }

    /**
     * Sets the Avialabilty for the type.
     * @param string $availability
     */
    public function setAvailability(string $availability)
    {
        $this->availability = $availability;
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
     * Returns the Images for the type.
     * @return string
     */
    public function getImages(): string
    {
        return $this->images;
    }

    /**
     * Sets the Images for the type.
     * @param string $images
     */
    public function setImages(string $images)
    {
        $this->images = $images;
    }

    /**
     * Retruns the IsMatchingVariant for the type.
     * @return string
     */
    public function getIsMatchingVariant(): string
    {
        return $this->isMatchingVariant;
    }

    /**
     * Sets the IsMatchingVariant for the type.
     * @param string $isMatchingVariant
     */
    public function setIsMatchingVariant(string $isMatchingVariant)
    {
        $this->isMatchingVariant = $isMatchingVariant;
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
     * Returns the Price for the type.
     * @return string
     */
    public function getPrice(): string
    {
        return $this->price;
    }

    /**
     * Sets the Price for the type.
     * @param string $price
     */
    public function setPrice(string $price)
    {
        $this->price = $price;
    }

    /**
     * Returns the Prices for the type.
     * @return string
     */
    public function getPrices(): string
    {
        return $this->prices;
    }

    /**
     * Sets the Prices for the type.
     * @param string $prices
     */
    public function setPrices(string $prices)
    {
        $this->prices = $prices;
    }

    /**
     * Returns the ScopedPrice for the type.
     * @return string
     */
    public function getScopedPrice(): string
    {
        return $this->scopedPrice;
    }

    /**
     * Sets the ScopedPrice for the type.
     * @param string $scopedPrice
     */
    public function setScopedPrice(string $scopedPrice)
    {
        $this->scopedPrice = $scopedPrice;
    }

    /**
     * Returns the ScopedPriceDiscount for the type.
     * @return string
     */
    public function getScopedPriceDiscount(): string
    {
        return $this->scopedPriceDiscount;
    }

    /**
     * Sets the ScopedPrice for the type.
     * @param string $scopedPriceDiscount
     */
    public function setScopedPriceDiscount(string $scopedPriceDiscount)
    {
        $this->scopedPriceDiscount = $scopedPriceDiscount;
    }

    /**
     * Returns the Sku for the type.
     * @return string
     */
    public function getSku(): string
    {
        return $this->sku;
    }

    /**
     * Sets the Sku for the type.
     * @param string $sku
     */
    public function setSku(string $sku)
    {
        $this->sku = $sku;
    }

}