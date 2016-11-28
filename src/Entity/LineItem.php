<?php

namespace BestIt\CommercetoolsODM\Entity;

use BestIt\CommercetoolsODM\Mapping\Annotations as Commercetools;

/**
 * Class ProductType
 * @package BestIt\CommercetoolsODM\Entity
 */
class LineItem
{
    /**
     * The Custom for the tape
     * @Commercetools\Field(type="") TODO CustomFields
     * @Commercetools\Custom
     * @var
     */
    private $custom;

    /**
     * The DiscountPricePerQuantity for the type.
     * @Commercetools\Field(type="array")
     * @Commercetools\DiscountPricePerQuantity
     * @var array
     */
    private $discountedPricePerQuantity = [];

    /**
     * The distribution channel is used to select a ProductPrice. The channel has the role ProductDistribution.
     * @Commercetools\Field(type="") TODO Reference
     * @Commercetools\distributeChannel
     * @var
     */
    private $distributionChannel;

    /**
     * The unique ID of this LineItem.
     * @Commercetools\Field(type="string")
     * @Commercetools\Id
     * @var string
     */
    private $id = '';

    /**
     * The product name.
     * @Commercetools\Field(type="") TODO LocalizedString
     * @Commercetools\Name
     * @var
     */
    private $name;

    /**
     * The Price for the type.
     * @Commercetools\Field(type="") TODO Price
     * @Commercetools\Price
     * @var
     */
    private $price;

    /**
     * The PriceMode for the type.
     * @Commercetools\Field(type="") TODO LineItemPriceMode
     * @Commercetools\PriceMode
     * @var
     */
    private $priceMode;

    /**
     * The ProductId for the type.
     * @Commercetools\Field(type="string")
     * @Commercetools\ProductId
     * @var string
     */
    private $productId = '';

    /**
     * The slug of a product. Added to all line items of carts and orders automatically.
     * @Commercetools\Field(type="") TODO LocalizedString
     * @Commercetools\ProductSlug
     * @var
     */
    private $productSlug;

    /**
     * The Quantity for the type.
     * @Commercetools\Field(type="int")
     * @Commercetools\Quantity
     * @var int
     */
    private $quantity = 0;

    /**
     * The State for the type.
     * @Commercetools\Field(type="array")
     * @Commercetools\State
     * @var array
     */
    private $state = [];

    /**
     * The supply channel identifies the inventory entries that should be reserved.
     * The channel has the role InventorySupply.
     * @Commercetools\Field(type="") TODO Refenrence
     * @Commercetools\SupplyChannel
     * @var
     */
    private $supplyChannel;

    /**
     * Will be set automatically in the Platform TaxMode once the shipping address is set is set.
     * For the External tax mode the tax rate has to be set explicitly with the ExternalTaxRateDraft.
     * @Commercetools\Field(type="") TODO TaxRate
     * @Commercetools\TaxRate
     * @var
     */
    private $taxRate;

    /**
     * Set once the taxRate is set.
     * @Commercetools\Field(type="") TODO TaxedItemPrice
     * @Commercetools\TaxedPrice
     * @var
     */
    private $taxedPrice;

    /**
     * The total price of this line item. If the line item is discounted,
     * then the totalPrice is the DiscountedLineItemPriceForQuantity multiplied by quantity.
     * Otherwise the total price is the product price multiplied by the quantity.
     * totalPrice may or may not include the taxes: it depends on the taxRate.includedInPrice property.
     * @Commercetools\Field(type="") TODO Money
     * @Commercetools\TotalPrice
     * @var
     */
    private $totalPrice;

    /**
     * The Variant for the type.
     * @Commercetools\Field(type="") TODO ProductVariant
     * @Commercetools\Variant
     * @var
     */
    private $variant = '';

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
     * Sets Custom
     *
     * @param mixed $custom
     */
    public function setCustom($custom)
    {
        $this->custom = $custom;
    }

    /**
     * gets DiscountedPricePerQuantity
     *
     * @return array
     */
    public function getDiscountedPricePerQuantity(): array
    {
        return $this->discountedPricePerQuantity;
    }

    /**
     * Sets DiscountedPricePerQuantity
     *
     * @param array $discountedPricePerQuantity
     */
    public function setDiscountedPricePerQuantity(array $discountedPricePerQuantity)
    {
        $this->discountedPricePerQuantity = $discountedPricePerQuantity;
    }

    /**
     * gets DistributionChannel
     *
     * @return mixed
     */
    public function getDistributionChannel()
    {
        return $this->distributionChannel;
    }

    /**
     * Sets DistributionChannel
     *
     * @param mixed $distributionChannel
     */
    public function setDistributionChannel($distributionChannel)
    {
        $this->distributionChannel = $distributionChannel;
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
     * Sets Id
     *
     * @param string $id
     */
    public function setId(string $id)
    {
        $this->id = $id;
    }

    /**
     * gets Name
     *
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets Name
     *
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
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
     * Sets Price
     *
     * @param mixed $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * gets PriceMode
     *
     * @return mixed
     */
    public function getPriceMode()
    {
        return $this->priceMode;
    }

    /**
     * Sets PriceMode
     *
     * @param mixed $priceMode
     */
    public function setPriceMode($priceMode)
    {
        $this->priceMode = $priceMode;
    }

    /**
     * gets ProductId
     *
     * @return string
     */
    public function getProductId(): string
    {
        return $this->productId;
    }

    /**
     * Sets ProductId
     *
     * @param string $productId
     */
    public function setProductId(string $productId)
    {
        $this->productId = $productId;
    }

    /**
     * gets ProductSlug
     *
     * @return mixed
     */
    public function getProductSlug()
    {
        return $this->productSlug;
    }

    /**
     * Sets ProductSlug
     *
     * @param mixed $productSlug
     */
    public function setProductSlug($productSlug)
    {
        $this->productSlug = $productSlug;
    }

    /**
     * gets Quantity
     *
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * Sets Quantity
     *
     * @param int $quantity
     */
    public function setQuantity(int $quantity)
    {
        $this->quantity = $quantity;
    }

    /**
     * gets State
     *
     * @return array
     */
    public function getState(): array
    {
        return $this->state;
    }

    /**
     * Sets State
     *
     * @param array $state
     */
    public function setState(array $state)
    {
        $this->state = $state;
    }

    /**
     * gets SupplyChannel
     *
     * @return mixed
     */
    public function getSupplyChannel()
    {
        return $this->supplyChannel;
    }

    /**
     * Sets SupplyChannel
     *
     * @param mixed $supplyChannel
     */
    public function setSupplyChannel($supplyChannel)
    {
        $this->supplyChannel = $supplyChannel;
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
     * Sets TaxRate
     *
     * @param mixed $taxRate
     */
    public function setTaxRate($taxRate)
    {
        $this->taxRate = $taxRate;
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
     * Sets TaxedPrice
     *
     * @param mixed $taxedPrice
     */
    public function setTaxedPrice($taxedPrice)
    {
        $this->taxedPrice = $taxedPrice;
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
     * Sets TotalPrice
     *
     * @param mixed $totalPrice
     */
    public function setTotalPrice($totalPrice)
    {
        $this->totalPrice = $totalPrice;
    }

    /**
     * gets Variant
     *
     * @return mixed
     */
    public function getVariant()
    {
        return $this->variant;
    }

    /**
     * Sets Variant
     *
     * @param mixed $variant
     */
    public function setVariant($variant)
    {
        $this->variant = $variant;
    }
}
