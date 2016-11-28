<?php

namespace BestIt\CommercetoolsODM\Entity;

use BestIt\CommercetoolsODM\Mapping\Annotations as Commercetools;

/**
 * Class DiscountCodeInfo
 *
 * @package BestIt\CommercetoolsODM\Entity
 */
class DiscountCodeInfo
{
    /**
     * @Commercetools\Field(type="") TODO Reference
     * @Commercetools\DiscountCode
     * @var
     */
    private $discountCode;

    /**
     * @Commercetools\Field(type="") TODO DiscountCodeState
     * @Commercetools\State
     * @var
     */
    private $state;

    /**
     * gets DiscountCode
     *
     * @return mixed
     */
    public function getDiscountCode()
    {
        return $this->discountCode;
    }

    /**
     * gets State
     *
     * @return mixed
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Sets DiscountCode
     *
     * @param mixed $discountCode
     */
    public function setDiscountCode($discountCode)
    {
        $this->discountCode = $discountCode;
    }

    /**
     * Sets State
     *
     * @param mixed $state
     */
    public function setState($state)
    {
        $this->state = $state;
    }
}
