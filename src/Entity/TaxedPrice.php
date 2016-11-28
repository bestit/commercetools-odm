<?php

namespace BestIt\CommercetoolsODM\Entity;

use BestIt\CommercetoolsODM\Mapping\Annotations as Commercetools;

/**
 * Class TaxedPrice
 *
 * @package BestIt\CommercetoolsODM\Entity
 */
class TaxedPrice
{
    /**
     * @Commercetools\Field(type="array")
     * @Commercetools\TaxPortion
     * @var array
     */
    private $taxPortions = [];
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
     * gets TaxPortions
     *
     * @return array
     */
    public function getTaxPortions(): array
    {
        return $this->taxPortions;
    }

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
     * Sets TaxPortions
     *
     * @param array $taxPortions
     */
    public function setTaxPortions(array $taxPortions)
    {
        $this->taxPortions = $taxPortions;
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