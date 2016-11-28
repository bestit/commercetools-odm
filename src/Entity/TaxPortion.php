<?php

namespace BestIt\CommercetoolsODM\Entity;

use BestIt\CommercetoolsODM\Mapping\Annotations as Commercetools;

/**
 * Class TaxPortion
 *
 * @package BestIt\CommercetoolsODM\Entity
 */
class TaxPortion
{
    /**
     * @Commercetools\Field(type="") TODO Money
     * @Commercetools\Amount
     * @var
     */
    private $amount;

    /**
     * @Commercetools\Field(type="string")
     * @Commercetools\Name
     * @var string
     */
    private $name = '';

    /**
     * @Commercetools\Field(type="int")
     * @Commercetools\Rate
     * @var int
     */
    private $rate = 0;

    /**
     * gets Amount
     *
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * gets Name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * gets Rate
     *
     * @return int
     */
    public function getRate(): int
    {
        return $this->rate;
    }

    /**
     * Sets Amount
     *
     * @param mixed $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * Sets Name
     *
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * Sets Rate
     *
     * @param int $rate
     */
    public function setRate(int $rate)
    {
        $this->rate = $rate;
    }
}
