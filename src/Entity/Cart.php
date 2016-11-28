<?php

namespace BestIt\CommercetoolsODM\Entity;

use BestIt\CommercetoolsODM\Mapping\Annotations as Commercetools;

class Cart
{
    /**     TODO @var!!
     * Identifies carts and orders belonging to an anonymous session (the customer has not signed up/in yet).
     * @Commercetools\Field(type="string")
     * @Commercetools\AnonymousId
     * @var string
     */
    private $anonymousId = '';
    /**
     * The BillingAddress for the type
     * @Commercetools\Field(type="") TODO
     * @Commercetools\BillingAddress
     * @var
     */
    private $billingAddress = '';
    /**
     * The CartState for the type.
     * @Commercetools\Field(type="") TODO
     * @Commercetools\CartState
     * @var
     */
    private $cartState = '';
    /**
     * A two-digit country code as per ↗ ISO 3166-1 alpha-2 . Used for product variant price selection.
     * @Commercetools\Field(type="string")
     * @Commercetools\Country
     * @var string
     */
    private $country = '';
    /**
     * The CreatedAt for the type.
     * @Commercetools\Field(type="datetime")
     * @Commercetools\CreatedAt
     * @var datetime
     */
    private $createdAt = '';
    /**
     * The Custom for the type.
     * @Commercetools\Field(type="") TODO
     * @Commercetools\Custom
     * @var
     */
    private $custom = '';
    /**
     * The CustomLineItems for the type.
     * @Commercetools\Field(type="array")
     * @Commercetools\CustomLineItems
     * @var array
     */
    private $customLineItems = '';
    /**
     * The CustomerEmail for the type.
     * @Commercetools\Field(type="string")
     * @Commercetools\CustomerEmail
     * @var string
     */
    private $customerEmail = '';
    /**
     * Set automatically when the customer is set and the customer is a member of a customer group. Used for product variant price selection.
     * @Commercetools\Field(type="") TODO
     * @Commercetools\CustomerGroup
     * @var
     */
    private $customerGroup = '';
    /**
     * The CoustomerId for the type.
     * @Commercetools\Field(type="string")
     * @Commercetools\CoustomerId
     * @var string
     */
    private $customerId = '';
    /**
     * The DicountCode for the type.
     * @Commercetools\Field(type="array")
     * @Commercetools\DicountCode
     * @var array
     */
    private $discountCodes = '';
    /**
     * The unique ID of the cart.
     * @Commercetools\Field(type="string")
     * @Commercetools\Id
     * @var string
     */
    private $id = '';
    /**
     * The InventoryMode for the type.
     * @Commercetools\Field(type="") TODO
     * @Commercetools\InventoryMode
     * @var
     */
    private $inventoryMode = '';
    /**
     * The LastModifiedAt for the type.
     * @Commercetools\Field(type="datetime")
     * @Commercetools\LastModifiedAt
     * @var
     */
    private $lastModifiedAt = '';
    /**
     * The LineItem for the type.
     * @Commercetools\Field(type="array")
     * @Commercetools\LineItem
     * @var array
     */
    private $lineItems = '';
    /**
     * The Locale for the type.
     * @Commercetools\Field(type="string")
     * @Commercetools\Locale
     * @var string
     */
    private $locale = '';
    /**
     * The PaymentInfo for the type.
     * @Commercetools\Field(type="") TODO
     * @Commercetools\PaymentInfo
     * @var
     */
    private $paymentInfo = '';
    /**
     * The shipping address is also used to determine tax rate of the line items.
     * @Commercetools\Field(type="") TODO
     * @Commercetools\ShippingAdress
     * @var
     */
    private $shippingAddress = '';
    /**
     * Set automatically once the ShippingMethod is set.
     * @Commercetools\Field(type="") TODO
     * @Commercetools\ShippingInfo
     * @var
     */
    private $shippingInfo = '';
    /**
     * The TaxMode for the type.
     * @Commercetools\Field(type="") TODO
     * @Commercetools\TaxMode
     * @var
     */
    private $taxMode = '';
    /**
     * Not set until the shipping address is set. Will be set automatically in the Platform TaxMode. For the External tax mode it will be set as soon as the external tax rates for all line items, custom line items, and shipping in the cart are set.
     * @Commercetools\Field(type="") TODO
     * @Commercetools\TaxedPrice
     * @var
     */
    private $taxedPrice = '';
    /**
     * The TotalPrice for the type.
     * @Commercetools\Field(type="") TODO
     * @Commercetools\TatalPrice
     * @var
     */
    private $totalPrice = '';
    /**
     * The current version of the cart.
     * @Commercetools\Field(type="int")
     * @Commercetools\Version
     * @var int
     */
    private $version = '';

    /**
     * Returns the AnonymousIs for the type.
     * @return string
     */
    public function getAnonymousId(): string
    {
        return $this->anonymousId;
    }

    /**
     * Sets the AnonymousId for the type.
     * @param string $anonymousId
     */
    public function setAnonymousId(string $anonymousId)
    {
        $this->anonymousId = $anonymousId;
    }

    /**
     * Returns the BillingAddress for the type.
     * @return string
     */
    public function getBillingAddress(): string
    {
        return $this->billingAddress;
    }

    /**
     * Sets the BillingAddres for the type.
     * @param string $billingAddress
     */
    public function setBillingAddress(string $billingAddress)
    {
        $this->billingAddress = $billingAddress;
    }

    /**
     * Returns the CartState for the type.
     * @return string
     */
    public function getCartState(): string
    {
        return $this->cartState;
    }

    /**
     * Sets the CartState for the type.
     * @param string $cartState
     */
    public function setCartState(string $cartState)
    {
        $this->cartState = $cartState;
    }

    /**
     * Returns the Country for the type.
     * @return string
     */
    public function getCountry(): string
    {
        return $this->country;
    }

    /**
     * Sets the Country for the type.
     * @param string $country
     */
    public function setCountry(string $country)
    {
        $this->country = $country;
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
     * Sets the CreatedAt for the type.
     * @param string $createdAt
     */
    public function setCreatedAt(string $createdAt)
    {
        $this->createdAt = $createdAt;
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
     * Sets the Custom for the type.
     * @param string $custom
     */
    public function setCustom(string $custom)
    {
        $this->custom = $custom;
    }

    /**
     * Returns the CustomLineItems for the type.
     * @return string
     */
    public function getCustomLineItems(): string
    {
        return $this->customLineItems;
    }

    /**
     * Sets the CustomLineItems for the type.
     * @param string $customLineItems
     */
    public function setCustomLineItems(string $customLineItems)
    {
        $this->customLineItems = $customLineItems;
    }

    /**
     * Returns the CustomerEmail for the type.
     * @return string
     */
    public function getCustomerEmail(): string
    {
        return $this->customerEmail;
    }

    /**
     * Sets the CustomerEmail for the type.
     * @param string $customerEmail
     */
    public function setCustomerEmail(string $customerEmail)
    {
        $this->customerEmail = $customerEmail;
    }

    /**
     * Returns the CustomerGroup for the type.
     * @return string
     */
    public function getCustomerGroup(): string
    {
        return $this->customerGroup;
    }

    /**
     * Sets the CustomerGroup for the type.
     * @param string $customerGroup
     */
    public function setCustomerGroup(string $customerGroup)
    {
        $this->customerGroup = $customerGroup;
    }

    /**
     * Returns the CustomerId for the type.
     * @return string
     */
    public function getCustomerId(): string
    {
        return $this->customerId;
    }

    /**
     * Sets the CustomerId for the type.
     * @param string $customerId
     */
    public function setCustomerId(string $customerId)
    {
        $this->customerId = $customerId;
    }

    /**
     * Returns the DiscountCodes for the type.
     * @return string
     */
    public function getDiscountCodes(): string
    {
        return $this->discountCodes;
    }

    /**
     * Sets the DiscountCodes for the type.
     * @param string $discountCodes
     */
    public function setDiscountCodes(string $discountCodes)
    {
        $this->discountCodes = $discountCodes;
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
     * Returns the InventoryMode for the type.
     * @return string
     */
    public function getInventoryMode(): string
    {
        return $this->inventoryMode;
    }

    /**
     * Sets the InventoryMode for the type.
     * @param string $inventoryMode
     */
    public function setInventoryMode(string $inventoryMode)
    {
        $this->inventoryMode = $inventoryMode;
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
     * Sets the LastModiefiedAt for the type.
     * @param string $lastModifiedAt
     */
    public function setLastModifiedAt(string $lastModifiedAt)
    {
        $this->lastModifiedAt = $lastModifiedAt;
    }

    /**
     * Returns the LineItems for the type.
     * @return string
     */
    public function getLineItems(): string
    {
        return $this->lineItems;
    }

    /**
     * Sets the LineItems for the type.
     * @param string $lineItems
     */
    public function setLineItems(string $lineItems)
    {
        $this->lineItems = $lineItems;
    }

    /**
     * Returns the Locale for the type.
     * @return string
     */
    public function getLocale(): string
    {
        return $this->locale;
    }

    /**
     * Sets the Locale for the type.
     * @param string $locale
     */
    public function setLocale(string $locale)
    {
        $this->locale = $locale;
    }

    /**
     * Returns the PaymentInfo for the type.
     * @return string
     */
    public function getPaymentInfo(): string
    {
        return $this->paymentInfo;
    }

    /**
     * Sets the PaymentInfo for the type.
     * @param string $paymentInfo
     */
    public function setPaymentInfo(string $paymentInfo)
    {
        $this->paymentInfo = $paymentInfo;
    }

    /**
     * Returns the ShippingAddres
     * @return string
     */
    public function getShippingAddress(): string
    {
        return $this->shippingAdress;
    }

    /**
     * @param string $shippingAdress
     */
    public function setShippingAddress(string $shippingAdress)
    {
        $this->shippingAdress = $shippingAdress;
    }

    /**
     * @return string
     */
    public function getShippingInfo(): string
    {
        return $this->shippingInfo;
    }

    /**
     * @param string $shippingInfo
     */
    public function setShippingInfo(string $shippingInfo)
    {
        $this->shippingInfo = $shippingInfo;
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

    /**
     * @return string
     */
    public function getTaxedPrice(): string
    {
        return $this->taxedPrice;
    }

    /**
     * @param string $taxedPrice
     */
    public function setTaxedPrice(string $taxedPrice)
    {
        $this->taxedPrice = $taxedPrice;
    }

    /**
     * @return string
     */
    public function getTotalPrice(): string
    {
        return $this->totalPrice;
    }

    /**
     * @param string $totalPrice
     */
    public function setTotalPrice(string $totalPrice)
    {
        $this->totalPrice = $totalPrice;
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * @param string $version
     */
    public function setVersion(string $version)
    {
        $this->version = $version;
    }

}