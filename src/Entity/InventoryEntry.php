<?php
namespace BestIt\CommercetoolsODM\Entity;

use BestIt\CommercetoolsODM\Mapping\Annotations as Commercetools;

/**
 * Entity for Inventories.
 * @Commercetools\DraftClass("Commercetools\Core\Model\Inventory\InventoryDraft")
 * @Commercetools\Entity(requestMap=@Commercetools\RequestMap(
 *     defaultNamespace="Commercetools\Core\Request\Inventories",
 *     findById="InventoryByIdGetRequest",
 *     query="InventoryQueryRequest",
 *     create="InventoryCreateRequest",
 *     update="InventoryUpdateRequest",
 *     delete="InventoryDeleteRequest",
 * ))
 * @Commercetools\Repository("BestIt\CommercetoolsODM\Model\InventoryRepository")
 * @package BestIt\CommercetoolsODM
 * @subpackage Entity
 * @version $id$
 */
class InventoryEntry
{
    /**
     * Available amount of stock. (available means: quantityOnStock - reserved quantity)
     * @Commercetools\Field(type="string")
     * @Commercetools\AvailibleQuantity
     * @var string
     */
    private $availableQuantity = 0;

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
     * The date and time of the next restock.
     * @Commercetools\Field(type="datetime")
     * @Commercetools\ExpectedDelivery
     * @var \DateTime
     */
    private $expectedDelivery;

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
     * @var \DateTime
     */
    private $lastModifiedAt;

    /**
     * Overall amount of stock. (available + reserved)
     * @Commercetools\Field(type="int")
     * @Commercetools\QuantityOnStock
     * @var int
     */
    private $quantityOnStock = 0;
    /**
     * The time period in days, that tells how often this inventory entry is restocked.
     * @Commercetools\Field(type="int")
     * @Commercetools\RestockableInDays
     * @var int
     */
    private $restockableInDays = 0;

    /**
     * The Sku for the type.
     * @Commercetools\Field(type="string")
     * @Commercetools\Sku
     * @var string
     */
    private $sku = '';

    /**
     * Optional connection to particular supplier.
     * @Commercetools\Field(type="") TODO Reference
     * @Commercetools\SupplyChannel
     * @var
     */
    private $supplyChannel;

    /**
     * The Version for this type.
     * @Commercetools\Field(type="int")
     * @Commercetools\Version
     * @var int
     */
    private $version = 0;

    /**
     * gets AvailableQuantity
     *
     * @return string
     */
    public function getAvailableQuantity(): string
    {
        return $this->availableQuantity;
    }

    /**
     * Sets AvailableQuantity
     *
     * @param string $availableQuantity
     */
    public function setAvailableQuantity(string $availableQuantity)
    {
        $this->availableQuantity = $availableQuantity;
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
     * Sets CreatedAt
     *
     * @param \DateTime $createdAt
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
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
     * Sets Custom
     *
     * @param mixed $custom
     */
    public function setCustom($custom)
    {
        $this->custom = $custom;
    }

    /**
     * gets ExpectedDelivery
     *
     * @return \DateTime
     */
    public function getExpectedDelivery(): \DateTime
    {
        return $this->expectedDelivery;
    }

    /**
     * Sets ExpectedDelivery
     *
     * @param \DateTime $expectedDelivery
     */
    public function setExpectedDelivery(\DateTime $expectedDelivery)
    {
        $this->expectedDelivery = $expectedDelivery;
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
     * gets LastModifiedAt
     *
     * @return \DateTime
     */
    public function getLastModifiedAt(): \DateTime
    {
        return $this->lastModifiedAt;
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
     * gets QuantityOnStock
     *
     * @return int
     */
    public function getQuantityOnStock(): int
    {
        return $this->quantityOnStock;
    }

    /**
     * Sets QuantityOnStock
     *
     * @param int $quantityOnStock
     */
    public function setQuantityOnStock(int $quantityOnStock)
    {
        $this->quantityOnStock = $quantityOnStock;
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
     * Sets RestockableInDays
     *
     * @param int $restockableInDays
     */
    public function setRestockableInDays(int $restockableInDays)
    {
        $this->restockableInDays = $restockableInDays;
    }

    /**
     * gets Sku
     *
     * @return string
     */
    public function getSku(): string
    {
        return $this->sku;
    }

    /**
     * Sets Sku
     *
     * @param string $sku
     */
    public function setSku(string $sku)
    {
        $this->sku = $sku;
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
     * gets Version
     *
     * @return int
     */
    public function getVersion(): int
    {
        return $this->version;
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
