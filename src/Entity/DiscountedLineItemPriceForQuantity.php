<?php

namespace BestIt\CommercetoolsODM\Entity;

use BestIt\CommercetoolsODM\Mapping\Annotations as Commercetools;

/**
 * Class DiscountedLineItemPriceForQuantity
 *
 * @package BestIt\CommercetoolsODM\Entity
 */
class DiscountedLineItemPriceForQuantity
{
    /**
     * @Commercetools\Field(type="") TODO DiscountedLineItemPortion
     * @Commercetools\DiscountedPrice
     * @var
     */
    private $discountedPrice;

    /**
     * @Commercetools\Field(type="int")
     * @Commercetools\Quantity
     * @var int
     */
    private $quantity;

    /**
     * gets DiscountedPrice
     *
     * @return mixed
     */
    public function getDiscountedPrice()
    {
        return $this->discountedPrice;
    }

    /**
     * gets Quantity
     *
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * Sets DiscountedPrice
     *
     * @param mixed $discountedPrice
     */
    public function setDiscountedPrice($discountedPrice)
    {
        $this->discountedPrice = $discountedPrice;
    }

    /**
     * Sets Quantity
     *
     * @param int $quantity
     */
    public function setQuantity(int $quantity)
    {
        $this->quantity = $quantity;
    }
}
