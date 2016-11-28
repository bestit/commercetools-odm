<?php

namespace BestIt\CommercetoolsODM\Entity;

use BestIt\CommercetoolsODM\Mapping\Annotations as Commercetools;

/**
 * Entity for Orders.
 * An order can be created from a cart, usually after a checkout process has been completed.
 * Orders can also be imported.
 *
 * @Commercetools\DraftClass("Commercetools\Core\Model\Order\OrderDraft")
 * @Commercetools\Entity(requestMap=@Commercetools\RequestMap(
 *     defaultNamespace="Commercetools\Core\Request\Orders",
 *     findById="OrderByIdGetRequest",
 *     query="OrderByIdGetRequest",
 *     create="OrderCreateRequest",
 *     update="OrderUpdateRequest",
 *     delete="OrderDeleteRequest"
 * ))
 * @Commercetools\Repository("BestIt\CommercetoolsODM\Model\OrderRepository")
 * @package BestIt\CommercetoolsODM
 * @subpackage Entity
 * @version $id$
 */
class Order
{
    /**
     * Identifies carts and orders belonging to an anonymous session (the customer has not signed up/in yet).
     * @Commercetools\Field(type="string")
     * @Commercetools\AnonymousId
     * @var string
     */
    private $anonymousId;
    /**
     * @Commercetools\Field(type="") TODO Address
     * @Commercetools\BillingAddress
     * @var
     */
    private $billingAddress;
    /**
     * Set when this order was created from a cart. The cart will have the state Ordered.
     * @Commercetools\Field(type="") TODO Reference
     * @Commercetools\Cart
     * @var
     */
    private $cart;
    /**
     * This field will only be present if it was set for Order Import
     * @Commercetools\Field(type="datetime")
     * @Commercetools\CompletedAt
     * @var \DateTime
     */
    private $completedAt;
    /**
     * A two-digit country code as per ISO 3166-1 alpha-2 . Used for product variant price selection.
     * @Commercetools\Field(type="string")
     * @Commercetools\Country
     * @var string
     */
    private $country;
    /**
     * @Commercetools\Field(type="datetime")
     * @Commercetools\CreatedAt
     * @var \DateTime
     */
    private $createdAt;
    /**
     * @Commercetools\Field(type="") TODO CustomFields
     * @Commercetools\Custom
     * @var
     */
    private $custom;
    /**
     * Array of CustomLineItem
     * @Commercetools\Field(type="array")
     * @Commercetools\CustomLineItems
     * @var array
     */
    private $customLineItems;
    /**
     * @Commercetools\Field(type="string")
     * @Commercetools\CustomerEmail
     * @var string
     */
    private $customerEmail;
    /**
     * Set when the customer is set and the customer is a member of a customer group.
     * Used for product variant price selection.
     * @Commercetools\Field(type="") TODO Reference
     * @Commercetools\CustomerGroup
     * @var
     */
    private $customerGroup;
    /**
     * @Commercetools\Field(type="string")
     * @Commercetools\CustomerId
     * @var string
     */
    private $customerId;
    /**
     * Array of DiscountCodeInfo
     * @Commercetools\Field(type="array")
     * @Commercetools\DiscountCodes
     * @var array
     */
    private $discountCodes;
    /**
     * The unique ID of the order.
     * @Commercetools\Field(type="string")
     * @Commercetools\Id
     * @var string
     */
    private $id;
    /**
     * The unique ID of this object.
     * @Commercetools\Field(type="string") TODO InventoryMode
     * @Commercetools\Id
     * @var string
     */
    private $inventoryMode;
    /**
     * The sequence number of the last order message produced by changes to this order.
     * 0 means, that no messages were created yet.
     * @Commercetools\Field(type="int")
     * @Commercetools\LastMessageSequenceNumber
     * @var int
     */
    private $lastMessageSequenceNumber;
    /**
     * @Commercetools\Field(type="datetime")
     * @Commercetools\LastModifiedAt
     * @var \DateTime
     */
    private $lastModifiedAt;
    /**
     * Array of LineItem
     * @Commercetools\Field(type="array")
     * @Commercetools\LineItems
     * @var array
     */
    private $lineItems;
    /**
     * String conforming to IETF language tag
     * @Commercetools\Field(type="string")
     * @Commercetools\Locale
     * @var string
     */
    private $locale;
    /**
     * String that uniquely identifies an order. It can be used to create more human-readable (in contrast to ID)
     * identifier for the order. It should be unique across a project. Once it’s set it cannot be changed.
     * @Commercetools\Field(type="string")
     * @Commercetools\OrderNumber
     * @var string
     */
    private $odernumber;
    /**
     * One of the four predefined OrderStates.
     * @Commercetools\Field(type="") TODO OrderState
     * @Commercetools\OrderState
     * @var
     */
    private $orderState;
    /**
     * @Commercetools\Field(type="") TODO PaymentInfo
     * @Commercetools\PaymentInfo
     * @var
     */
    private $paymentInfo;
    /**
     * @Commercetools\Field(type="") TODO PaymentState
     * @Commercetools\PaymentState
     * @var
     */
    private $paymentState;
    /**
     * Set of ReturnInfo
     * @Commercetools\Field(type="") TODO ReturnInfo
     * @Commercetools\ReturnInfo
     * @var
     */
    private $returnInfo;
    /**
     * @Commercetools\Field(type="") TODO ShipmentState
     * @Commercetools\ShipmentState
     * @var
     */
    private $shipmentState;
    /**
     * @Commercetools\Field(type="string") TODO Address
     * @Commercetools\ShippingAddress
     * @var
     */
    private $shippingAddress;
    /**
     * Set if the ShippingMethod is set.
     * @Commercetools\Field(type="") TODO ShippingInfo
     * @Commercetools\ShippingInfo
     * @var
     */
    private $shippingInfo;
    /**
     * This reference can point to a state in a custom workflow.
     * @Commercetools\Field(type="") TODO Reference
     * @Commercetools\State
     * @var
     */
    private $state;
    /**
     * Set of SyncInfo
     * @Commercetools\Field(type="") TODO SyncInfo
     * @Commercetools\SyncInfo
     * @var
     */
    private $syncInfo;
    /**
     * @Commercetools\Field(type="") TODO TaxMode
     * @Commercetools\TaxMode
     * @var
     */
    private $taxMode;
    /**
     * The taxes are calculated based on the shipping address.
     * @Commercetools\Field(type="") TODO Money
     * @Commercetools\TaxedPrice
     * @var
     */
    private $taxedPrice;
    /**
     * @Commercetools\Field(type="") TODO Money
     * @Commercetools\TotalPrice
     * @var
     */
    private $totalPrice;
    /**
     * The current version of the order.
     * @Commercetools\Field(type="int")
     * @Commercetools\Version
     * @var int
     */
    private $version;

    /**
     * gets AnonymousId
     *
     * @return string
     */
    public function getAnonymousId(): string
    {
        return $this->anonymousId;
    }

    /**
     * gets BillingAddress
     *
     * @return mixed
     */
    public function getBillingAddress()
    {
        return $this->billingAddress;
    }

    /**
     * gets Cart
     *
     * @return mixed
     */
    public function getCart()
    {
        return $this->cart;
    }

    /**
     * gets CompletedAt
     *
     * @return \DateTime
     */
    public function getCompletedAt(): \DateTime
    {
        return $this->completedAt;
    }

    /**
     * gets Country
     *
     * @return string
     */
    public function getCountry(): string
    {
        return $this->country;
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
     * gets Custom
     *
     * @return mixed
     */
    public function getCustom()
    {
        return $this->custom;
    }

    /**
     * gets CustomLineItems
     *
     * @return array
     */
    public function getCustomLineItems(): array
    {
        return $this->customLineItems;
    }

    /**
     * gets CustomerEmail
     *
     * @return string
     */
    public function getCustomerEmail(): string
    {
        return $this->customerEmail;
    }

    /**
     * gets CustomerGroup
     *
     * @return mixed
     */
    public function getCustomerGroup()
    {
        return $this->customerGroup;
    }

    /**
     * gets CustomerId
     *
     * @return string
     */
    public function getCustomerId(): string
    {
        return $this->customerId;
    }

    /**
     * gets DiscountCodes
     *
     * @return array
     */
    public function getDiscountCodes(): array
    {
        return $this->discountCodes;
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
     * gets InventoryMode
     *
     * @return string
     */
    public function getInventoryMode(): string
    {
        return $this->inventoryMode;
    }

    /**
     * gets LastMessageSequenceNumber
     *
     * @return int
     */
    public function getLastMessageSequenceNumber(): int
    {
        return $this->lastMessageSequenceNumber;
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
     * gets LineItems
     *
     * @return array
     */
    public function getLineItems(): array
    {
        return $this->lineItems;
    }

    /**
     * gets Locale
     *
     * @return string
     */
    public function getLocale(): string
    {
        return $this->locale;
    }

    /**
     * gets Odernumber
     *
     * @return string
     */
    public function getOdernumber(): string
    {
        return $this->odernumber;
    }

    /**
     * gets OrderState
     *
     * @return mixed
     */
    public function getOrderState()
    {
        return $this->orderState;
    }

    /**
     * gets PaymentInfo
     *
     * @return mixed
     */
    public function getPaymentInfo()
    {
        return $this->paymentInfo;
    }

    /**
     * gets PaymentState
     *
     * @return mixed
     */
    public function getPaymentState()
    {
        return $this->paymentState;
    }

    /**
     * gets ReturnInfo
     *
     * @return mixed
     */
    public function getReturnInfo()
    {
        return $this->returnInfo;
    }

    /**
     * gets ShipmentState
     *
     * @return mixed
     */
    public function getShipmentState()
    {
        return $this->shipmentState;
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
     * gets ShippingInfo
     *
     * @return mixed
     */
    public function getShippingInfo()
    {
        return $this->shippingInfo;
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
     * gets SyncInfo
     *
     * @return mixed
     */
    public function getSyncInfo()
    {
        return $this->syncInfo;
    }

    /**
     * gets TaxMode
     *
     * @return mixed
     */
    public function getTaxMode()
    {
        return $this->taxMode;
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
     * gets TotalPrice
     *
     * @return mixed
     */
    public function getTotalPrice()
    {
        return $this->totalPrice;
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
     * Sets AnonymousId
     *
     * @param string $anonymousId
     */
    public function setAnonymousId(string $anonymousId)
    {
        $this->anonymousId = $anonymousId;
    }

    /**
     * Sets BillingAddress
     *
     * @param mixed $billingAddress
     */
    public function setBillingAddress($billingAddress)
    {
        $this->billingAddress = $billingAddress;
    }

    /**
     * Sets Cart
     *
     * @param mixed $cart
     */
    public function setCart($cart)
    {
        $this->cart = $cart;
    }

    /**
     * Sets CompletedAt
     *
     * @param \DateTime $completedAt
     */
    public function setCompletedAt(\DateTime $completedAt)
    {
        $this->completedAt = $completedAt;
    }

    /**
     * Sets Country
     *
     * @param string $country
     */
    public function setCountry(string $country)
    {
        $this->country = $country;
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
     * Sets Custom
     *
     * @param mixed $custom
     */
    public function setCustom($custom)
    {
        $this->custom = $custom;
    }

    /**
     * Sets CustomLineItems
     *
     * @param array $customLineItems
     */
    public function setCustomLineItems(array $customLineItems)
    {
        $this->customLineItems = $customLineItems;
    }

    /**
     * Sets CustomerEmail
     *
     * @param string $customerEmail
     */
    public function setCustomerEmail(string $customerEmail)
    {
        $this->customerEmail = $customerEmail;
    }

    /**
     * Sets CustomerGroup
     *
     * @param mixed $customerGroup
     */
    public function setCustomerGroup($customerGroup)
    {
        $this->customerGroup = $customerGroup;
    }

    /**
     * Sets CustomerId
     *
     * @param string $customerId
     */
    public function setCustomerId(string $customerId)
    {
        $this->customerId = $customerId;
    }

    /**
     * Sets DiscountCodes
     *
     * @param array $discountCodes
     */
    public function setDiscountCodes(array $discountCodes)
    {
        $this->discountCodes = $discountCodes;
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
     * Sets InventoryMode
     *
     * @param string $inventoryMode
     */
    public function setInventoryMode(string $inventoryMode)
    {
        $this->inventoryMode = $inventoryMode;
    }

    /**
     * Sets LastMessageSequenceNumber
     *
     * @param int $lastMessageSequenceNumber
     */
    public function setLastMessageSequenceNumber(int $lastMessageSequenceNumber)
    {
        $this->lastMessageSequenceNumber = $lastMessageSequenceNumber;
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
     * Sets LineItems
     *
     * @param array $lineItems
     */
    public function setLineItems(array $lineItems)
    {
        $this->lineItems = $lineItems;
    }

    /**
     * Sets Locale
     *
     * @param string $locale
     */
    public function setLocale(string $locale)
    {
        $this->locale = $locale;
    }

    /**
     * Sets Odernumber
     *
     * @param string $odernumber
     */
    public function setOdernumber(string $odernumber)
    {
        $this->odernumber = $odernumber;
    }

    /**
     * Sets OrderState
     *
     * @param mixed $orderState
     */
    public function setOrderState($orderState)
    {
        $this->orderState = $orderState;
    }

    /**
     * Sets PaymentInfo
     *
     * @param mixed $paymentInfo
     */
    public function setPaymentInfo($paymentInfo)
    {
        $this->paymentInfo = $paymentInfo;
    }

    /**
     * Sets PaymentState
     *
     * @param mixed $paymentState
     */
    public function setPaymentState($paymentState)
    {
        $this->paymentState = $paymentState;
    }

    /**
     * Sets ReturnInfo
     *
     * @param mixed $returnInfo
     */
    public function setReturnInfo($returnInfo)
    {
        $this->returnInfo = $returnInfo;
    }

    /**
     * Sets ShipmentState
     *
     * @param mixed $shipmentState
     */
    public function setShipmentState($shipmentState)
    {
        $this->shipmentState = $shipmentState;
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
     * Sets ShippingInfo
     *
     * @param mixed $shippingInfo
     */
    public function setShippingInfo($shippingInfo)
    {
        $this->shippingInfo = $shippingInfo;
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
     * Sets SyncInfo
     *
     * @param mixed $syncInfo
     */
    public function setSyncInfo($syncInfo)
    {
        $this->syncInfo = $syncInfo;
    }

    /**
     * Sets TaxMode
     *
     * @param mixed $taxMode
     */
    public function setTaxMode($taxMode)
    {
        $this->taxMode = $taxMode;
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

    /**
     * Sets TotalPrice
     *
     * @param mixed $totalPrice
     */
    public function setTotalPrice($totalPrice)
    {
        $this->totalPrice = $totalPrice;
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
