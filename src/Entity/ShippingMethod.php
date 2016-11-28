<?php

namespace BestIt\CommercetoolsODM\Entity;

use BestIt\CommercetoolsODM\Mapping\Annotations as Commercetools;

/**
 * Entity for Product Types.
 * A shipping method defines a specific way of shipping, with different rates for different geographic locations.
 * Example shipping methods are “DHL”, “DHL Express” and “UPS”.
 * @Commercetools\DraftClass("Commercetools\Core\Model\ShippingMethod\ShippingMethodDraft")
 * @Commercetools\Entity(requestMap=@Commercetools\RequestMap(
 *     defaultNamespace="Commercetools\Core\Request\ShippingMethods",
 *     findById="ShippingMethodByIdGetRequest",
 *     findByCartId="ShippingMethodByCartIdGetRequest",
 *     findByRegion="ShippingMethodByLocationGetRequest",
 *     query="ShippingMethodQueryRequest",
 *     create="ShippingMethodCreateRequest",
 *     update="ShippingMethodUpdateRequest",
 *     delete="ShippingMethodDeleteRequest"
 * ))
 * @Commercetools\Repository("BestIt\CommercetoolsODM\Model\ShippingMethodRepository")
 * @package BestIt\CommercetoolsODM
 * @subpackage Entity
 * @version $id$
 */
class ShippingMethod
{
    /**
     * @Commercetools\Field(type="datetime")
     * @Commercetools\CreatedAt
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @Commercetools\Field(type="string")
     * @Commercetools\Description
     * @var string
     */
    private $description;

    /**
     * The unique ID of the shipping method.
     * @Commercetools\Field(type="string")
     * @Commercetools\Id
     * @var string
     */
    private $id;

    /**
     * One shipping method in a project can be default.
     * @Commercetools\Field(type="Boolean")
     * @Commercetools\Version
     * @var boolean
     */
    private $isDefault;

    /**
     * @Commercetools\Field(type="datetime")
     * @Commercetools\LastModifiedAt
     * @var \DateTime
     */
    private $lastModifiedAt;

    /**
     * @Commercetools\Field(type="string")
     * @Commercetools\Name
     * @var string
     */
    private $name;

    /**
     * @Commercetools\Field(type="") TODO Reference
     * @Commercetools\TaxCategory
     * @var
     */
    private $taxCategory;

    /**
     * The current version of the shipping method.
     * @Commercetools\Field(type="int")
     * @Commercetools\Version
     * @var int
     */
    private $version;

    /**
     * @Commercetools\Field(type="array")
     * @Commercetools\ZoneRate
     * @var array
     */
    private $zoneRate;


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
     * gets Description
     *
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
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
     * gets LastModifiedAt
     *
     * @return \DateTime
     */
    public function getLastModifiedAt(): \DateTime
    {
        return $this->lastModifiedAt;
    }

    /**
     * gets Name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * gets TaxCategory
     *
     * @return mixed
     */
    public function getTaxCategory()
    {
        return $this->taxCategory;
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
     * gets ZoneRate
     *
     * @return array
     */
    public function getZoneRate(): array
    {
        return $this->zoneRate;
    }

    /**
     * iss IsDefault
     *
     * @return boolean
     */
    public function isIsDefault(): bool
    {
        return $this->isDefault;
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
     * Sets Description
     *
     * @param string $description
     */
    public function setDescription(string $description)
    {
        $this->description = $description;
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
     * Sets IsDefault
     *
     * @param boolean $isDefault
     */
    public function setIsDefault(bool $isDefault)
    {
        $this->isDefault = $isDefault;
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
     * Sets Name
     *
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * Sets TaxCategory
     *
     * @param mixed $taxCategory
     */
    public function setTaxCategory($taxCategory)
    {
        $this->taxCategory = $taxCategory;
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

    /**
     * Sets ZoneRate
     *
     * @param array $zoneRate
     */
    public function setZoneRate(array $zoneRate)
    {
        $this->zoneRate = $zoneRate;
    }
}
