<?php
namespace BestIt\CommercetoolsODM\Entity;

use BestIt\CommercetoolsODM\Mapping\Annotations as Commercetools;

class Price
{
    /**     TODO @var!!
     * A reference to a channel.
     * @Commercetools\Field(type="") TODO
     * @Commercetools\Channel
     * @var
     */
    private $channel = '';
    /**
     * A two-digit country code as per ↗ ISO 3166-1 alpha-2 .
     * @Commercetools\Field(type="string")
     * @Commercetools\Country
     * @var string
     */
    private $country = '';
    /**
     * The Costum for the type.
     * @Commercetools\Field(type="") TODO
     * @Commercetools\Costum
     * @var
     */
    private $custom = '';
    /**
     * A reference to a customer group.
     * @Commercetools\Field(type="") TODO
     * @Commercetools\CustomerGroup
     * @var
     */
    private $customerGroup = '';
    /**
     * The discount for the type.
     * @Commercetools\Field(type="") TODO
     * @Commercetools\discounted
     * @var
     */
    private $discounted = '';
    /**
     * The unique ID of this price.
     * @Commercetools\Field(type="int")
     * @Commercetools\Id
     * @var
     */
    private $id = '';
    /**
     * Date from which the price is valid.
     * @Commercetools\Field(type="datetime")
     * @Commercetools\ValidFrom
     * @var
     */
    private $validFrom = '';
    /**
     * Date until which the price is valid.
     * @Commercetools\Field(type="datetime")
     * @Commercetools\ValidUntil
     * @var
     */
    private $validUntil = '';
    /**
     * The Value for the type.
     * @Commercetools\Field(type="") TODO
     * @Commercetools\Value
     * @var
     *//**
     * @var string
     */
    private $value = '';


    /**
     * Returns the Channel for the type.
     * @return string
     */
    public function getChannel(): string
    {
        return $this->channel;
    }

    /**
     * Sets the Channel for the type.
     * @param string $channel
     */
    public function setChannel(string $channel)
    {
        $this->channel = $channel;
    }

    /**
     * Returns the Country for the type.
     * @return string
     */
    public function getCountry(): string
    {
        return $this->country;
    }

    /**
     * Sets the Country for the type.
     * @param string $country
     */
    public function setCountry(string $country)
    {
        $this->country = $country;
    }

    /** Returns the Custom for the type.
     * @return string
     */
    public function getCustom(): string
    {
        return $this->custom;
    }

    /**
     * Sets the Custom for the type.
     * @param string $custom
     */
    public function setCustom(string $custom)
    {
        $this->custom = $custom;
    }

    /**
     * Returns the CustomerGroup for the type.
     * @return string
     */
    public function getCustomerGroup(): string
    {
        return $this->customerGroup;
    }

    /**
     * Sets the CustomerGroup for the type.
     * @param string $customerGroup
     */
    public function setCustomerGroup(string $customerGroup)
    {
        $this->customerGroup = $customerGroup;
    }

    /**
     * Returns the Discounted for the type.
     * @return string
     */
    public function getDiscounted(): string
    {
        return $this->discounted;
    }

    /**
     * Sets the Disounted for the type.
     * @param string $discounted
     */
    public function setDiscounted(string $discounted)
    {
        $this->discounted = $discounted;
    }

    /**
     * Returns the Id for the type.
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Sets the Id for the type.
     * @param string $id
     */
    public function setId(string $id)
    {
        $this->id = $id;
    }

    /**
     * Returns the ValidForm for the type.
     * @return string
     */
    public function getValidFrom(): string
    {
        return $this->validFrom;
    }

    /**
     * Sets the ValidForm for the type.
     * @param string $validFrom
     */
    public function setValidFrom(string $validFrom)
    {
        $this->validFrom = $validFrom;
    }

    /**
     * Returns the ValidUntil for the type.
     * @return string
     */
    public function getValidUntil(): string
    {
        return $this->validUntil;
    }

    /**
     * Sets the ValidUntil for the type.
     * @param string $validUntil
     */
    public function setValidUntil(string $validUntil)
    {
        $this->validUntil = $validUntil;
    }

    /**
     * Returns the Value for the type.
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * Sets the Value for the type.
     * @param string $value
     */
    public function setValue(string $value)
    {
        $this->value = $value;
    }

}