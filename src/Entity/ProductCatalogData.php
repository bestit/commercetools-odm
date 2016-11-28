<?php
namespace BestIt\CommercetoolsODM\Entity;

use BestIt\CommercetoolsODM\Mapping\Annotations as Commercetools;

class ProductCatalogData
{
    /** TODO @var!!!
     * The current data of the product.
     * @Commercetools\Field(type="") TODO
     * @Commercetools\Current
     * @var
     */
    private $current = '';
    /**
     *Whether the staged data is different from the current data.
     * @Commercetools\Field(type="Boolean")
     * @Commercetools\HasStagedChanges
     * @var
     */
    private $hasStagedChanges = '';
    /**
     * Whether the product is published.
     * @Commercetools\Field(type="Boolean")
     * @Commercetools\Published
     * @var
     */
    private $published = '';
    /**
     * The staged data of the product.
     * @Commercetools\Field(type="") TODO
     * @Commercetools\Staged
     * @var
     */
    private $staged = '';

    /**
     * Returns the Current for the type.
     * @return string
     */
    public function getCurrent(): string
    {
        return $this->current;
    }

    /**
     * Sets the Current for the type.
     * @param string $current
     */
    public function setCurrent(string $current)
    {
        $this->current = $current;
    }

    /**
     * Returns the HasStagedChanges for the type.
     * @return string
     */
    public function getHasStagedChanges(): string
    {
        return $this->hasStagedChanges;
    }

    /**
     * Sets the HasStagedChanges for the type.
     * @param string $hasStagedChanges
     */
    public function setHasStagedChanges(string $hasStagedChanges)
    {
        $this->hasStagedChanges = $hasStagedChanges;
    }

    /**
     * Returns the Published for the type.
     * @return string
     */
    public function getPublished(): string
    {
        return $this->published;
    }

    /**
     * Sets the Published for the type.
     * @param string $published
     */
    public function setPublished(string $published)
    {
        $this->published = $published;
    }

    /**
     * Returns the Staged for the type.
     * @return string
     */
    public function getStaged(): string
    {
        return $this->staged;
    }

    /**
     * Sets the Staged for the type.
     * @param string $staged
     */
    public function setStaged(string $staged)
    {
        $this->staged = $staged;
    }

}