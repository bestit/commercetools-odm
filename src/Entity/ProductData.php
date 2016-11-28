<?php
namespace BestIt\CommercetoolsODM\Entity;

use BestIt\CommercetoolsODM\Mapping\Annotations as Commercetools;

class ProductData
{
    /**
     * References to categories the product is in.
     * @Commercetools\Field(type="array")
     * @Commercetools\Categories
     * @var array
     */
    private $categories = [];

    /**
     * The CategoryOrderHint for the type.
     * @Commercetools\Field(type="") TODO CategoryOrderHints
     * @Commercetools\CategoryOrderHints
     * @var
     */
    private $categoryOrderHints;

    /**
     * The Description for the type.
     * @Commercetools\Field(type="") TODO LocalizedString
     * @Commercetools\Description
     * @var
     */
    private $description;

    /**
     * The MasterVariant for the type.
     * @Commercetools\Field(type="") TODO ProductVariant
     * @Commercetools\MasterVariant
     * @var
     */
    private $masterVariant;

    /**
     * The MetaDesciption for the type.
     * @Commercetools\Field(type="") TODO LocalizedString
     * @Commercetools\MetaDescription
     * @var
     */
    private $metaDescription;

    /**
     * The MetaKeywords for the type.
     * @Commercetools\Field(type="") TODO LocalizedString
     * @Commercetools\MetaKeywords
     * @var
     */
    private $metaKeywords;

    /**
     * The MetaTitle for the type.
     * @Commercetools\Field(type="") TODO LocalizedString
     * @Commercetools\MetaTitle
     * @var
     */
    private $metaTitle;

    /**
     * The Name foor the type.
     * @Commercetools\Field(type="") TODO LocalizedString
     * @Commercetools\Name
     * @var
     */
    private $name;

    /**
     * The SearchKeywords for the type.
     * @Commercetools\Field(type="") TODO SearchKeyWords
     * @Commercetools\SearchKeywords
     * @var
     */
    private $searchKeywords;

    /**
     * The Slug for the type.
     * @Commercetools\Field(type="") TODO LocalizedString
     * @Commercetools\Slug
     * @var
     */
    private $slug;

    /**
     * The Variants for the type.
     * @Commercetools\Field(type="array")
     * @Commercetools\Variants
     * @var
     */
    private $variants = [];

    /**
     * gets Categories
     *
     * @return array
     */
    public function getCategories(): array
    {
        return $this->categories;
    }

    /**
     * gets CategoryOrderHints
     *
     * @return mixed
     */
    public function getCategoryOrderHints()
    {
        return $this->categoryOrderHints;
    }

    /**
     * gets Description
     *
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * gets MasterVariant
     *
     * @return mixed
     */
    public function getMasterVariant()
    {
        return $this->masterVariant;
    }

    /**
     * gets MetaDescription
     *
     * @return mixed
     */
    public function getMetaDescription()
    {
        return $this->metaDescription;
    }

    /**
     * gets MetaKeywords
     *
     * @return mixed
     */
    public function getMetaKeywords()
    {
        return $this->metaKeywords;
    }

    /**
     * gets MetaTitle
     *
     * @return mixed
     */
    public function getMetaTitle()
    {
        return $this->metaTitle;
    }

    /**
     * gets Name
     *
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * gets SearchKeywords
     *
     * @return mixed
     */
    public function getSearchKeywords()
    {
        return $this->searchKeywords;
    }

    /**
     * gets Slug
     *
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * gets Variants
     *
     * @return mixed
     */
    public function getVariants()
    {
        return $this->variants;
    }

    /**
     * Sets Categories
     *
     * @param array $categories
     */
    public function setCategories(array $categories)
    {
        $this->categories = $categories;
    }

    /**
     * Sets CategoryOrderHints
     *
     * @param mixed $categoryOrderHints
     */
    public function setCategoryOrderHints($categoryOrderHints)
    {
        $this->categoryOrderHints = $categoryOrderHints;
    }

    /**
     * Sets Description
     *
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Sets MasterVariant
     *
     * @param mixed $masterVariant
     */
    public function setMasterVariant($masterVariant)
    {
        $this->masterVariant = $masterVariant;
    }

    /**
     * Sets MetaDescription
     *
     * @param mixed $metaDescription
     */
    public function setMetaDescription($metaDescription)
    {
        $this->metaDescription = $metaDescription;
    }

    /**
     * Sets MetaKeywords
     *
     * @param mixed $metaKeywords
     */
    public function setMetaKeywords($metaKeywords)
    {
        $this->metaKeywords = $metaKeywords;
    }

    /**
     * Sets MetaTitle
     *
     * @param mixed $metaTitle
     */
    public function setMetaTitle($metaTitle)
    {
        $this->metaTitle = $metaTitle;
    }

    /**
     * Sets Name
     *
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Sets SearchKeywords
     *
     * @param mixed $searchKeywords
     */
    public function setSearchKeywords($searchKeywords)
    {
        $this->searchKeywords = $searchKeywords;
    }

    /**
     * Sets Slug
     *
     * @param mixed $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * Sets Variants
     *
     * @param mixed $variants
     */
    public function setVariants($variants)
    {
        $this->variants = $variants;
    }
}