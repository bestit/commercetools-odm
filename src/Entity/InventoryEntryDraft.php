<?php

namespace BestIt\CommercetoolsODM\Entity;
use BestIt\CommercetoolsODM\Mapping\Annotations as Commercetools;

/**
 * Class InventoryEntryDraft
 * @package BestIt\CommercetoolsODM\Entity
 */
class InventoryEntryDraft
{
    /**
     * @var string
     */
    private $sku = '';
    /**
     * @var string
     */
    private $quantityOnStock = '';
    /**
     * @var string
     */
    private $restockableInDays = '';
    /**
     * @var string
     */
    private $expectedDelivery = '';
    /**
     * @var string
     */
    private $supplyChannel = '';
    /**
     * @var string
     */
    private $custom = '';

    /**
     * @return string
     */
    public function getSku(): string
    {
        return $this->sku;
    }

    /**
     * @return string
     */
    public function getQuantityOnStock(): string
    {
        return $this->quantityOnStock;
    }

    /**
     * @return string
     */
    public function getRestockableInDays(): string
    {
        return $this->restockableInDays;
    }

    /**
     * @return string
     */
    public function getExpectedDelivery(): string
    {
        return $this->expectedDelivery;
    }

    /**
     * @return string
     */
    public function getSupplyChannel(): string
    {
        return $this->supplyChannel;
    }

    /**
     * @return string
     */
    public function getCustom(): string
    {
        return $this->custom;
    }


    /**
     * @param string $sku
     * @return InventoryEntryDraft
     */
    public function setSku(string $sku): InventoryEntryDraft
    {
        $this->sku = $sku;

        return $this;
    }

    /**
     * @param string $quantityOnStock
     * @return InventoryEntryDraft
     */
    public function setQuantityOnStock(string $quantityOnStock): InventoryEntryDraft
    {
        $this->quantityOnStock = $quantityOnStock;

        return $this;
    }

    /**
     * @param string $restockableInDays
     * @return InventoryEntryDraft
     */
    public function setRestockableInDays(string $restockableInDays): InventoryEntryDraft
    {
        $this->restockableInDays = $restockableInDays;

        return $this;
    }

    /**
     * @param string $expectedDelivery
     * @return InventoryEntryDraft
     */
    public function setExpectedDelivery(string $expectedDelivery): InventoryEntryDraft
    {
        $this->expectedDelivery = $expectedDelivery;

        return $this;
    }

    /**
     * @param string $supplyChannel
     * @return InventoryEntryDraft
     */
    public function setSupplyChannel(string $supplyChannel): InventoryEntryDraft
    {
        $this->supplyChannel = $supplyChannel;

        return $this;
    }

    /**
     * @param string $custom
     * @return InventoryEntryDraft
     */
    public function setCustom(string $custom): InventoryEntryDraft
    {
        $this->custom = $custom;

        return $this;
    }

}



