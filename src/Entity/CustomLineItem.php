<?php

namespace BestIt\CommercetoolsODM\Entity;

use BestIt\CommercetoolsODM\Mapping\Annotations as Commercetools;

/**
 * Class LineItem
 * @package BestIt\CommercetoolsODM\Entity
 */
class LineItem
{
    /**
     * The Custom for the tyype.
     * @Commercetools\Field(type="") TODO
     * @Commercetools\Custom
     * @var
     */
    private $custom = '';
    /**
     * The DiscountedPricePerQuantity for the type.
     * @Commercetools\Field(type="array")
     * @Commercetools\DiscountedPricePerQuantity
     * @var array
     */
    private $discountedPricePerQuantity= '';
    /**     TODO @var !!
     * The unique ID of this CustomLineItem.
     * @Commercetools\Field(type="string")
     * @Commercetools\Id
     * @var string
     */
    private $id = '';
    /**
     * The cost to add to the cart. The amount can be negative.
     * @Commercetools\Field(type="") TODO
     * @Commercetools\Money
     * @var
     */
    private $money = '';
    /**
     * The name of this CustomLineItem.
     * @Commercetools\Field(type="string")
     * @Commercetools\Name
     * @var string
     */
    private $name = '';
    /**
     * The Quantity for the type.
     * @Commercetools\Field(type="int")
     * @Commercetools\Quantity
     * @var int
     */
    private $quantity = '';
    /**
     * String A unique String in the cart to identify this CustomLineItem.
     * @Commercetools\Field(type="") TODO
     * @Commercetools\TotalPrice
     * @var
     */
    private $slug = '';
    /**
     * The State for the type.
     * @Commercetools\Field(type="array")
     * @Commercetools\State
     * @var array
     */
    private $state = '';
    /**
     * The TaxCategory for the type.
     * @Commercetools\Field(type="array")
     * @Commercetools\TaxCategory
     * @var array
     */
    private $taxCategory = '';
    /**
     * Will be set automatically in the Platform TaxMode once the shipping address is set is set. For the External tax mode the tax rate has to be set explicitly with the ExternalTaxRateDraft.
     * @Commercetools\Field(type="") TODO
     * @Commercetools\TaxRate
     * @var
     */
    private $taxRate = '';
    /**
     * Set once the taxRate is set.
     * @Commercetools\Field(type="") TODO
     * @Commercetools\TaxedPrice
     * @var
     */
    private $taxedPrice = '';
    /**
     * The total price of this custom line item. If custom line item is discounted, then the totalPrice would be the discounted custom line item price multiplied by quantity. Otherwise a total price is just a money multiplied by the quantity. totalPrice may or may not include the taxes: it depends on the taxRate.includedInPrice property.
     * @Commercetools\Field(type="") TODO
     * @Commercetools\TotalPrice
     * @var
     */
    private $totalPrice = '';

    /**
     * Returns the Custom for the type.
     * @return string
     */
    public function getCustom(): string
    {
        return $this->custom;
    }

    /**
     * Sets the Custom for the type.
     * @param string $custom
     */
    public function setCustom(string $custom)
    {
        $this->custom = $custom;
    }

    /**
     * Returns the DiscountedPriceQuantity for the type.
     * @return string
     */
    public function getDiscountedPricePerQuantity(): string
    {
        return $this->discountedPricePerQuantity;
    }

    /**
     * Sets the DiscountPricePerQuantity for the type.
     * @param string $discountedPricePerQuantity
     */
    public function setDiscountedPricePerQuantity(string $discountedPricePerQuantity)
    {
        $this->discountedPricePerQuantity = $discountedPricePerQuantity;
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
     * Returns the Money for the type.
     * @return string
     */
    public function getMoney(): string
    {
        return $this->money;
    }

    /**
     * Sets the Money for the type.
     * @param string $money
     */
    public function setMoney(string $money)
    {
        $this->money = $money;
    }

    /**
     * Returns the name for the type.
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Sets the Name for the type.
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * Returns the Quantity for the type.
     * @return string
     */
    public function getQuantity(): string
    {
        return $this->quantity;
    }

    /**
     * Sets the Quantity for the type.
     * @param string $quantity
     */
    public function setQuantity(string $quantity)
    {
        $this->quantity = $quantity;
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
     */
    public function setSlug(string $slug)
    {
        $this->slug = $slug;
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
     * Returns the Category for the type.
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
     * Returns the TaxRate for the type.
     * @return string
     */
    public function getTaxRate(): string
    {
        return $this->taxRate;
    }

    /**
     * Sets the TaxRate for the type.
     * @param string $taxRate
     */
    public function setTaxRate(string $taxRate)
    {
        $this->taxRate = $taxRate;
    }

    /**
     * Returns the TaxedPrice for the type.
     * @return string
     */
    public function getTaxedPrice(): string
    {
        return $this->taxedPrice;
    }

    /**
     * Sets the TaxedPrice for the type.
     * @param string $taxedPrice
     */
    public function setTaxedPrice(string $taxedPrice)
    {
        $this->taxedPrice = $taxedPrice;
    }

    /**
     * Returns the TotalPrice for the type.
     * @return string
     */
    public function getTotalPrice(): string
    {
        return $this->totalPrice;
    }

    /**
     * Sets the TotalPrice for the type.
     * @param string $totalPrice
     */
    public function setTotalPrice(string $totalPrice)
    {
        $this->totalPrice = $totalPrice;
    }

}