<?php
namespace BestIt\CommercetoolsODM\Entity;
use BestIt\CommercetoolsODM\Mapping\Annotations as Commercetools;

class InventoryEntry
{
    /**  TODO @var!!
     * Available amount of stock. (available means: quantityOnStock - reserved quantity)
     * @Commercetools\Field(type="string")
     * @Commercetools\AvailibleQuantity
     * @var string
     */
    private $availableQuantity = '';
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
     * The date and time of the next restock.
     * @Commercetools\Field(type="datetime")
     * @Commercetools\ExpectedDelivery
     * @var datetime
     */
    private $expectedDelivery = '';
    /**   
     * The unique ID of the inventory entry.
     * @Commercetools\Field(type="string")
     * @Commercetools\Id
     * @var string
     */
    private $id = '';
    /**
     * The LastModifiedAt for the type
     * @Commercetools\Field(type="datetime")
     * @Commercetools\LastModifiedAt
     * @var datetime
     */
    private $lastModifiedAt = '';
    /**
     * Overall amount of stock. (available + reserved)
     * @Commercetools\Field(type="int")
     * @Commercetools\QuantityOnStock
     * @var int
     */
    private $quantityOnStock = '';
    /**
     * The time period in days, that tells how often this inventory entry is restocked.
     * @Commercetools\Field(type="int")
     * @Commercetools\RestockableInDays
     * @var int
     */
    private $restockableInDays = '';
    /**
     * The Sku for the type.
     * @Commercetools\Field(type="string")
     * @Commercetools\Sku
     * @var string
     */
    private $sku = '';
    /**
     * Optional connection to particular supplier.
     * @Commercetools\Field(type="") TODO
     * @Commercetools\SupplyChannel
     * @var
     */
    private $supplyChannel = '';
    /**
     * The Version for this type.
     * @Commercetools\Field(type="int")
     * @Commercetools\Version
     * @var int
     */
    private $version = '';

    /**
     * Returns the AvailableQuantity for the type.
     * @return string
     */
    public function getAvailableQuantity(): string
    {
        return $this->availableQuantity;
    }

    /**
     * Sets the AvailableQuantity for the type.
     * @param string $availableQuantity
     * @return InventoryEntry
     */
    public function setAvailableQuantity(string $availableQuantity): InventoryEntry
    {
        $this->availableQuantity = $availableQuantity;

        return $this;
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
     * @return InventoryEntry
     */
    public function setCreatedAt(string $createdAt): InventoryEntry
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Return the Custom for the type.
     * @return string
     */
    public function getCustom(): string
    {
        return $this->custom;
    }

    /**
     * Sets the Custom for the type.
     * @param string $custom
     * @return InventoryEntry
     */
    public function setCustom(string $custom): InventoryEntry
    {
        $this->custom = $custom;

        return $this;
    }

    /**
     * Returns the ExpectedDelivery
     * @return string
     */
    public function getExpectedDelivery(): string
    {
        return $this->expectedDelivery;
    }

    /**
     * Sets the ExpectedDelivery for the type.
     * @param string $expectedDelivery
     * @return InventoryEntry
     */
    public function setExpectedDelivery(string $expectedDelivery): InventoryEntry
    {
        $this->expectedDelivery = $expectedDelivery;

        return $this;
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
     * @return InventoryEntry
     */
    public function setId(string $id): InventoryEntry
    {
        $this->id = $id;

        return $this;
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
     * Sets the Last ModifiedAt for the type.
     * @param string $lastModifiedAt
     * @return InventoryEntry
     */
    public function setLastModifiedAt(string $lastModifiedAt): InventoryEntry
    {
        $this->lastModifiedAt = $lastModifiedAt;

        return $this;
    }

    /**
     * Returns the QuantityOnStock
     * @return string
     */
    public function getQuantityOnStock(): string
    {
        return $this->quantityOnStock;
    }

    /**
     * Sets the QuantityOnStock for the type.
     * @param string $quantityOnStock
     * @return InventoryEntry
     */
    public function setQuantityOnStock(string $quantityOnStock): InventoryEntry
    {
        $this->quantityOnStock = $quantityOnStock;

        return $this;
    }

    /**
     * Returns the RestockableInDays for the type.
     * @return string
     */
    public function getRestockableInDays(): string
    {
        return $this->restockableInDays;
    }

    /**
     * Sets the RestockableInDays for the type.
     * @param string $restockableInDays
     * @return InventoryEntry
     */
    public function setRestockableInDays(string $restockableInDays): InventoryEntry
    {
        $this->restockableInDays = $restockableInDays;

        return $this;
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
     * @return InventoryEntry
     */
    public function setSku(string $sku): InventoryEntry
    {
        $this->sku = $sku;

        return $this;
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
     * @return InventoryEntry
     */
    public function setSupplyChannel(string $supplyChannel): InventoryEntry
    {
        $this->supplyChannel = $supplyChannel;

        return $this;
    }

    /**
     * Returns the Verison for the type.
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * Sets the Version for the type.for the type.
     * @param string $version
     * @return InventoryEntry
     */
    public function setVersion(string $version): InventoryEntry
    {
        $this->version = $version;

        return $this;
    }

}
