<?php

namespace BestIt\CommercetoolsODM\Entity;

use BestIt\CommercetoolsODM\Mapping\Annotations as Commercetools;

/**
 * Class ZoneRate
 *
 * @package BestIt\CommercetoolsODM\Entity
 */
class ZoneRate
{
    /**
     * The array does not contain two shipping rates with the same currency.
     * @Commercetools\Field(type="array")
     * @Commercetools\ShippingRates
     * @var array
     */
    private $shippingRates;

    /**
     * @Commercetools\Field(type="") TODO Reference
     * @Commercetools\Zone
     * @var
     */
    private $zone;

    /**
     * gets ShippingRates
     *
     * @return array
     */
    public function getShippingRates(): array
    {
        return $this->shippingRates;
    }

    /**
     * gets Zone
     *
     * @return mixed
     */
    public function getZone()
    {
        return $this->zone;
    }

    /**
     * Sets ShippingRates
     *
     * @param array $shippingRates
     */
    public function setShippingRates(array $shippingRates)
    {
        $this->shippingRates = $shippingRates;
    }

    /**
     * Sets Zone
     *
     * @param mixed $zone
     */
    public function setZone($zone)
    {
        $this->zone = $zone;
    }
}
