<?php

namespace BestIt\CommercetoolsODM\Entity;

use BestIt\CommercetoolsODM\Mapping\Annotations as Commercetools;

/**
 * Entity for Product Discounts.
 * @Commercetools\DraftClass("Commercetools\Core\Model\ProductDiscount\ProductDiscountDraft")
 * @Commercetools\Entity(requestMap=@Commercetools\RequestMap(
 *     defaultNamespace="Commercetools\Core\Request\ProductDiscounts",
 *     findById="ProductDiscountByIdGetRequest",
 *     query="ProductDiscountQueryRequest",
 *     create="ProductDiscountCreateRequest",
 *     update="ProductDiscountUpdateRequest",
 *     delete="ProductDiscountDeleteRequest"
 * ))
 * @Commercetools\Repository("BestIt\CommercetoolsODM\Model\ProductDiscountRepository")
 * @package BestIt\CommercetoolsODM
 * @subpackage Entity
 * @version $id$
 */
class ProductDiscount
{
    /**
     * @Commercetools\Field(type="datetime")
     * @Commercetools\CreatedAt
     * @var \DateTime
     */
    private $createdAt;
    /**
     * @Commercetools\Field(type="") TODO LocalizedString
     * @Commercetools\Description
     * @var
     */
    private $description;
    /**
     * The unique ID of the product discount
     * @Commercetools\Field(type="string")
     * @Commercetools\Name
     * @var string
     */
    private $id;
    /**
     * Only active discount will be applied to product prices.
     * @Commercetools\Field(type="Boolean")
     * @Commercetools\IsActive
     * @var boolean
     */
    private $isActive;
    /**
     * @Commercetools\Field(type="datetime")
     * @Commercetools\LastModifiedAt
     * @var \DateTime
     */
    private $lastModifiedAt;
    /**
     * @Commercetools\Field(type="") TODO LocalizedString
     * @Commercetools\Name
     * @var
     */
    private $name;
    /**
     * A valid ProductDiscount Predicate
     * @Commercetools\Field(type="string")
     * @Commercetools\Predicate
     * @var string
     */
    private $predicate;
    /**
     * Only active discount will be applied to product prices.
     * @Commercetools\Field(type="array")
     * @Commercetools\References
     * @var array
     */
    private $references;
    /**
     * The string contains a number between 0 and 1. A discount with greater sortOrder is prioritized higher
     * than a discount with lower sortOrder. A sortOrder must be unambiguous.
     * @Commercetools\Field(type="string")
     * @Commercetools\SortOrder
     * @var string
     */
    private $sortOrder;
    /**
     * @Commercetools\Field(type="") TODO ProductDiscountValue
     * @Commercetools\Value
     * @var
     */
    private $value;
    /**
     * The current version of the product discount.
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
     * @return mixed
     */
    public function getDescription()
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
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * gets Predicate
     *
     * @return string
     */
    public function getPredicate(): string
    {
        return $this->predicate;
    }

    /**
     * gets References
     *
     * @return array
     */
    public function getReferences(): array
    {
        return $this->references;
    }

    /**
     * gets SortOrder
     *
     * @return string
     */
    public function getSortOrder(): string
    {
        return $this->sortOrder;
    }

    /**
     * gets Value
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
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
     * iss IsActive
     *
     * @return boolean
     */
    public function isIsActive(): bool
    {
        return $this->isActive;
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
     * @param mixed $description
     */
    public function setDescription($description)
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
     * Sets IsActive
     *
     * @param boolean $isActive
     */
    public function setIsActive(bool $isActive)
    {
        $this->isActive = $isActive;
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
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Sets Predicate
     *
     * @param string $predicate
     */
    public function setPredicate(string $predicate)
    {
        $this->predicate = $predicate;
    }

    /**
     * Sets References
     *
     * @param array $references
     */
    public function setReferences(array $references)
    {
        $this->references = $references;
    }

    /**
     * Sets SortOrder
     *
     * @param string $sortOrder
     */
    public function setSortOrder(string $sortOrder)
    {
        $this->sortOrder = $sortOrder;
    }

    /**
     * Sets Value
     *
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->value = $value;
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
