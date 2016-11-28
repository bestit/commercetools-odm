<?php
namespace BestIt\CommercetoolsODM\Entity;

use BestIt\CommercetoolsODM\Mapping\Annotations as Commercetools;

/**
 * Entity for Product Types.
 * @author lange <lange@bestit-online.de>
 * @Commercetools\DraftClass("Commercetools\Core\Model\Review\ReviewDraft")
 * @Commercetools\Entity(requestMap=@Commercetools\RequestMap(
 *     defaultNamespace="Commercetools\Core\Request\Reviews",
 *     findById="ReviewByIdGetRequest",
 *     findByKey="ReviewByKeyGetRequest",
 *     query="ReviewQueryRequest",
 *     create="ReviewCreateRequest",
 *     update="ReviewUpdateRequest",
 *     deleteById="ReviewDeleteRequest",
 *     deleteByKey="ReviewDeleteByKeyRequest"
 * ))
 * @Commercetools\Repository("BestIt\CommercetoolsODM\Model\ReviewRepository")
 * @package BestIt\CommercetoolsODM
 * @subpackage Entity
 * @version $id$
 */
class Review
{
    /**
     * The AuthorName for the type.
     * @Commercetools\Field(type="string")
     * @Commercetools\AuthorName
     * @var string
     */
    private $authorName = '';

    /**
     * The CreatedAt fore the type.
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
    private $custom;

    /**
     * The customer who created the review.
     * @Commercetools\Field(type="") TODO Reference
     * @Commercetools\Customer
     * @var
     */
    private $customer;

    /**
     * The unique ID of the review.
     * @Commercetools\Field(type="string")
     * @Commercetools\Id
     * @var string
     */
    private $id = '';

    /**
     * Indicates if this review is taken into account in the ratings statistics of the target.
     * @Commercetools\Field(type="boolean")
     * @Commercetools\IncludeInStatistics
     * @var boolean
     */
    private $includedInStatistics = false;

    /**
     * The Key for the type.
     * @Commercetools\Field(type="string")
     * @Commercetools\Key
     * @var string
     */
    private $key = '';

    /**
     * The LastModifiedAt for the type.
     * @Commercetools\Field(type="datetime")
     * @Commercetools\LastModifiedAt
     * @var \DateTime
     */
    private $lastModifiedAt;

    /**
     * The Locale for the type.
     * @Commercetools\Field(type="string")
     * @Commercetools\Locale
     * @var string
     */
    private $locale = '';

    /**
     * Number between -100 and 100 included.
     * @Commercetools\Field(type="int")
     * @Commercetools\Rating
     * @var int
     */
    private $rating = 0;

    /**
     * The State for the type.
     * @Commercetools\Field(type="") TODO Reference
     * @Commercetools\State
     * @var
     */
    private $state = '';

    /**
     * Identifies the target of the review. Can be a Product or a Channel.
     * @Commercetools\Field(type="") TODO Reference
     * @Commercetools\Target
     * @var
     */
    private $target;

    /**
     * The Text for the type
     * @Commercetools\Field(type="string")
     * @Commercetools\Text
     * @var string
     */
    private $text = '';

    /**
     * The Title for the type.
     * @Commercetools\Field(type="string")
     * @Commercetools\Title
     * @var string
     */
    private $title = '';

    /**
     * User-specific unique identifier for the review.
     * @Commercetools\Field(type="")
     * @Commercetools\UniquenessValue
     * @var
     */
    private $uniquenessValue = '';

    /**
     * The current version of the review.
     * @Commercetools\Field(type="int")
     * @Commercetools\Version
     * @var int
     */
    private $version = 0;

    /**
     * gets AuthorName
     *
     * @return string
     */
    public function getAuthorName(): string
    {
        return $this->authorName;
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
     * gets Custom
     *
     * @return mixed
     */
    public function getCustom()
    {
        return $this->custom;
    }

    /**
     * gets Customer
     *
     * @return mixed
     */
    public function getCustomer()
    {
        return $this->customer;
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
     * gets Key
     *
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
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
     * gets Locale
     *
     * @return string
     */
    public function getLocale(): string
    {
        return $this->locale;
    }

    /**
     * gets Rating
     *
     * @return int
     */
    public function getRating(): int
    {
        return $this->rating;
    }

    /**
     * gets State
     *
     * @return mixed
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * gets Target
     *
     * @return mixed
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * gets Text
     *
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * gets Title
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * gets UniquenessValue
     *
     * @return mixed
     */
    public function getUniquenessValue()
    {
        return $this->uniquenessValue;
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
     * iss IncludedInStatistics
     *
     * @return boolean
     */
    public function isIncludedInStatistics(): bool
    {
        return $this->includedInStatistics;
    }

    /**
     * Sets AuthorName
     *
     * @param string $authorName
     */
    public function setAuthorName(string $authorName)
    {
        $this->authorName = $authorName;
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
     * Sets Custom
     *
     * @param mixed $custom
     */
    public function setCustom($custom)
    {
        $this->custom = $custom;
    }

    /**
     * Sets Customer
     *
     * @param mixed $customer
     */
    public function setCustomer($customer)
    {
        $this->customer = $customer;
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
     * Sets IncludedInStatistics
     *
     * @param boolean $includedInStatistics
     */
    public function setIncludedInStatistics(bool $includedInStatistics)
    {
        $this->includedInStatistics = $includedInStatistics;
    }

    /**
     * Sets Key
     *
     * @param string $key
     */
    public function setKey(string $key)
    {
        $this->key = $key;
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
     * Sets Locale
     *
     * @param string $locale
     */
    public function setLocale(string $locale)
    {
        $this->locale = $locale;
    }

    /**
     * Sets Rating
     *
     * @param int $rating
     */
    public function setRating(int $rating)
    {
        $this->rating = $rating;
    }

    /**
     * Sets State
     *
     * @param mixed $state
     */
    public function setState($state)
    {
        $this->state = $state;
    }

    /**
     * Sets Target
     *
     * @param mixed $target
     */
    public function setTarget($target)
    {
        $this->target = $target;
    }

    /**
     * Sets Text
     *
     * @param string $text
     */
    public function setText(string $text)
    {
        $this->text = $text;
    }

    /**
     * Sets Title
     *
     * @param string $title
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    /**
     * Sets UniquenessValue
     *
     * @param mixed $uniquenessValue
     */
    public function setUniquenessValue($uniquenessValue)
    {
        $this->uniquenessValue = $uniquenessValue;
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
