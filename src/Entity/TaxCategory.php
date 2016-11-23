<?php
namespace BestIt\CommercetoolsODM\Entity;

use BestIt\CommercetoolsODM\Mapping\Annotations as Commercetools;

/**
 * Entity for Product Types.
 * @author Paul Tenbrock <Paul_Tenbrock@outlook.com>
 * @Commercetools\DraftClass("Commercetools\Core\Model\ProductType\ProductTypeDraft")
 * @Commercetools\Entity(requestMap=@Commercetools\RequestMap(
 *     create="ProductTypeCreateRequest",
 *     defaultNamespace="Commercetools\Core\Request\ProductTypes",
 *     deleteById="ProductTypeDeleteRequest",
 *     deleteByKey="ProductTypeDeleteByKeyRequest",
 *     findById="ProductTypeByIdGetRequest",
 *     findByKey="ProductTypeByKeyGetRequest",
 *     query="ProductTypeQueryRequest",
 *     updateById="ProductTypeUpdateRequest",
 *     updateByKey="ProductTypeUpdateByKeyRequest"
 * ))
 * @Commercetools\Repository("BestIt\CommercetoolsODM\Model\ProductTypeRepository")
 * @package BestIt\CommercetoolsODM
 * @subpackage Entity
 * @version $id$
 */
class TaxCategory

{

    /**
     * The Id of the document.
     * @var string
     */
    private $id = '';
    /**
     * The Version for the type.
     * @var string
     */
    private $version = '';
    /**
     * The CreatedAt for the type.
     * @var string
     */
    private $createdAt = '';
    /**
     * The LastModifiedAt for the type.
     * @var string
     */
    private $lastModifiedAt = '';
    /**
     * The Name for the type.
     * @var string
     */
    private $name = '';
    /**
     * The Description for the type.
     * @var string
     */
    private $description = '';
    /**
     * The Rates for the type.
     * @var string
     */
    private $rates = '';

    /**
     * Returns the Id of the document.
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Returns the Version for the type.
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
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
     * Returns the LastModifiedAt for the type.
     * @return string
     */
    public function getLastModifiedAt(): string
    {
        return $this->lastModifiedAt;
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
     * Returns the Description for the type.
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Returns the Rates for the type.
     * @return string
     */
    public function getRates(): string
    {
        return $this->rates;
    }


    /**
     * Sets the Id of the document.
     * @param string $id
     * @return TaxCategory
     */
    public function setId(string $id): TaxCategory
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Sets the Version for the type.
     * @param string $version
     * @return TaxCategory
     */
    public function setVersion(string $version): TaxCategory
    {
        $this->version = $version;

        return $this;
    }

    /**
     * Sets the CreatedAt for the type.
     * @param string $createdAt
     * @return TaxCategory
     */
    public function setCreatedAt(string $createdAt): TaxCategory
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Sets the LastModifiedAt for the type.
     * @param string $lastModifiedAt
     * @return TaxCategory
     */
    public function setLastModifiedAt(string $lastModifiedAt): TaxCategory
    {
        $this->lastModifiedAt = $lastModifiedAt;

        return $this;
    }

    /**
     * Sets the Name for the type.
     * @param string $name
     * @return TaxCategory
     */
    public function setName(string $name): TaxCategory
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Sets the Description for the type.
     * @param string $description
     * @return TaxCategory
     */
    public function setDescription(string $description): TaxCategory
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Sets the Rates for the type.
     * @param string $rates
     * @return TaxCategory
     */
    public function setRates(string $rates): TaxCategory
    {
        $this->rates = $rates;

        return $this;
    }

}
