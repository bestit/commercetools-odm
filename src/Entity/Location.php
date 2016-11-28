<?php

namespace BestIt\CommercetoolsODM\Entity;

use BestIt\CommercetoolsODM\Mapping\Annotations as Commercetools;

/**
 * Class Location
 * A geographical location representing a country with an optional state.
 *
 * @package BestIt\CommercetoolsODM\Entity
 */
class Location
{
    /**
     * @Commercetools\Field(type="string")
     * @Commercetools\Country
     * @var string
     */
    private $country;

    /**
     * @Commercetools\Field(type="string")
     * @Commercetools\State
     * @var string
     */
    private $state;

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
     * Sets Country
     *
     * @param string $country
     */
    public function setCountry(string $country)
    {
        $this->country = $country;
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
     * Sets State
     *
     * @param string $state
     */
    public function setState(string $state)
    {
        $this->state = $state;
    }
}
