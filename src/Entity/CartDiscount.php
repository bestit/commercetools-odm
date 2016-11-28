<?php

namespace BestIt\CommercetoolsODM\Entity;

use BestIt\CommercetoolsODM\Mapping\Annotations as Commercetools;

/**
 * Entity for Cart Discounts.
 * @Commercetools\DraftClass("Commercetools\Core\Model\CartDiscount\CartDiscountDraft")
 * @Commercetools\Entity(requestMap=@Commercetools\RequestMap(
 *     defaultNamespace="Commercetools\Core\Request\CartDiscounts",
 *     findById="CartDiscountByIdGetRequest",
 *     query="CartDiscountQueryRequest",
 *     create="CartDiscountCreateRequest",
 *     update="CartDiscountUpdateRequest",
 *     delete="CartDiscountDeleteRequest"
 * ))
 * @Commercetools\Repository("BestIt\CommercetoolsODM\Model\CartDiscountRepository")
 * @package BestIt\CommercetoolsODM
 * @subpackage Entity
 * @version $id$
 */
class CartDiscount
{
    /**
     * A valid CartDiscount predicate.
     * @Commercetools\Field(type="string")
     * @Commercetools\CartPredicate
     * @var string
     */
    private $cartPredicate;

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
     * The unique ID of the product discount.
     * @Commercetools\Field(type="string")
     * @Commercetools\Id
     * @var string
     */
    private $id;

    /**
     * Only active discounts can be applied to the cart.
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
     * The platform will generate this array from the predicate.
     * It contains the references of all the resources that are addressed in the predicate.
     * @Commercetools\Field(type="array")
     * @Commercetools\Country
     * @var array
     */
    private $references;

    /**
     * States whether the discount can only be used in a connection with a DiscountCode.
     * @Commercetools\Field(type="Boolean")
     * @Commercetools\RequiresDiscountCode
     * @var boolean
     */
    private $requiresDiscountCode;

    /**
     * The string must contain a number between 0 and 1. All matching cart discounts are applied to a cart in the
     * order defined by this field. A discount with greater sort order is prioritized higher than a discount with
     * lower sort order. The sort order is unambiguous among all cart discounts.
     * @Commercetools\Field(type="string")
     * @Commercetools\SortOrder
     * @var string
     */
    private $sortOrder;

    /**
     * @Commercetools\Field(type="") TODO CartDiscountTarget
     * @Commercetools\Target
     * @var
     */
    private $taget;

    /**
     * @Commercetools\Field(type="datetime")
     * @Commercetools\ValidFrom
     * @var \DateTime
     */
    private $validFrom;

    /**
     * @Commercetools\Field(type="datetime")
     * @Commercetools\ValidUntil
     * @var \DateTime
     */
    private $validUntil;

    /**
     * @Commercetools\Field(type="") TODO CartDiscountValue
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
     * gets CartPredicate
     *
     * @return mixed
     */
    public function getCartPredicate()
    {
        return $this->cartPredicate;
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
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * gets IsActive
     *
     * @return mixed
     */
    public function getIsActive()
    {
        return $this->isActive;
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
     * gets Taget
     *
     * @return mixed
     */
    public function getTaget()
    {
        return $this->taget;
    }

    /**
     * gets ValidFrom
     *
     * @return \DateTime
     */
    public function getValidFrom(): \DateTime
    {
        return $this->validFrom;
    }

    /**
     * gets ValidUntil
     *
     * @return \DateTime
     */
    public function getValidUntil(): \DateTime
    {
        return $this->validUntil;
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
     * @return mixed
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * iss RequiresDiscountCode
     *
     * @return boolean
     */
    public function isRequiresDiscountCode(): bool
    {
        return $this->requiresDiscountCode;
    }

    /**
     * Sets CartPredicate
     *
     * @param mixed $cartPredicate
     */
    public function setCartPredicate($cartPredicate)
    {
        $this->cartPredicate = $cartPredicate;
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
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Sets IsActive
     *
     * @param mixed $isActive
     */
    public function setIsActive($isActive)
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
     * Sets References
     *
     * @param array $references
     */
    public function setReferences(array $references)
    {
        $this->references = $references;
    }

    /**
     * Sets RequiresDiscountCode
     *
     * @param boolean $requiresDiscountCode
     */
    public function setRequiresDiscountCode(bool $requiresDiscountCode)
    {
        $this->requiresDiscountCode = $requiresDiscountCode;
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
     * Sets Taget
     *
     * @param mixed $taget
     */
    public function setTaget($taget)
    {
        $this->taget = $taget;
    }

    /**
     * Sets ValidFrom
     *
     * @param \DateTime $validFrom
     */
    public function setValidFrom(\DateTime $validFrom)
    {
        $this->validFrom = $validFrom;
    }

    /**
     * Sets ValidUntil
     *
     * @param \DateTime $validUntil
     */
    public function setValidUntil(\DateTime $validUntil)
    {
        $this->validUntil = $validUntil;
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
     * @param mixed $version
     */
    public function setVersion($version)
    {
        $this->version = $version;
    }
}
