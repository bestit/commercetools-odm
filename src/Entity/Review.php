<?php
namespace BestIt\CommercetoolsODM\Entity;

use BestIt\CommercetoolsODM\Mapping\Annotations as Commercetools;

/**
 * Class Review
 * @package BestIt\CommercetoolsODM\Entity
 */class Review

{
private $id = '';
private $version = '';
private $createdAt = '';
private $lastModifiedAt = '';
private $key = '';
private $uniquenessValue = '';
private $locale = '';
private $authorName = '';
private $title = '';
private $text = '';
private $target = '';
private $rating = '';
private $state = '';
private $includedInStatistics = '';
private $customer = '';
private $custom = '';


    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * @return string
     */
    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    /**
     * @return string
     */
    public function getLastModifiedAt(): string
    {
        return $this->lastModifiedAt;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @return string
     */
    public function getUniquenessValue(): string
    {
        return $this->uniquenessValue;
    }

    /**
     * @return string
     */
    public function getLocale(): string
    {
        return $this->locale;
    }

    /**
     * @return string
     */
    public function getAuthorName(): string
    {
        return $this->authorName;
    }

    /**
     * @param string $id
     * @return Review
     */
    public function setId(string $id): Review
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @param string $version
     * @return Review
     */
    public function setVersion(string $version): Review
    {
        $this->version = $version;

        return $this;
    }

    /**
     * @param string $createdAt
     * @return Review
     */
    public function setCreatedAt(string $createdAt): Review
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @param string $lastModifiedAt
     * @return Review
     */
    public function setLastModifiedAt(string $lastModifiedAt): Review
    {
        $this->lastModifiedAt = $lastModifiedAt;

        return $this;
    }

    /**
     * @param string $key
     * @return Review
     */
    public function setKey(string $key): Review
    {
        $this->key = $key;

        return $this;
    }

    /**
     * @param string $uniquenessValue
     * @return Review
     */
    public function setUniquenessValue(string $uniquenessValue): Review
    {
        $this->uniquenessValue = $uniquenessValue;

        return $this;
    }

    /**
     * @param string $locale
     * @return Review
     */
    public function setLocale(string $locale): Review
    {
        $this->locale    = $locale;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText(string $text)
    {
        $this->text = $text;
    }

    /**
     * @return string
     */
    public function getTarget(): string
    {
        return $this->target;
    }

    /**
     * @param string $target
     */
    public function setTarget(string $target)
    {
        $this->target = $target;
    }

    /**
     * @return string
     */
    public function getRating(): string
    {
        return $this->rating;
    }

    /**
     * @param string $rating
     */
    public function setRating(string $rating)
    {
        $this->rating = $rating;
    }

    /**
     * @return string
     */
    public function getState(): string
    {
        return $this->state;
    }

    /**
     * @param string $state
     */
    public function setState(string $state)
    {
        $this->state = $state;
    }

    /**
     * @return string
     */
    public function getIncludedInStatistics(): string
    {
        return $this->includedInStatistics;
    }

    /**
     * @param string $includedInStatistics
     */
    public function setIncludedInStatistics(string $includedInStatistics)
    {
        $this->includedInStatistics = $includedInStatistics;
    }

    /**
     * @return string
     */
    public function getCustomer(): string
    {
        return $this->customer;
    }

    /**
     * @param string $customer
     */
    public function setCustomer(string $customer)
    {
        $this->customer = $customer;
    }

    /**
     * @return string
     */
    public function getCustom(): string
    {
        return $this->custom;
    }

    /**
     * @param string $custom
     */
    public function setCustom(string $custom)
    {
        $this->custom = $custom;
    }
}