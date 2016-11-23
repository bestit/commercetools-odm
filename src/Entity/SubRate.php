<?php
namespace BestIt\CommercetoolsODM\Entity;

use BestIt\CommercetoolsODM\Mapping\Annotations as Commercetools;

class SubRate
{
    private $name = '';
    private $amount = '';
    private $includedInPrice = '';
    private $country = '';
    private $state = '';
    private $subRates = '';

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return SubRate
     */
    public function setName(string $name): SubRate
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getAmount(): string
    {
        return $this->amount;
    }

    /**
     * @param string $amount
     * @return SubRate
     */
    public function setAmount(string $amount): SubRate
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * @return string
     */
    public function getIncludedInPrice(): string
    {
        return $this->includedInPrice;
    }

    /**
     * @param string $includedInPrice
     * @return SubRate
     */
    public function setIncludedInPrice(string $includedInPrice): SubRate
    {
        $this->includedInPrice = $includedInPrice;
        return $this;
    }

    /**
     * @return string
     */
    public function getCountry(): string
    {
        return $this->country;
    }

    /**
     * @param string $country
     * @return SubRate
     */
    public function setCountry(string $country): SubRate
    {
        $this->country = $country;
        return $this;
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
     * @return SubRate
     */
    public function setState(string $state): SubRate
    {
        $this->state = $state;
        return $this;
    }

    /**
     * @return string
     */
    public function getSubRates(): string
    {
        return $this->subRates;
    }

    /**
     * @param string $subRates
     * @return SubRate
     */
    public function setSubRates(string $subRates): SubRate
    {
        $this->subRates = $subRates;
        return $this;
    }

}