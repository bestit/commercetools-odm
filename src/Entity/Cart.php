<?php

namespace BestIt\CommercetoolsODM\Entity;

use BestIt\CommercetoolsODM\Mapping\Annotations as Commercetools;

/**
 * Entity for Carts.
 * @Commercetools\DraftClass("Commercetools\Core\Model\Cart\CartDraft")
 * @Commercetools\Entity(requestMap=@Commercetools\RequestMap(
 *     defaultNamespace="Commercetools\Core\Request\Carts",
 *     findById="CartByIdGetRequest",
 *     findByCustomerId="CartByCustomerIdGetRequest",
 *     query="CartQueryRequest",
 *     create="CartCreateRequest",
 *     update="CartUpdateRequest",
 *     delete="CartDeleteRequest"
 * ))
 * @Commercetools\Repository("BestIt\CommercetoolsODM\Model\CartRepository")
 * @package BestIt\CommercetoolsODM
 * @subpackage Entity
 * @version $id$
 */
class Cart
{
    /**
     * Identifies carts and orders belonging to an anonymous session (the customer has not signed up/in yet).
     * @Commercetools\Field(type="string")
     * @Commercetools\AnonymousId
     * @var string
     */
    private $anonymousId = '';

    /**
     * The BillingAddress for the type
     * @Commercetools\Field(type="") TODO Address
     * @Commercetools\BillingAddress
     * @var
     */
    private $billingAddress = '';

    /**
     * The CartState for the type.
     * @Commercetools\Field(type="") TODO cartState
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
     * @var \DateTime
     */
    private $createdAt;

    /**
     * The Custom for the type.
     * @Commercetools\Field(type="") TODO CustomFields
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
    private $customLineItems = [];

    /**
     * The CustomerEmail for the type.
     * @Commercetools\Field(type="string")
     * @Commercetools\CustomerEmail
     * @var string
     */
    private $customerEmail = '';

    /**
     * Set automatically when the customer is set and the customer is a member of a customer group. Used for product variant price selection.
     * @Commercetools\Field(type="") TODO Reference
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
    private $discountCodes = [];

    /**
     * The unique ID of the cart.
     * @Commercetools\Field(type="string")
     * @Commercetools\Id
     * @var string
     */
    private $id = '';

    /**
     * The InventoryMode for the type.
     * @Commercetools\Field(type="") TODO InventoryMode
     * @Commercetools\InventoryMode
     * @var
     */
    private $inventoryMode = '';

    /**
     * The LastModifiedAt for the type.
     * @Commercetools\Field(type="datetime")
     * @Commercetools\LastModifiedAt
     * @var \DateTime
     */
    private $lastModifiedAt;

    /**
     * The LineItem for the type.
     * @Commercetools\Field(type="array")
     * @Commercetools\LineItem
     * @var array
     */
    private $lineItems = [];

    /**
     * The Locale for the type.
     * @Commercetools\Field(type="string")
     * @Commercetools\Locale
     * @var string
     */
    private $locale = '';

    /**
     * The PaymentInfo for the type.
     * @Commercetools\Field(type="") TODO PaymentInfo
     * @Commercetools\PaymentInfo
     * @var
     */
    private $paymentInfo = '';

    /**
     * The shipping address is also used to determine tax rate of the line items.
     * @Commercetools\Field(type="") TODO Address
     * @Commercetools\ShippingAdress
     * @var
     */
    private $shippingAddress = '';

    /**
     * Set automatically once the ShippingMethod is set.
     * @Commercetools\Field(type="") TODO ShippingInfo
     * @Commercetools\ShippingInfo
     * @var
     */
    private $shippingInfo = '';

    /**
     * The TaxMode for the type.
     * @Commercetools\Field(type="") TODO TaxMode
     * @Commercetools\TaxMode
     * @var
     */
    private $taxMode = '';

    /**
     * Not set until the shipping address is set. Will be set automatically in the Platform TaxMode. For the External tax mode it will be set as soon as the external tax rates for all line items, custom line items, and shipping in the cart are set.
     * @Commercetools\Field(type="") TODO TaxedPrice
     * @Commercetools\TaxedPrice
     * @var
     */
    private $taxedPrice = '';

    /**
     * The TotalPrice for the type.
     * @Commercetools\Field(type="") TODO Money
     * @Commercetools\TotalPrice
     * @var
     */
    private $totalPrice = '';

    /**
     * The current version of the cart.
     * @Commercetools\Field(type="int")
     * @Commercetools\Version
     * @var int
     */
    private $version = 0;


    /**
     * Returns the AnonymousIs for the type.
     * @return string
     */
    public function getAnonymousId(): string
    {
        return $this->anonymousId;
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
     * Returns the CartState for the type.
     * @return string
     */
    public function getCartState(): string
    {
        return $this->cartState;
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
     * Returns the CreatedAt for the type.
     * @return string
     */
    public function getCreatedAt(): string
    {
        return $this->createdAt;
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
     * Returns the CustomLineItems for the type.
     * @return string
     */
    public function getCustomLineItems(): string
    {
        return $this->customLineItems;
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
     * Returns the CustomerGroup for the type.
     * @return string
     */
    public function getCustomerGroup(): string
    {
        return $this->customerGroup;
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
     * Returns the DiscountCodes for the type.
     * @return string
     */
    public function getDiscountCodes(): string
    {
        return $this->discountCodes;
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
     * Returns the InventoryMode for the type.
     * @return string
     */
    public function getInventoryMode(): string
    {
        return $this->inventoryMode;
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
     * Returns the LineItems for the type.
     * @return string
     */
    public function getLineItems(): string
    {
        return $this->lineItems;
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
     * Returns the PaymentInfo for the type.
     * @return string
     */
    public function getPaymentInfo(): string
    {
        return $this->paymentInfo;
    }

    /**
     * gets ShippingAddress
     *
     * @return mixed
     */
    public function getShippingAddress()
    {
        return $this->shippingAddress;
    }

    /**
     * @return string
     */
    public function getShippingInfo(): string
    {
        return $this->shippingInfo;
    }

    /**
     * @return string
     */
    public function getTaxMode(): string
    {
        return $this->taxMode;
    }

    /**
     * @return string
     */
    public function getTaxedPrice(): string
    {
        return $this->taxedPrice;
    }

    /**
     * @return string
     */
    public function getTotalPrice(): string
    {
        return $this->totalPrice;
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
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
     * Sets the BillingAddres for the type.
     * @param string $billingAddress
     */
    public function setBillingAddress(string $billingAddress)
    {
        $this->billingAddress = $billingAddress;
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
     * Sets the Country for the type.
     * @param string $country
     */
    public function setCountry(string $country)
    {
        $this->country = $country;
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
     * Sets the Custom for the type.
     * @param string $custom
     */
    public function setCustom(string $custom)
    {
        $this->custom = $custom;
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
     * Sets the CustomerEmail for the type.
     * @param string $customerEmail
     */
    public function setCustomerEmail(string $customerEmail)
    {
        $this->customerEmail = $customerEmail;
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
     * Sets the CustomerId for the type.
     * @param string $customerId
     */
    public function setCustomerId(string $customerId)
    {
        $this->customerId = $customerId;
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
     * Sets the Id for the type.
     * @param string $id
     */
    public function setId(string $id)
    {
        $this->id = $id;
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
     * Sets the LastModiefiedAt for the type.
     * @param string $lastModifiedAt
     */
    public function setLastModifiedAt(string $lastModifiedAt)
    {
        $this->lastModifiedAt = $lastModifiedAt;
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
     * Sets the Locale for the type.
     * @param string $locale
     */
    public function setLocale(string $locale)
    {
        $this->locale = $locale;
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
     * Sets ShippingAddress
     *
     * @param mixed $shippingAddress
     */
    public function setShippingAddress($shippingAddress)
    {
        $this->shippingAddress = $shippingAddress;
    }


    /**
     * @param string $shippingInfo
     */
    public function setShippingInfo(string $shippingInfo)
    {
        $this->shippingInfo = $shippingInfo;
    }

    /**
     * @param string $taxMode
     */
    public function setTaxMode(string $taxMode)
    {
        $this->taxMode = $taxMode;
    }

    /**
     * @param string $taxedPrice
     */
    public function setTaxedPrice(string $taxedPrice)
    {
        $this->taxedPrice = $taxedPrice;
    }

    /**
     * @param string $totalPrice
     */
    public function setTotalPrice(string $totalPrice)
    {
        $this->totalPrice = $totalPrice;
    }

    /**
     * @param string $version
     */
    public function setVersion(string $version)
    {
        $this->version = $version;
    }

}