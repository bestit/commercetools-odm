<?php

namespace BestIt\CommercetoolsODM\Entity;

/**
 * The basic entity with the default propertiers version and id.
 *
 * @author lange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\Entity
 * @subpackage Entity
 */
trait BaseEntityTrait
{
    /**
     * The Id of this document.
     *
     * @Commercetools\Field(type="string")
     * @Commercetools\Id
     * @var string
     */
    private $id = '';

    /**
     * The version of this document.
     *
     * @Commercetools\Field(type="int")
     * @Commercetools\Version
     * @var int
     */
    private $version = 0;

    /**
     * Returns the id of this document.
     *
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Returns the version of this document.
     *
     * @return int
     */
    public function getVersion(): int
    {
        return $this->version;
    }

    /**
     * Sets the ID of this document.
     *
     * @param string $id
     *
     * @return ProductType
     */
    public function setId(string $id): ProductType
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Sets the version of this document.
     *
     * @param int $version
     *
     * @return ProductType
     */
    public function setVersion(int $version): ProductType
    {
        $this->version = $version;

        return $this;
    }
}
