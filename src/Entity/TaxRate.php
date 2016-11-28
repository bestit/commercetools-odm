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
     * Number Percentage in the range of [0..1]. The sum of the amounts of all subRates, if there are any.
     * @Commercetools\Field(type="int")
     * @Commercetools\Amount
     * @var int
     */
    private $amount = 0;

    /**
     * A two-digit country code as per ↗ ISO 3166-1 alpha-2 .
     * @Commercetools\Field(type="string")
     * @Commercetools\Country
     * @var string
     */
    private $country = '';

    /**
     * The id is always set if the tax rate is part of a TaxCategory.
     * The external tax rates in a Cart do not contain an id.
     * @Commercetools\Field(type="string")
     * @Commercetools\Id
     * @var string
     */
    private $id = '';

    /**
     * The IncludePrice for the type
     * @Commercetools\Field(type="boolean")
     * @Commercetools\IncludePrice
     * @var boolean
     */
    private $includedInPrice = false;

    /**
     * The Name of the type.
     * @Commercetools\Field(type="string")
     * @Commercetools\Name
     * @var string
     */
    private $name = '';

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
    private $subRates = [];

    /**
     * gets Amount
     *
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * gets Country
     *
     * @return string
     */
    public function getCountry(): string
    {
        return $this->country;
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
     * gets Name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * gets State
     *
     * @return string
     */
    public function getState(): string
    {
        return $this->state;
    }

    /**
     * gets SubRates
     *
     * @return array
     */
    public function getSubRates(): array
    {
        return $this->subRates;
    }

    /**
     * iss IncludedInPrice
     *
     * @return boolean
     */
    public function isIncludedInPrice(): bool
    {
        return $this->includedInPrice;
    }

    /**
     * Sets Amount
     *
     * @param int $amount
     */
    public function setAmount(int $amount)
    {
        $this->amount = $amount;
    }

    /**
     * Sets Country
     *
     * @param string $country
     */
    public function setCountry(string $country)
    {
        $this->country = $country;
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
     * Sets IncludedInPrice
     *
     * @param boolean $includedInPrice
     */
    public function setIncludedInPrice(bool $includedInPrice)
    {
        $this->includedInPrice = $includedInPrice;
    }

    /**
     * Sets Name
     *
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * Sets State
     *
     * @param string $state
     */
    public function setState(string $state)
    {
        $this->state = $state;
    }

    /**
     * Sets SubRates
     *
     * @param array $subRates
     */
    public function setSubRates(array $subRates)
    {
        $this->subRates = $subRates;
    }
}