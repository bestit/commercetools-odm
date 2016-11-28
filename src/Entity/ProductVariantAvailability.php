<?php
namespace BestIt\CommercetoolsODM\Entity;

use BestIt\CommercetoolsODM\Mapping\Annotations as Commercetools;

class ProductVariantAvailability
{
    /**        TODO @var!!
     * The number of items of this product variant that are currently available in stock.
     * @Commercetools\Field(type="int")
     * @Commercetools\AvailableQuantity
     * @var
     */
    private $availableQuantity = '';
    /**
     * Map of ProductVariantAvailability per Channel id - Optional
    For each Inventory Entries with a supply channel, an entry is added into channels:
    the key is the Channel id
    the value is an object containing the data isOnStock, restockableInDays and availableQuantity for the Inventory Entry with the supply channel for this variant.
     * @Commercetools\Field(type="") TODO
     * @Commercetools\Channels
     * @var
     */
    private $channels = '';
    /**
     * The IsOnStcok for the type.
     * @Commercetools\Field(type="boolean")
     * @Commercetools\IsOnStock
     * @var
     */
    private $isOnStock = '';
    /**
     * The number of days it takes to restock a product once it is out of stock.
     * @Commercetools\Field(type="int")
     * @Commercetools\RestockableInDays
     * @var
     */
    private $restockableInDays = '';

    /**
     * Returns the AvailableQuantity from the type.
     * @return string
     */
    public function getAvailableQuantity(): string
    {
        return $this->availableQuantity;
    }

    /**
     * Sets the AvailableQuantity for the type.
     * @param string $availableQuantity
     */
    public function setAvailableQuantity(string $availableQuantity)
    {
        $this->availableQuantity = $availableQuantity;
    }

    /**
     * Returns the Channels for the type.
     * @return string
     */
    public function getChannels(): string
    {
        return $this->channels;
    }

    /**
     * Sets the Channels for the type.
     * @param string $channels
     */
    public function setChannels(string $channels)
    {
        $this->channels = $channels;
    }

    /**
     * Returns the IsOnStock for the type.
     * @return string
     */
    public function getIsOnStock(): string
    {
        return $this->isOnStock;
    }

    /**
     * Sets the IsOnStock for the type.
     * @param string $isOnStock
     */
    public function setIsOnStock(string $isOnStock)
    {
        $this->isOnStock = $isOnStock;
    }

    /**
     * Returns the RestockableInDay for the type.
     * @return string
     */
    public function getRestockableInDays(): string
    {
        return $this->restockableInDays;
    }

    /**
     * Sets the RestockableInDays for the type.
     * @param string $restockableInDays
     */
    public function setRestockableInDays(string $restockableInDays)
    {
        $this->restockableInDays = $restockableInDays;
    }

}