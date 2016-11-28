<?php

namespace BestIt\CommercetoolsODM\Entity;

use BestIt\CommercetoolsODM\Mapping\Annotations as Commercetools;

/**
 * Class DiscountedLineItemPortion
 *
 * @package BestIt\CommercetoolsODM\Entity
 */
class DiscountedLineItemPortion
{
    /**
     * @Commercetools\Field(type="") TODO Reference
     * @Commercetools\Discount
     * @var
     */
    private $discount;

    /**
     * @Commercetools\Field(type="") TODO Money
     * @Commercetools\DiscountAmount
     * @var
     */
    private $discountedAmount;

    /**
     * gets Discount
     *
     * @return mixed
     */
    public function getDiscount()
    {
        return $this->discount;
    }

    /**
     * gets DiscountedAmount
     *
     * @return mixed
     */
    public function getDiscountedAmount()
    {
        return $this->discountedAmount;
    }

    /**
     * Sets Discount
     *
     * @param mixed $discount
     */
    public function setDiscount($discount)
    {
        $this->discount = $discount;
    }

    /**
     * Sets DiscountedAmount
     *
     * @param mixed $discountedAmount
     */
    public function setDiscountedAmount($discountedAmount)
    {
        $this->discountedAmount = $discountedAmount;
    }
}
