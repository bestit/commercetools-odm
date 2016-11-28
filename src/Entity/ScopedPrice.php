<?php
namespace BestIt\CommercetoolsODM\Entity;

use BestIt\CommercetoolsODM\Mapping\Annotations as Commercetools;

class ScopedPrice
{
    /**
     * A reference to a channel.
     * @Commercetools\Field(type="") TODO Reference
     * @Commercetools\Channel
     * @var
     */
    private $channel;

    /**
     * A two-digit country code as per ↗ ISO 3166-1 alpha-2 .
     * @Commercetools\Field(type="string")
     * @Commercetools\Country
     * @var string
     */
    private $country = '';

    /**
     * The CurrentValue for the type.
     * @Commercetools\Field(type="") TODO Money
     * @Commercetools\CurrentValue
     * @var
     */
    private $currentValue;

    /**
     * The Custom for the type.
     * @Commercetools\Field(type="") TODO CustomFields
     * @Commercetools\Custom
     * @var
     */
    private $custom;

    /**
     * A reference to a customer group.
     * @Commercetools\Field(type="") TODO Reference
     * @Commercetools\CustomerGroup
     * @var
     */
    private $customerGroup;

    /**
     * Is set if a matching ProductDiscount exists. If set, the Cart will use the discounted value for the cart price calculation.
     * @Commercetools\Field(type="") TODO DiscountedPrice
     * @Commercetools\Discounted
     * @var
     */
    private $discounted;

    /**
     * The unique ID of this price.
     * @Commercetools\Field(type="string")
     * @Commercetools\Id
     * @var string
     */
    private $id = '';

    /**
     * Date from which the price is valid.
     * @Commercetools\Field(type="datetime")
     * @Commercetools\ValidForm
     * @var \DateTime
     */
    private $validFrom;

    /**
     * Date until which the price is valid.
     * @Commercetools\Field(type="datetime")
     * @Commercetools\ValidUntil
     * @var \DateTime
     */
    private $validUntil;

    /**
     * The orifinal price value.
     * @Commercetools\Field(type="") TODO Money
     * @Commercetools\Value
     * @var
     */
    private $value;

    /**
     * gets Channel
     *
     * @return mixed
     */
    public function getChannel()
    {
        return $this->channel;
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
     * gets CurrentValue
     *
     * @return mixed
     */
    public function getCurrentValue()
    {
        return $this->currentValue;
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
     * gets CustomerGroup
     *
     * @return mixed
     */
    public function getCustomerGroup()
    {
        return $this->customerGroup;
    }

    /**
     * gets Discounted
     *
     * @return mixed
     */
    public function getDiscounted()
    {
        return $this->discounted;
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
     * gets ValidFrom
     *
     * @return \DateTime
     */
    public function getValidFrom(): \DateTime
    {
        return $this->validFrom;
    }

    /**
     * gets ValidUntil
     *
     * @return \DateTime
     */
    public function getValidUntil(): \DateTime
    {
        return $this->validUntil;
    }

    /**
     * gets Value
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Sets Channel
     *
     * @param mixed $channel
     */
    public function setChannel($channel)
    {
        $this->channel = $channel;
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
     * Sets CurrentValue
     *
     * @param mixed $currentValue
     */
    public function setCurrentValue($currentValue)
    {
        $this->currentValue = $currentValue;
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
     * Sets CustomerGroup
     *
     * @param mixed $customerGroup
     */
    public function setCustomerGroup($customerGroup)
    {
        $this->customerGroup = $customerGroup;
    }

    /**
     * Sets Discounted
     *
     * @param mixed $discounted
     */
    public function setDiscounted($discounted)
    {
        $this->discounted = $discounted;
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
     * Sets ValidFrom
     *
     * @param \DateTime $validFrom
     */
    public function setValidFrom(\DateTime $validFrom)
    {
        $this->validFrom = $validFrom;
    }

    /**
     * Sets ValidUntil
     *
     * @param \DateTime $validUntil
     */
    public function setValidUntil(\DateTime $validUntil)
    {
        $this->validUntil = $validUntil;
    }

    /**
     * Sets Value
     *
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }
}