<?php
namespace BestIt\CommercetoolsODM\Entity;

use BestIt\CommercetoolsODM\Mapping\Annotations as Commercetools;

class ProductCatalogData
{
    /**
     * The current data of the product.
     * @Commercetools\Field(type="") TODO ProductData
     * @Commercetools\Current
     * @var
     */
    private $current;

    /**
     * Whether the staged data is different from the current data.
     * @Commercetools\Field(type="Boolean")
     * @Commercetools\HasStagedChanges
     * @var boolean
     */
    private $hasStagedChanges = false;

    /**
     * Whether the product is published.
     * @Commercetools\Field(type="Boolean")
     * @Commercetools\Published
     * @var boolean
     */
    private $published = false;

    /**
     * The staged data of the product.
     * @Commercetools\Field(type="") TODO ProductData
     * @Commercetools\Staged
     * @var
     */
    private $staged;

    /**
     * gets Current
     *
     * @return mixed
     */
    public function getCurrent()
    {
        return $this->current;
    }

    /**
     * gets Staged
     *
     * @return mixed
     */
    public function getStaged()
    {
        return $this->staged;
    }

    /**
     * iss HasStagedChanges
     *
     * @return boolean
     */
    public function isHasStagedChanges(): bool
    {
        return $this->hasStagedChanges;
    }

    /**
     * iss Published
     *
     * @return boolean
     */
    public function isPublished(): bool
    {
        return $this->published;
    }

    /**
     * Sets Current
     *
     * @param mixed $current
     */
    public function setCurrent($current)
    {
        $this->current = $current;
    }

    /**
     * Sets HasStagedChanges
     *
     * @param boolean $hasStagedChanges
     */
    public function setHasStagedChanges(bool $hasStagedChanges)
    {
        $this->hasStagedChanges = $hasStagedChanges;
    }

    /**
     * Sets Published
     *
     * @param boolean $published
     */
    public function setPublished(bool $published)
    {
        $this->published = $published;
    }

    /**
     * Sets Staged
     *
     * @param mixed $staged
     */
    public function setStaged($staged)
    {
        $this->staged = $staged;
    }
}