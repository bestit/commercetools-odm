<?php

namespace BestIt\CommercetoolsODM\Entity;

use BestIt\CommercetoolsODM\Mapping\Annotations as Commercetools;

/**
 * Class ExternalLineItemTotalPrice
 *
 * @package BestIt\CommercetoolsODM\Entity
 */
class ExternalLineItemTotalPrice
{
    /**
     * @Commercetools\Field(type="") TODO Money
     * @Commercetools\Price
     * @var
     */
    private $price;

    /**
     * @Commercetools\Field(type="") TODO Money
     * @Commercetools\TotalPrice
     * @var
     */
    private $totalPrice;

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
     * gets TotalPrice
     *
     * @return mixed
     */
    public function getTotalPrice()
    {
        return $this->totalPrice;
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

    /**
     * Sets TotalPrice
     *
     * @param mixed $totalPrice
     */
    public function setTotalPrice($totalPrice)
    {
        $this->totalPrice = $totalPrice;
    }
}