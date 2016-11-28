<?php

namespace BestIt\CommercetoolsODM\Entity;

use BestIt\CommercetoolsODM\Mapping\Annotations as Commercetools;

/**
 * Class ShippingRate
 *
 * @package BestIt\CommercetoolsODM\Entity
 */
class ShippingRate
{
    /**
     * @Commercetools\Field(type="") TODO Money
     * @Commercetools\FreeAbove
     * @var
     */
    private $freeAbove;

    /**
     * @Commercetools\Field(type="") TODO Money
     * @Commercetools\Price
     * @var
     */
    private $price;

    /**
     * gets FreeAbove
     *
     * @return mixed
     */
    public function getFreeAbove()
    {
        return $this->freeAbove;
    }

    /**
     * gets Price
     *
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Sets FreeAbove
     *
     * @param mixed $freeAbove
     */
    public function setFreeAbove($freeAbove)
    {
        $this->freeAbove = $freeAbove;
    }

    /**
     * Sets Price
     *
     * @param mixed $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }
}