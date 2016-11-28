<?php

namespace BestIt\CommercetoolsODM\Entity;

use BestIt\CommercetoolsODM\Mapping\Annotations as Commercetools;

/**
 * Entity for Zones.
 * Groups locations to a zone. A zone is used to define a ShippingRate for a set of locations.
 * @Commercetools\DraftClass("Commercetools\Core\Model\Zone\ZoneDraft")
 * @Commercetools\Entity(requestMap=@Commercetools\RequestMap(
 *     defaultNamespace="Commercetools\Core\Request\Zones",
 *     findById="ZoneByIdGetRequest",
 *     query="ZoneQueryRequest",
 *     create="ZoneCreateRequest",
 *     update="ZoneUpdateRequest",
 *     delete="ZoneDeleteRequest"
 * ))
 * @Commercetools\Repository("BestIt\CommercetoolsODM\Model\ZoneRepository")
 * @package BestIt\CommercetoolsODM
 * @subpackage Entity
 * @version $id$
 */
class Zone
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
     * The unique ID of the zone.
     * @Commercetools\Field(type="string")
     * @Commercetools\Id
     * @var string
     */
    private $id;

    /**
     * @Commercetools\Field(type="datetime")
     * @Commercetools\LastModifiedAt
     * @var \DateTime
     */
    private $lastModifiedAt;

    /**
     * @Commercetools\Field(type="array")
     * @Commercetools\Locations
     * @var array
     */
    private $locations;

    /**
     * @Commercetools\Field(type="string")
     * @Commercetools\Name
     * @var string
     */
    private $name;

    /**
     * @Commercetools\Field(type="int")
     * @Commercetools\Version
     * @var int
     */
    private $version;

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
     * @return mixed
     */
    public function getId()
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
     * gets Locations
     *
     * @return array
     */
    public function getLocations(): array
    {
        return $this->locations;
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
     * gets Version
     *
     * @return int
     */
    public function getVersion(): int
    {
        return $this->version;
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
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
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
     * Sets Locations
     *
     * @param array $locations
     */
    public function setLocations(array $locations)
    {
        $this->locations = $locations;
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
     * Sets Version
     *
     * @param int $version
     */
    public function setVersion(int $version)
    {
        $this->version = $version;
    }
}
