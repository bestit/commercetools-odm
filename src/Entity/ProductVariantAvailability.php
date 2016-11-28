<?php
namespace BestIt\CommercetoolsODM\Entity;

use BestIt\CommercetoolsODM\Mapping\Annotations as Commercetools;

class ProductVariantAvailability
{
    /**
     * The number of items of this product variant that are currently available in stock.
     * @Commercetools\Field(type="int")
     * @Commercetools\AvailableQuantity
     * @var int
     */
    private $availableQuantity = 0;

    /**
     * Map of ProductVariantAvailability per Channel id - Optional
     * For each Inventory Entries with a supply channel, an entry is added into channels:
     * the key is the Channel id
     * the value is an object containing the data isOnStock, restockableInDays and availableQuantity
     * for the Inventory Entry with the supply channel for this variant.
     * @Commercetools\Field(type="") TODO Map of ProductVariantAvailability per Channel
     * @Commercetools\Channels
     * @var
     */
    private $channels;

    /**
     * The IsOnStcok for the type.
     * @Commercetools\Field(type="boolean")
     * @Commercetools\IsOnStock
     * @var boolean
     */
    private $isOnStock = false;

    /**
     * The number of days it takes to restock a product once it is out of stock.
     * @Commercetools\Field(type="int")
     * @Commercetools\RestockableInDays
     * @var int
     */
    private $restockableInDays = 0;

    /**
     * gets AvailableQuantity
     *
     * @return int
     */
    public function getAvailableQuantity(): int
    {
        return $this->availableQuantity;
    }

    /**
     * gets Channels
     *
     * @return mixed
     */
    public function getChannels()
    {
        return $this->channels;
    }

    /**
     * gets RestockableInDays
     *
     * @return int
     */
    public function getRestockableInDays(): int
    {
        return $this->restockableInDays;
    }

    /**
     * iss IsOnStock
     *
     * @return boolean
     */
    public function isIsOnStock(): bool
    {
        return $this->isOnStock;
    }

    /**
     * Sets AvailableQuantity
     *
     * @param int $availableQuantity
     */
    public function setAvailableQuantity(int $availableQuantity)
    {
        $this->availableQuantity = $availableQuantity;
    }

    /**
     * Sets Channels
     *
     * @param mixed $channels
     */
    public function setChannels($channels)
    {
        $this->channels = $channels;
    }

    /**
     * Sets IsOnStock
     *
     * @param boolean $isOnStock
     */
    public function setIsOnStock(bool $isOnStock)
    {
        $this->isOnStock = $isOnStock;
    }

    /**
     * Sets RestockableInDays
     *
     * @param int $restockableInDays
     */
    public function setRestockableInDays(int $restockableInDays)
    {
        $this->restockableInDays = $restockableInDays;
    }
}