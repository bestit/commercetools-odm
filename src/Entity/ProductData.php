<?php
namespace BestIt\CommercetoolsODM\Entity;

use BestIt\CommercetoolsODM\Mapping\Annotations as Commercetools;

class ProductData
{
    /**  TODO @var!!!
     * References to categories the product is in.
     * @Commercetools\Field(type="array")
     * @Commercetools\Categories
     * @var
     */
    private $categories = '';
    /**
     * The CategoryOrderHint for the type.
     * @Commercetools\Field(type="") TODO
     * @Commercetools\CategoryOrderHints
     * @var
     */
    private $categoryOrderHints = '';
    /**
     * The Description for the type.
     * @Commercetools\Field(type="") TODO
     * @Commercetools\Description
     * @var
     */
    private $description = '';
    /**
     * The MasterVariant for the type.
     * @Commercetools\Field(type="") TODO
     * @Commercetools\MasterVariant
     * @var
     */
    private $masterVariant  = '';
    /**
     * The MetaDesciption for the type.
     * @Commercetools\Field(type="") TODO
     * @Commercetools\MateDescription
     * @var
     */
    private $metaDescription = '';
    /**
     * The MetaKeywords for the type.
     * @Commercetools\Field(type="") TODO
     * @Commercetools\MetaKeywords
     * @var
     */
    private $metaKeywords = '';
    /**
     * The MetaTitle for the type.
     * @Commercetools\Field(type="") TODO
     * @Commercetools\MetaTitle
     * @var
     */
    private $metaTitle = '';
    /**
     * The Name foor the type.
     * @Commercetools\Field(type="") TODO
     * @Commercetools\Name
     * @var
     */
    private $name = '';
    /**
     * The SearchKeywords for the type.
     * @Commercetools\Field(type="") TODO
     * @Commercetools\SearchKeywords
     * @var
     */
    private $searchKeywords = '';
    /**
     * The Slug for the type.
     * @Commercetools\Field(type="") TODO
     * @Commercetools\Slug
     * @var
     */
    private $slug = '';
    /**
     * The Variants for the type.
     * @Commercetools\Field(type="array")
     * @Commercetools\Variants
     * @var
     */
    private $variants= '';

    /**
     * Returns the Ctaegories for the type.
     * @return string
     */
    public function getCategories(): string
    {
        return $this->categories;
    }

    /**
     * Sets the Categories for the type.
     * @param string $categories
     */
    public function setCategories(string $categories)
    {
        $this->categories = $categories;
    }

    /**
     * Returns the CategoryOrderHints for the type.
     * @return string
     */
    public function getCategoryOrderHints(): string
    {
        return $this->categoryOrderHints;
    }

    /**
     * Sets the CategoryOrderHints for the type.
     * @param string $categoryOrderHints
     */
    public function setCategoryOrderHints(string $categoryOrderHints)
    {
        $this->categoryOrderHints = $categoryOrderHints;
    }

    /**
     * Returns the Description for the type.
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Stets the Description for the type.
     * @param string $description
     */
    public function setDescription(string $description)
    {
        $this->description = $description;
    }

    /**
     * Returns the MasterVariant for the type.
     * @return string
     */
    public function getMasterVariant(): string
    {
        return $this->masterVariant;
    }

    /**
     * Sets the MasterVariant for the type.
     * @param string $masterVariant
     */
    public function setMasterVariant(string $masterVariant)
    {
        $this->masterVariant = $masterVariant;
    }

    /**
     * Retruns the MetDescription for the type.
     * @return string
     */
    public function getMetaDescription(): string
    {
        return $this->metaDescription;
    }

    /**
     * Sets the MetaDescription for the type.
     * @param string $metaDescription
     */
    public function setMetaDescription(string $metaDescription)
    {
        $this->metaDescription = $metaDescription;
    }

    /**
     * Returns the MetaKeywords for the type.
     * @return string
     */
    public function getMetaKeywords(): string
    {
        return $this->metaKeywords;
    }

    /**
     * Sets the MetaKeywords for the type.
     * @param string $metaKeywords
     */
    public function setMetaKeywords(string $metaKeywords)
    {
        $this->metaKeywords = $metaKeywords;
    }

    /**
     * Returns the MetaTitle for the type.
     * @return string
     */
    public function getMetaTitle(): string
    {
        return $this->metaTitle;
    }

    /**
     * Sets the MetaTitle for the type.
     * @param string $metaTitle
     */
    public function setMetaTitle(string $metaTitle)
    {
        $this->metaTitle = $metaTitle;
    }

    /**
     * Returns the Name for the type.
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Sets the Name for the type.
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * Returns the SearchKeywords for the type.
     * @return string
     */
    public function getSearchKeywords(): string
    {
        return $this->searchKeywords;
    }

    /**
     * Sets the SearchKeywords for the type.
     * @param string $searchKeywords
     */
    public function setSearchKeywords(string $searchKeywords)
    {
        $this->searchKeywords = $searchKeywords;
    }

    /**
     * Returns the Slug for the type.
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * Sets the Slug for the type.
     * @param string $slug
     */
    public function setSlug(string $slug)
    {
        $this->slug = $slug;
    }

    /**
     * Returns the Variant for the type.
     * @return string
     */
    public function getVariants(): string
    {
        return $this->variants;
    }

    /**
     * Stets the Variants for the type.
     * @param string $variants
     */
    public function setVariants(string $variants)
    {
        $this->variants = $variants;
    }

}