<?php
namespace BestIt\CommercetoolsODM\Entity;

use BestIt\CommercetoolsODM\Mapping\Annotations as Commercetools;

/**
 * Class TaxRate
 * @package BestIt\CommercetoolsODM\Entity
 */
class TaxRate

{

    /**     TODO @var!!
     * The id is always set if the tax rate is part of a TaxCategory. The external tax rates in a Cart do not contain an id.
     * @Commercetools\Field(type="string")
     * @Commercetools\Id
     * @var string
     */
    private $id = '';
    /**
     * The Name of the type.
     * @Commercetools\Field(type="string")
     * @Commercetools\Name
     * @var string
     */
    private $name = '';
    /**
     * Number Percentage in the range of [0..1]. The sum of the amounts of all subRates, if there are any.
     * @Commercetools\Field(type="") TODO
     * @Commercetools\Amount
     * @var
     */
    private $amount = '';
    /**
     * The IncludePrice for the type
     * @Commercetools\Field(type="boolean")
     * @Commercetools\IncludePrice
     * @var boolean
     */
    private $includedInPrice = '';
    /**
     * A two-digit country code as per ↗ ISO 3166-1 alpha-2 .
     * @Commercetools\Field(type="string")
     * @Commercetools\Country
     * @var string
     */
    private $country = '';
    /**
     * The state in the country.
     * @Commercetools\Field(type="string")
     * @Commercetools\State
     * @var string
     */
    private $state = '';
    /**
     * For countries (e.g. the US) where the total tax is a combination of multiple taxes (e.g. state and local taxes).
     * @Commercetools\Field(type="array")
     * @Commercetools\SubRates
     * @var array
     */
    private $subRates = '';


    /**
     * Returns the String for the type.
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
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
     * Returns the Amount for the type.
     * @return string
     */
    public function getAmount(): string
    {
        return $this->amount;
    }

    /**
     * Returns the IncludePrice for the type.
     * @return string
     */
    public function getIncludedInPrice(): string
    {
        return $this->includedInPrice;
    }

    /**
     * Return the Country for the type.
     * @return string
     */
    public function getCountry(): string
    {
        return $this->country;
    }

    /**
     * Returns the State for the type.
     * @return string
     */
    public function getState(): string
    {
        return $this->state;
    }

    /**
     * Returns the SubRates for the type.
     * @return string
     */
    public function getSubRates(): string
    {
        return $this->subRates;
    }


    /**
     * Sets the String for the type.
     * @param string $id
     * @return TaxRate
     */
    public function setId(string $id): TaxRate
    {
        $this->id = $id;

        return $this;
    }

    /**
     *  Sets the Name for the type.
     * @param string $name
     * @return TaxRate
     */
    public function setName(string $name): TaxRate
    {
        $this->name = $name;

        return $this;
    }

    /**Sets the Amount for the type.
     * @param string $amount
     * @return TaxRate
     */
    public function setAmount(string $amount): TaxRate
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Sets the IncludePrice for the type.
     * @param string $includedInPrice
     * @return TaxRate
     */
    public function setIncludedInPrice(string $includedInPrice): TaxRate
    {
        $this->includedInPrice = $includedInPrice;

        return $this;
    }

    /**
     * Sets the Country for the type.
     * @param string $country
     * @return TaxRate
     */
    public function setCountry(string $country): TaxRate
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Sets the State for the type.
     * @param string $state
     * @return TaxRate
     */
    public function setState(string $state): TaxRate
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Sets the SubRates
     * @param string $subRates
     * @return TaxRate
     */
    public function setSubRates(string $subRates): TaxRate
    {
        $this->subRates = $subRates;

        return $this;
    }



}