<?php
namespace BestIt\CommercetoolsODM\Entity;

use BestIt\CommercetoolsODM\Mapping\Annotations as Commercetools;

/**
 * Class TaxRateDraft
 * @package BestIt\CommercetoolsODM\Entity
 */
class TaxRateDraft

{

    /**
     * @var string
     */
    private $name = '';
    /**
     * @var string
     */
    private $amount = '';
    /**
     * @var string
     */
    private $includedInPrice = '';
    /**
     * @var string
     */
    private $country = '';
    /**
     * @var string
     */
    private $state = '';
    /**
     * @var string
     */
    private $subRates = '';


    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getAmount(): string
    {
        return $this->amount;
    }

    /**
     * @return string
     */
    public function getIncludedInPrice(): string
    {
        return $this->includedInPrice;
    }

    /**
     * @return string
     */
    public function getCountry(): string
    {
        return $this->country;
    }

    /**
     * @return string
     */
    public function getState(): string
    {
        return $this->state;
    }

    /**
     * @return string
     */
    public function getSubRates(): string
    {
        return $this->subRates;
    }


    /**
     * @param string $name
     * @return TaxRateDraft
     */
    public function setName(string $name): TaxRateDraft
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param string $amount
     * @return TaxRateDraft
     */
    public function setAmount(string $amount): TaxRateDraft
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @param string $includedInPrice
     * @return TaxRateDraft
     */
    public function setIncludedInPrice(string $includedInPrice): TaxRateDraft
    {
        $this->includedInPrice = $includedInPrice;

        return $this;
    }

    /**
     * @param string $country
     * @return TaxRateDraft
     */
    public function setCountry(string $country): TaxRateDraft
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @param string $state
     * @return TaxRateDraft
     */
    public function setState(string $state): TaxRateDraft
    {
        $this->state = $state;

        return $this;
    }

    /**
     * @param string $subRates
     * @return TaxRateDraft
     */
    public function setSubRates(string $subRates): TaxRateDraft
    {
        $this->subRates = $subRates;

        return $this;
    }

}