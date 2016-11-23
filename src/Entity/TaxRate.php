<?php
namespace BestIt\CommercetoolsODM\Entity;

use BestIt\CommercetoolsODM\Mapping\Annotations as Commercetools;

/**
 * Class TaxRate
 * @package BestIt\CommercetoolsODM\Entity
 */
class TaxRate

{

    /**
     * @var string
     */
    private $id = '';
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
    public function getId(): string
    {
        return $this->id;
    }

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
     * @param string $id
     * @return TaxRate
     */
    public function setId(string $id): TaxRate
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @param string $name
     * @return TaxRate
     */
    public function setName(string $name): TaxRate
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param string $amount
     * @return TaxRate
     */
    public function setAmount(string $amount): TaxRate
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @param string $includedInPrice
     * @return TaxRate
     */
    public function setIncludedInPrice(string $includedInPrice): TaxRate
    {
        $this->includedInPrice = $includedInPrice;

        return $this;
    }

    /**
     * @param string $country
     * @return TaxRate
     */
    public function setCountry(string $country): TaxRate
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @param string $state
     * @return TaxRate
     */
    public function setState(string $state): TaxRate
    {
        $this->state = $state;

        return $this;
    }

    /**
     * @param string $subRates
     * @return TaxRate
     */
    public function setSubRates(string $subRates): TaxRate
    {
        $this->subRates = $subRates;

        return $this;
    }



}