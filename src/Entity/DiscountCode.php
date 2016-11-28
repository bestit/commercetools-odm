<?php

namespace BestIt\CommercetoolsODM\Entity;

use BestIt\CommercetoolsODM\Mapping\Annotations as Commercetools;

/**
 * Entity for Discount Codes.
 * Discount codes can be added to a cart to enable certain cart discounts.
 * With discount codes it is possible to give specific cart discounts to an eligible amount of users.
 * They are defined by a string value which can be added to a cart so that
 * specific cart discounts can be applied to the cart.
 * @Commercetools\DraftClass("Commercetools\Core\Model\DiscountCode\DiscountCodeDraft")
 * @Commercetools\Entity(requestMap=@Commercetools\RequestMap(
 *     defaultNamespace="Commercetools\Core\Request\DiscountCodes",
 *     findById="DiscountCodeByIdGetRequest",
 *     query="DiscountCodeQueryRequest",
 *     create="DiscountCodeCreateRequest",
 *     update="DiscountCodeUpdateRequest",
 *     delete="DiscountCodeDeleteRequest"
 * ))
 * @Commercetools\Repository("BestIt\CommercetoolsODM\Model\DiscountCodeRepository")
 * @package BestIt\CommercetoolsODM
 * @subpackage Entity
 * @version $id$
 */
class DiscountCode
{
    /**
     * The referenced matching cart discounts can be applied to the cart once the DiscountCode is added.
     * @Commercetools\Field(type="array")
     * @Commercetools\CartDiscounts
     * @var array
     */
    private $cartDiscounts;

    /**
     * The discount code can only be applied to carts that match this predicate.
     * @Commercetools\Field(type="") TODO CartDiscountPredicate
     * @Commercetools\CartDiscounts
     * @var
     */
    private $cartPredicate;

    /**
     * Unique identifier of this discount code.
     * This value is added to the cart to enable the related cart discounts in the cart.
     * @Commercetools\Field(type="string")
     * @Commercetools\Code
     * @var string
     */
    private $code;

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
     * The unique ID of the discount code.
     * @Commercetools\Field(type="string")
     * @Commercetools\Id
     * @var string
     */
    private $id;

    /**
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
     * The discount code can only be applied maxApplications times total.
     * @Commercetools\Field(type="int")
     * @Commercetools\MaxApplications
     * @var int
     */
    private $maxApplications;

    /**
     * The discount code can only be applied maxApplicationsPerCustomer times per customer.
     * @Commercetools\Field(type="int")
     * @Commercetools\MaxApplicationsPerCustomer
     * @var int
     */
    private $maxApplicationsPerCustomer;

    /**
     * @Commercetools\Field(type="") TODO LocalizedString
     * @Commercetools\Name
     * @var
     */
    private $name;

    /**
     * The platform will generate this array from the cartPredicate.
     * It contains the references of all the resources that are addressed in the predicate.
     * @Commercetools\Field(type="array")
     * @Commercetools\References
     * @var array
     */
    private $references;

    /**
     * @Commercetools\Field(type="int")
     * @Commercetools\Version
     * @var int
     */
    private $version;

    /**
     * gets CartDiscounts
     *
     * @return array
     */
    public function getCartDiscounts(): array
    {
        return $this->cartDiscounts;
    }

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
     * gets Code
     *
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
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
     * gets MaxApplications
     *
     * @return int
     */
    public function getMaxApplications(): int
    {
        return $this->maxApplications;
    }

    /**
     * gets MaxApplicationsPerCustomer
     *
     * @return int
     */
    public function getMaxApplicationsPerCustomer(): int
    {
        return $this->maxApplicationsPerCustomer;
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
     * Sets CartDiscounts
     *
     * @param array $cartDiscounts
     */
    public function setCartDiscounts(array $cartDiscounts)
    {
        $this->cartDiscounts = $cartDiscounts;
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
     * Sets Code
     *
     * @param string $code
     */
    public function setCode(string $code)
    {
        $this->code = $code;
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
     * Sets MaxApplications
     *
     * @param int $maxApplications
     */
    public function setMaxApplications(int $maxApplications)
    {
        $this->maxApplications = $maxApplications;
    }

    /**
     * Sets MaxApplicationsPerCustomer
     *
     * @param int $maxApplicationsPerCustomer
     */
    public function setMaxApplicationsPerCustomer(int $maxApplicationsPerCustomer)
    {
        $this->maxApplicationsPerCustomer = $maxApplicationsPerCustomer;
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
     * Sets Version
     *
     * @param int $version
     */
    public function setVersion(int $version)
    {
        $this->version = $version;
    }
}
