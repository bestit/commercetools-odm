<?php

namespace BestIt\CommercetoolsODM\Entity;

use BestIt\CommercetoolsODM\Mapping\Annotations as Commercetools;

/**         TODO @var!!
 * Class ProductType
 * @package BestIt\CommercetoolsODM\Entity
 */
class ProductType
{
    /**
     * The unique ID of this LineItem.
     * @Commercetools\Field(type="string")
     * @Commercetools\Id
     * @var string
     */
    private $id = '';
    /**
     * The ProduczId for the type.
     * @Commercetools\Field(type="string")
     * @Commercetools\ProductId
     * @var string
     */
    private $productId = '';
    /**
     * The product name.
     * @Commercetools\Field(type="string")
     * @Commercetools\Name
     * @var string
     */
    private $name = '';
    /**
     * The slug of a product. Added to all line items of carts and orders automatically.
     * @Commercetools\Field(type="string")
     * @Commercetools\ProductSlug
     * @var string
     */
    private $productSlug = '';
    /**
     * The Variant for the type.
     * @Commercetools\Field(type="") TODO
     * @Commercetools\Variant
     * @var
     */
    private $variant = '';
    /**
     * The Price for the type.
     * @Commercetools\Field(type="") TODO
     * @Commercetools\Price
     * @var
     */
    private $price = '';
    /**
     * Set once the taxRate is set.
     * @Commercetools\Field(type="") TODO
     * @Commercetools\TaxedPrice
     * @var
     */
    private $taxedPrice = '';
    /**
     * The total price of this line item. If the line item is discounted, then the totalPrice is the DiscountedLineItemPriceForQuantity multiplied by quantity. Otherwise the total price is the product price multiplied by the quantity. totalPrice may or may not include the taxes: it depends on the taxRate.includedInPrice property.
     * @Commercetools\Field(type="") TODO
     * @Commercetools\TotalPrice
     * @var
     */
    private $totalPrice = '';
    /**
     * The Quantity for the type.
     * @Commercetools\Field(type="") TODO
     * @Commercetools\TotalPrice
     * @var
     */
    private $quantity = '';
    /**
     * The State for the type.
     * @Commercetools\Field(type="array")
     * @Commercetools\State
     * @var array
     */
    private $state = '';
    /**
     * Will be set automatically in the Platform TaxMode once the shipping address is set is set. For the External tax mode the tax rate has to be set explicitly with the ExternalTaxRateDraft.
     * @Commercetools\Field(type="")
     * @Commercetools\TaxRate
     * @var
     */
    private $taxRate = '';
    /**
     * The supply channel identifies the inventory entries that should be reserved. The channel has the role InventorySupply.
     * @Commercetools\Field(type="") TODO
     * @Commercetools\SupplyChannel
     * @var
     */
    private $supplyChannel = '';
    /**
     * The distribution channel is used to select a ProductPrice. The channel has the role ProductDistribution.
     * @Commercetools\Field(type="") TODO
     * @Commercetools\distributeChannel
     * @var
     */
    private $distributionChannel = '';
    /**
     * The DiscountPricePerQuantity for the type.
     * @Commercetools\Field(type="array")
     * @Commercetools\DiscountPricePerQuantity
     * @var array
     */
    private $discountedPricePerQuantity= '';
    /**
     * The PriceMode for the type.
     * @Commercetools\Field(type="") TODO
     * @Commercetools\PriceMode
     * @var
     */
    private $priceMode = '';
    /**
     * The Custom for the tape
     * @Commercetools\Field(type="") TODO
     * @Commercetools\Custom
     * @var
     */
    private $custom = '';

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
     * Returns the ProductId for the type.
     * @return string
     */
    public function getProductId(): string
    {
        return $this->productId;
    }

    /**
     * Sets the ProductId for the type.
     * @param string $productId
     */
    public function setProductId(string $productId)
    {
        $this->productId = $productId;
    }

    /**
     * Returns the Name for the type.
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
     * Returns the ProductSlug for the type.
     * @return string
     */
    public function getProductSlug(): string
    {
        return $this->productSlug;
    }

    /**
     * Sets the ProductSlug for the type.
     * @param string $productSlug
     */
    public function setProductSlug(string $productSlug)
    {
        $this->productSlug = $productSlug;
    }

    /**
     * Returns the Varian for the type.
     * @return string
     */
    public function getVariant(): string
    {
        return $this->variant;
    }

    /**
     * Sets the Variant for the type.
     * @param string $variant
     */
    public function setVariant(string $variant)
    {
        $this->variant = $variant;
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
     * Returns the SupplyChannel for the type.
     * @return string
     */
    public function getSupplyChannel(): string
    {
        return $this->supplyChannel;
    }

    /**
     * Sets the SupplyChannel for the type.
     * @param string $supplyChannel
     */
    public function setSupplyChannel(string $supplyChannel)
    {
        $this->supplyChannel = $supplyChannel;
    }

    /**
     * Returns the DistributeChannel for the type.
     * @return string
     */
    public function getDistributionChannel(): string
    {
        return $this->distributionChannel;
    }

    /**
     * Sets the distributeChannel for the type.
     * @param string $distributionChannel
     */
    public function setDistributionChannel(string $distributionChannel)
    {
        $this->distributionChannel = $distributionChannel;
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
     * Sets the DiscountedPricePerQuantity for the type.
     * @param string $discountedPricePerQuantity
     */
    public function setDiscountedPricePerQuantity(string $discountedPricePerQuantity)
    {
        $this->discountedPricePerQuantity = $discountedPricePerQuantity;
    }

    /**
     *
     * Returns the PriceMode for the type.
     * @return string
     */
    public function getPriceMode(): string
    {
        return $this->priceMode;
    }

    /**
     * Sets the PriceMode for the type.
     * @param string $priceMode
     */
    public function setPriceMode(string $priceMode)
    {
        $this->priceMode = $priceMode;
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

}