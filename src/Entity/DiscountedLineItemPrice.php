<?php

namespace BestIt\CommercetoolsODM\Entity;

use BestIt\CommercetoolsODM\Mapping\Annotations as Commercetools;

/**
 * Class DiscountedLineItemPrice
 *
 * @package BestIt\CommercetoolsODM\Entity
 */
class DiscountedLineItemPrice
{
    /**
     * @Commercetools\Field(type="array")
     * @Commercetools\IncludedDiscounts
     * @var array
     */
    private $includedDiscounts;
    /**
     * @Commercetools\Field(type="") TODO Money
     * @Commercetools\Value
     * @var
     */
    private $value;

    /**
     * gets IncludedDiscounts
     *
     * @return array
     */
    public function getIncludedDiscounts(): array
    {
        return $this->includedDiscounts;
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
     * Sets IncludedDiscounts
     *
     * @param array $includedDiscounts
     */
    public function setIncludedDiscounts(array $includedDiscounts)
    {
        $this->includedDiscounts = $includedDiscounts;
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
