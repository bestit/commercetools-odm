<?php

namespace BestIt\CommercetoolsODM\Entity;

use BestIt\CommercetoolsODM\Mapping\Annotations as Commercetools;

/**
 * Class LineItem
 * @package BestIt\CommercetoolsODM\Entity
 */
class LineItem
{
    /**
     * @var string
     */
    private $productId = '';
    /**
     * @var string
     */
    private $variantId = '';
    /**
     * @var string
     */
    private $quantity = '';
    /**
     * @var string
     */
    private $supplyChannel = '';
    /**
     * @var string
     */
    private $distributionChannel = '';
    /**
     * @var string
     */
    private $externalTaxRate = '';
    /**
     * @var string
     */
    private $custom = '';

    /**
     * @return string
     */
    public function getProductId(): string
    {
        return $this->productId;
    }

    /**
     * @param string $productId
     */
    public function setProductId(string $productId)
    {
        $this->productId = $productId;
    }

    /**
     * @return string
     */
    public function getVariantId(): string
    {
        return $this->variantId;
    }

    /**
     * @param string $variantId
     */
    public function setVariantId(string $variantId)
    {
        $this->variantId = $variantId;
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
    public function getSupplyChannel(): string
    {
        return $this->supplyChannel;
    }

    /**
     * @param string $supplyChannel
     */
    public function setSupplyChannel(string $supplyChannel)
    {
        $this->supplyChannel = $supplyChannel;
    }

    /**
     * @return string
     */
    public function getDistributionChannel(): string
    {
        return $this->distributionChannel;
    }

    /**
     * @param string $distributionChannel
     */
    public function setDistributionChannel(string $distributionChannel)
    {
        $this->distributionChannel = $distributionChannel;
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

}