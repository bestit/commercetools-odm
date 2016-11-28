<?php

namespace BestIt\CommercetoolsODM\Entity;

use BestIt\CommercetoolsODM\Mapping\Annotations as Commercetools;

/**
 * Class TaxedItemPrice
 *
 * @package BestIt\CommercetoolsODM\Entity
 */
class TaxedItemPrice
{
    /**
     * @Commercetools\Field(type="") TODO Money
     * @Commercetools\TotalGross
     * @var
     */
    private $totalGross;
    /**
     * @Commercetools\Field(type="") TODO Money
     * @Commercetools\TotalNet
     * @var
     */
    private $totalNet;

    /**
     * gets TotalGross
     *
     * @return string
     */
    public function getTotalGross(): string
    {
        return $this->totalGross;
    }

    /**
     * gets TotalNet
     *
     * @return string
     */
    public function getTotalNet(): string
    {
        return $this->totalNet;
    }

    /**
     * Sets TotalGross
     *
     * @param string $totalGross
     */
    public function setTotalGross(string $totalGross)
    {
        $this->totalGross = $totalGross;
    }

    /**
     * Sets TotalNet
     *
     * @param string $totalNet
     */
    public function setTotalNet(string $totalNet)
    {
        $this->totalNet = $totalNet;
    }
}
