<?php

namespace BestIt\CommercetoolsODM\Entity;

use BestIt\CommercetoolsODM\Mapping\Annotations as Commercetools;

/**
 * Class CartDraft
 * @package BestIt\CommercetoolsODM\Entity
 */
class CartDraft
{
    /**
     * @var string
     */
    private $anonymousId = '';
    /**
     * @var string
     */
    private $billingAddress = '';
    /**
     * @var string
     */
    private $country = '';
    /**
     * @var string
     */
    private $currency = '';
    /**
     * @var string
     */
    private $custom = '';
    /**
     * @var string
     */
    private $customLineItems = '';
    /**
     * @var string
     */
    private $customerEmail = '';
    /**
     * @var string
     */
    private $customerId = '';
    /**
     * @var string
     */
    private $externalTaxRateForShippingMethod = '';
    /**
     * @var string
     */
    private $inventoryMode = '';
    /**
     * @var string
     */
    private $lineItems = '';
    /**
     * @var string
     */
    private $locale = '';
    /**
     * @var string
     */
    private $shippingAddress = '';
    /**
     * @var string
     */
    private $shippingMethod = '';
    /**
     * @var string
     */
    private $taxMode = '';

    /**
     * @return string
     */
    public function getAnonymousId(): string
    {
        return $this->anonymousId;
    }

    /**
     * @param string $anonymousId
     */
    public function setAnonymousId(string $anonymousId)
    {
        $this->anonymousId = $anonymousId;
    }

    /**
     * @return string
     */
    public function getBillingAddress(): string
    {
        return $this->billingAddress;
    }

    /**
     * @param string $billingAddress
     */
    public function setBillingAddress(string $billingAddress)
    {
        $this->billingAddress = $billingAddress;
    }

    /**
     * @return string
     */
    public function getCountry(): string
    {
        return $this->country;
    }

    /**
     * @param string $country
     */
    public function setCountry(string $country)
    {
        $this->country = $country;
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     */
    public function setCurrency(string $currency)
    {
        $this->currency = $currency;
    }

    /**
     * @return string
     */
    public function getCustom(): string
    {
        return $this->custom;
    }

    /**
     * @param string $custom
     */
    public function setCustom(string $custom)
    {
        $this->custom = $custom;
    }

    /**
     * @return string
     */
    public function getCustomLineItems(): string
    {
        return $this->customLineItems;
    }

    /**
     * @param string $customLineItems
     */
    public function setCustomLineItems(string $customLineItems)
    {
        $this->customLineItems = $customLineItems;
    }

    /**
     * @return string
     */
    public function getCustomerEmail(): string
    {
        return $this->customerEmail;
    }

    /**
     * @param string $customerEmail
     */
    public function setCustomerEmail(string $customerEmail)
    {
        $this->customerEmail = $customerEmail;
    }

    /**
     * @return string
     */
    public function getCustomerId(): string
    {
        return $this->customerId;
    }

    /**
     * @param string $customerId
     */
    public function setCustomerId(string $customerId)
    {
        $this->customerId = $customerId;
    }

    /**
     * @return string
     */
    public function getExternalTaxRateForShippingMethod(): string
    {
        return $this->externalTaxRateForShippingMethod;
    }

    /**
     * @param string $externalTaxRateForShippingMethod
     */
    public function setExternalTaxRateForShippingMethod(string $externalTaxRateForShippingMethod)
    {
        $this->externalTaxRateForShippingMethod = $externalTaxRateForShippingMethod;
    }

    /**
     * @return string
     */
    public function getInventoryMode(): string
    {
        return $this->inventoryMode;
    }

    /**
     * @param string $inventoryMode
     */
    public function setInventoryMode(string $inventoryMode)
    {
        $this->inventoryMode = $inventoryMode;
    }

    /**
     * @return string
     */
    public function getLineItems(): string
    {
        return $this->lineItems;
    }

    /**
     * @param string $lineItems
     */
    public function setLineItems(string $lineItems)
    {
        $this->lineItems = $lineItems;
    }

    /**
     * @return string
     */
    public function getLocale(): string
    {
        return $this->locale;
    }

    /**
     * @param string $locale
     */
    public function setLocale(string $locale)
    {
        $this->locale = $locale;
    }

    /**
     * @return string
     */
    public function getShippingAddress(): string
    {
        return $this->shippingAddress;
    }

    /**
     * @param string $shippingAddress
     */
    public function setShippingAddress(string $shippingAddress)
    {
        $this->shippingAddress = $shippingAddress;
    }

    /**
     * @return string
     */
    public function getShippingMethod(): string
    {
        return $this->shippingMethod;
    }

    /**
     * @param string $shippingMethod
     */
    public function setShippingMethod(string $shippingMethod)
    {
        $this->shippingMethod = $shippingMethod;
    }

    /**
     * @return string
     */
    public function getTaxMode(): string
    {
        return $this->taxMode;
    }

    /**
     * @param string $taxMode
     */
    public function setTaxMode(string $taxMode)
    {
        $this->taxMode = $taxMode;
    }

}