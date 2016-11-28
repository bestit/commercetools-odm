<?php
namespace BestIt\CommercetoolsODM\Entity;

use BestIt\CommercetoolsODM\Mapping\Annotations as Commercetools;

/**
 * Class Review
 * @package BestIt\CommercetoolsODM\Entity
 */class Review

{
    /**     TODO @var!!
     * The unique ID of the review.
     * @Commercetools\Field(type="string")
     * @Commercetools\Id
     * @var string
     */
    private $id = '';
    /**
     * The current version of the review.
     * @Commercetools\Field(type="int")
     * @Commercetools\Version
     * @var int
     */
    private $version = '';
    /**
     * The CreatedAt fore the type.
     * @Commercetools\Field(type="datetime")
     * @Commercetools\CreatedAt
     * @var datetime
     */
    private $createdAt = '';
    /**
     * The LastModifiedAt for the type.
     * @Commercetools\Field(type="datetime")
     * @Commercetools\LastModifiedAt
     * @var datetime
     */
    private $lastModifiedAt = '';
    /**
     * The Key for the type.
     * @Commercetools\Field(type="string")
     * @Commercetools\Key
     * @var string
     */
    private $key = '';
    /**
     * User-specific unique identifier for the review.
     * @Commercetools\Field(type="") TODO
     * @Commercetools\uniquenessValue
     * @var
     */
    private $uniquenessValue = '';
    /**
     * The Locale for the type.
     * @Commercetools\Field(type="string")
     * @Commercetools\Locale
     * @var string
     */
    private $locale = '';
    /**
     * The AuthorName for the type.
     * @Commercetools\Field(type="string")
     * @Commercetools\AuthorName
     * @var string
     */
    private $authorName = '';
    /**
     * The Title for the type.
     * @Commercetools\Field(type="string")
     * @Commercetools\Title
     * @var string
     */
    private $title = '';
    /**
     * The Text for the type
     * @Commercetools\Field(type="string")
     * @Commercetools\Text
     * @var string
     */
    private $text = '';
    /**
     * Identifies the target of the review. Can be a Product or a Channel.
     * @Commercetools\Field(type="") TODO
     * @Commercetools\Target
     * @var
     */
    private $target = '';
    /**
     * Number between -100 and 100 included.
     * @Commercetools\Field(type="int")
     * @Commercetools\Rating
     * @var int
     */
    private $rating = '';
    /**
     * The State for the type.
     * @Commercetools\Field(type="") TODO
     * @Commercetools\State
     * @var
     */
    private $state = '';
    /**
     * Indicates if this review is taken into account in the ratings statistics of the target.
     * @Commercetools\Field(type="boolean")
     * @Commercetools\IncludeInStatistics
     * @var boolean
     */
    private $includedInStatistics = '';
    /**
     * The customer who created the review.
     * @Commercetools\Field(type="") TODO
     * @Commercetools\Customer
     * @var
     */
    private $customer = '';
    /**
     *
     * The Custom for the type.
     * @Commercetools\Field(type="") TODO
     * @Commercetools\Custom
     * @var
     */
    private $custom = '';


    /**
     * Returns the Id for the type.
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
     * Returns the LastModiefiedAt for the type.
     * @return string
     */
    public function getLastModifiedAt(): string
    {
        return $this->lastModifiedAt;
    }

    /**
     * Returns the Key for the type.
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * Returns the UniqueenessValue for the type.
     * @return string
     */
    public function getUniquenessValue(): string
    {
        return $this->uniquenessValue;
    }

    /**
     * Returns the Locale for the type.
     * @return string
     */
    public function getLocale(): string
    {
        return $this->locale;
    }

    /** Returns the AuthorName for the type.
     * @return string
     */
    public function getAuthorName(): string
    {
        return $this->authorName;
    }

    /**
     * Sets the Id for the type.
     * @param string $id
     * @return Review
     */
    public function setId(string $id): Review
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Sets the Version for the type.
     * @param string $version
     * @return Review
     */
    public function setVersion(string $version): Review
    {
        $this->version = $version;

        return $this;
    }

    /**
     * Sets the CreatedAt for the type.
     * @param string $createdAt
     * @return Review
     */
    public function setCreatedAt(string $createdAt): Review
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Sets the LastModifedAt for the type.
     * @param string $lastModifiedAt
     * @return Review
     */
    public function setLastModifiedAt(string $lastModifiedAt): Review
    {
        $this->lastModifiedAt = $lastModifiedAt;

        return $this;
    }

    /**
     * Sets the Key for the type.
     * @param string $key
     * @return Review
     */
    public function setKey(string $key): Review
    {
        $this->key = $key;

        return $this;
    }

    /**
     * Sets the UniqueensValue for the type.
     * @param string $uniquenessValue
     * @return Review
     */
    public function setUniquenessValue(string $uniquenessValue): Review
    {
        $this->uniquenessValue = $uniquenessValue;

        return $this;
    }

    /**
     * Sets the Locale for the type.
     * @param string $locale
     * @return Review
     */
    public function setLocale(string $locale): Review
    {
        $this->locale = $locale;

        return $this;
    }


    /**
     * Sets the AuthorName for the type.
     * @param string $authorName
     * @return Review
     */
    public function setAuthorName(string $authorName): Review
    {
        $this->authorName = $authorName;

        return $this;
    }

    /**
     * Sets the Title for the type.
     * @param string $title
     * @return Review
     */
    public function setTitle(string $title): Review
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Sets the Text for the type.
     * @param string $text
     * @return Review
     */
    public function setText(string $text): Review
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Sets the Target for the type.
     * @param string $target
     * @return Review
     */
    public function setTarget(string $target): Review
    {
        $this->target = $target;

        return $this;
    }

    /**
     * Sets the Rating for the type.
     * @param string $rating
     * @return Review
     */
    public function setRating(string $rating): Review
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * Sets the State for the type.
     * @param string $state
     * @return Review
     */
    public function setState(string $state): Review
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Sets the IncludeInStatistics for the type.
     * @param string $includedInStatistics
     * @return Review
     */
    public function setIncludedInStatistics(string $includedInStatistics): Review
    {
        $this->includedInStatistics = $includedInStatistics;

        return $this;
    }

    /**
     * Sets the Customer for the type.
     * @param string $customer
     * @return Review
     */
    public function setCustomer(string $customer): Review
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * Sets the Custom for the type.
     * @param string $custom
     * @return Review
     */
    public function setCustom(string $custom): Review
    {
        $this->custom = $custom;

        return $this;
    }
}
