<?php
namespace BestIt\CommercetoolsODM\Entity;
use BestIt\CommercetoolsODM\Mapping\Annotations as Commercetools;

class InventoryEntry
{
    private $id = '';
    private $version = '';
    private $createdAt = '';
    private $lastModifiedAt = '';
    private $sku = '';
    private $supplyChannel = '';
    private $quantityOnStock = '';
    private $availableQuantity = '';
    private $restockableInDays = '';
    private $expectedDelivery = '';
    private $custom = '';

    public function getId(): string
    {
        return $this->id;
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function getLastModifiedAt(): string
    {
        return $this->lastModifiedAt;
    }

    public function getSku(): string
    {
        return $this->sku;
    }

    public function getSupplyChannel(): string
    {
        return $this->supplyChannel;
    }

    public function getQuantityOnStock(): string
    {
        return $this->quantityOnStock;
    }

    public function getAvailableQuantity(): string
    {
        return $this->availableQuantity;
    }

    public function getRestockableInDays(): string
    {
        return $this->restockableInDays;
    }

    public function getExpectedDelivery(): string
    {
        return $this->expectedDelivery;
    }

    public function getCustom(): string
    {
        return $this->custom;
    }


    public function setId(string $id): InventoryEntry
    {
        $this->id = $id;

        return $this;
    }

    public function setVersion(string $version): InventoryEntry
    {
        $this->version = $version;

        return $this;
    }

    public function setCreatedAt(string $createdAt): InventoryEntry
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function setLastModifiedAt(string $lastModifiedAt): InventoryEntry
    {
        $this->lastModifiedAt = $lastModifiedAt;

        return $this;
    }

    public function setSku(string $sku): InventoryEntry
    {
        $this->sku = $sku;

        return $this;
    }

    public function setSupplyChannel(string $supplyChannel): InventoryEntry
    {
        $this->supplyChannel = $supplyChannel;

        return $this;
    }

    public function setQuantityOnStock(string $quantityOnStock): InventoryEntry
    {
        $this->quantityOnStock = $quantityOnStock;

        return $this;
    }

    public function setAvailableQuantity(string $availableQuantity): InventoryEntry
    {
        $this->availableQuantity = $availableQuantity;

        return $this;
    }

    public function setRestockableInDays(string $restockableInDays): InventoryEntry
    {
        $this->restockableInDays = $restockableInDays;

        return $this;
    }

    public function setExpectedDelivery(string $expectedDelivery): InventoryEntry
    {
        $this->expectedDelivery = $expectedDelivery;

        return $this;
    }

    public function setCustom(string $custom): InventoryEntry
    {
        $this->custom = $custom;

        return $this;
    }

}
