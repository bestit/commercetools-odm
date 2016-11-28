<?php

namespace BestIt\CommercetoolsODM\Entity;

use BestIt\CommercetoolsODM\Mapping\Annotations as Commercetools;

/**
 * Class CustomLineItemDraft
 * @package BestIt\CommercetoolsODM\Entity
 */
class CustomLineItemDraft
{
    /**
     * @var string
     */
    private $custom = '';
    /**
     * @var string
     */
    private $externalTaxRate = '';
    /**
     * @var string
     */
    private $money = '';
    /**
     * @var string
     */
    private $name = '';
    /**
     * @var string
     */
    private $quantity = '';
    /**
     * @var string
     */
    private $slug = '';
    /**
     * @var string
     */
    private $taxCategory = '';

    /**
     * @return string
     */
    public function getCustom(): string
    {
        return $this->custom;
    }

    /**
     * @param string $custom
     */
    public function setCustom(string $custom)
    {
        $this->custom = $custom;
    }

    /**
     * @return string
     */
    public function getExternalTaxRate(): string
    {
        return $this->externalTaxRate;
    }

    /**
     * @param string $externalTaxRate
     */
    public function setExternalTaxRate(string $externalTaxRate)
    {
        $this->externalTaxRate = $externalTaxRate;
    }

    /**
     * @return string
     */
    public function getMoney(): string
    {
        return $this->money;
    }

    /**
     * @param string $money
     */
    public function setMoney(string $money)
    {
        $this->money = $money;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getQuantity(): string
    {
        return $this->quantity;
    }

    /**
     * @param string $quantity
     */
    public function setQuantity(string $quantity)
    {
        $this->quantity = $quantity;
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug(string $slug)
    {
        $this->slug = $slug;
    }

    /**
     * @return string
     */
    public function getTaxCategory(): string
    {
        return $this->taxCategory;
    }

    /**
     * @param string $taxCategory
     */
    public function setTaxCategory(string $taxCategory)
    {
        $this->taxCategory = $taxCategory;
    }

}