<?php
namespace BestIt\CommercetoolsODM\Entity;

use BestIt\CommercetoolsODM\Mapping\Annotations as Commercetools;

class DiscountPrice
{
    /**
     * The Discount for the type.
     * @Commercetools\Field(type="") TODO Reference
     * @Commercetools\Discount
     * @var
     */
    private $discount;

    /**
     * The Value for the type.
     * @Commercetools\Field(type="") TODO Money
     * @Commercetools\Value
     * @var
     */
    private $value;

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
     * gets Value
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
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
     * Sets Value
     *
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }
}