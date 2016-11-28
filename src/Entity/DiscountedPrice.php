<?php
namespace BestIt\CommercetoolsODM\Entity;

use BestIt\CommercetoolsODM\Mapping\Annotations as Commercetools;

class DiscountPrice
{
    /**     TODO @var!!
     * The Discount for the type.
     * @Commercetools\Field(type="") TODO
     * @Commercetools\Discount
     * @var
     */
    private $discount = '';
    /**
     * The Value for the type.
     * @Commercetools\Field(type="") TODO
     * @Commercetools\Value
     * @var
     */
    private $value = '';

    /**
     * Returns the Discount for the type.
     * @return string
     */
    public function getDiscount(): string
    {
        return $this->discount;
    }

    /**
     * Sets the Discount for the type.
     * @param string $discount
     */
    public function setDiscount(string $discount)
    {
        $this->discount = $discount;
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