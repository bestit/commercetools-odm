<?php

namespace BestIt\CommercetoolsODM\Entity;

use BestIt\CommercetoolsODM\Mapping\Annotations as Commercetools;

/**
 * Class ShippingInfo
 *
 * @package BestIt\CommercetoolsODM\Entity
 */
class ShippingInfo
{
    /**
     * @Commercetools\Field(type="array")
     * @Commercetools\Deliveries
     * @var array
     */
    private $deliveries = [];

    /**
     * @Commercetools\Field(type="") TODO DiscountedLineItemPrice
     * @Commercetools\DiscountedPrice
     * @var
     */
    private $discountedPrice;

    /**
     * @Commercetools\Field(type="") TODO Money
     * @Commercetools\Price
     * @var
     */
    private $price;

    /**
     * @Commercetools\Field(type="") TODO Reference
     * @Commercetools\ShippingMethod
     * @var
     */
    private $shippingMethod;

    /**
     * @Commercetools\Field(type="string")
     * @Commercetools\ShippingMethodName
     * @var string
     */
    private $shippingMethodName;

    /**
     * @Commercetools\Field(type="") TODO ShippingRate
     * @Commercetools\ShippingRate
     * @var
     */
    private $shippingRate;

    /**
     * @Commercetools\Field(type="") TODO Reference
     * @Commercetools\TaxCategory
     * @var
     */
    private $taxCategory;

    /**
     * @Commercetools\Field(type="") TODO TaxRate
     * @Commercetools\TaxRate
     * @var
     */
    private $taxRate;

    /**
     * @Commercetools\Field(type="") TODO TaxedItemPrice
     * @Commercetools\TaxedPrice
     * @var
     */
    private $taxedPrice;

    /**
     * gets Deliveries
     *
     * @return array
     */
    public function getDeliveries(): array
    {
        return $this->deliveries;
    }

    /**
     * gets DiscountedPrice
     *
     * @return mixed
     */
    public function getDiscountedPrice()
    {
        return $this->discountedPrice;
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
     * gets ShippingMethod
     *
     * @return mixed
     */
    public function getShippingMethod()
    {
        return $this->shippingMethod;
    }

    /**
     * gets ShippingMethodName
     *
     * @return string
     */
    public function getShippingMethodName(): string
    {
        return $this->shippingMethodName;
    }

    /**
     * gets ShippingRate
     *
     * @return mixed
     */
    public function getShippingRate()
    {
        return $this->shippingRate;
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
     * gets TaxRate
     *
     * @return mixed
     */
    public function getTaxRate()
    {
        return $this->taxRate;
    }

    /**
     * gets TaxedPrice
     *
     * @return mixed
     */
    public function getTaxedPrice()
    {
        return $this->taxedPrice;
    }

    /**
     * Sets Deliveries
     *
     * @param array $deliveries
     */
    public function setDeliveries(array $deliveries)
    {
        $this->deliveries = $deliveries;
    }

    /**
     * Sets DiscountedPrice
     *
     * @param mixed $discountedPrice
     */
    public function setDiscountedPrice($discountedPrice)
    {
        $this->discountedPrice = $discountedPrice;
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
     * Sets ShippingMethod
     *
     * @param mixed $shippingMethod
     */
    public function setShippingMethod($shippingMethod)
    {
        $this->shippingMethod = $shippingMethod;
    }

    /**
     * Sets ShippingMethodName
     *
     * @param string $shippingMethodName
     */
    public function setShippingMethodName(string $shippingMethodName)
    {
        $this->shippingMethodName = $shippingMethodName;
    }

    /**
     * Sets ShippingRate
     *
     * @param mixed $shippingRate
     */
    public function setShippingRate($shippingRate)
    {
        $this->shippingRate = $shippingRate;
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
     * Sets TaxRate
     *
     * @param mixed $taxRate
     */
    public function setTaxRate($taxRate)
    {
        $this->taxRate = $taxRate;
    }

    /**
     * Sets TaxedPrice
     *
     * @param mixed $taxedPrice
     */
    public function setTaxedPrice($taxedPrice)
    {
        $this->taxedPrice = $taxedPrice;
    }
}
