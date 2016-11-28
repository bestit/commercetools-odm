<?php

namespace BestIt\CommercetoolsODM\Entity;

use BestIt\CommercetoolsODM\Mapping\Annotations as Commercetools;

/**
 * Class AttributeDefinition
 *
 * @package BestIt\CommercetoolsODM\Entity
 */
class AttributeDefinition
{
    /**
     * Describes how an attribute or a set of attributes should be validated across all variants of a product.
     * @Commercetools\Field(type="") TODO AttributeConstraint
     * @Commercetools\AttributeConstraint
     * @var
     */
    private $attributeConstraint;

    /**
     * Provides a visual representation type for this attribute. only relevant for text-based attribute types
     * like TextType and LocalizableTextType.
     * @Commercetools\Field(type="AttributeConstraint") TODO TextInputHint
     * @Commercetools\InputHint
     * @var
     */
    private $inputHint;

    /**
     * Additional information about the attribute that aids content managers when setting product details.
     * @Commercetools\Field(type="") TODO LocalizedString
     * @Commercetools\InputTip
     * @var
     */
    private $inputTip;

    /**
     * Whether the attribute is required to have a value.
     * @Commercetools\Field(type="Boolean")
     * @Commercetools\isRequired
     * @var boolean
     */
    private $isRequired = false;

    /**
     * Whether the attribute’s values should generally be enabled in product search.
     * This determines whether the value is stored in products for matching terms in the context of full-text
     * search queries and can be used in facets & filters as part of product search queries.
     * The exact features that are enabled/disabled with this flag depend on the concrete attribute type and
     * are described there. The max size of a searchable field is restricted to 10922 characters.
     * This constraint is enforced at both product creation and product update.
     * If the length of the input exceeds the maximum size an InvalidField error is returned.
     * @Commercetools\Field(type="Boolean")
     * @Commercetools\isSearchable
     * @var boolean
     */
    private $isSearchable = false;

    /**
     * A human-readable label for the attribute.
     * @Commercetools\Field(type="") TODO LocalizedString
     * @Commercetools\Label
     * @var
     */
    private $label;

    /**
     * The unique name of the attribute used in the API. The name must be between two and 36 characters long and
     * can contain the ASCII letters A to Z in lowercase or uppercase, digits, underscores (_) and the hyphen-minus (-).
     * It is allowed to have attributes with the same name in two or more productTypes. The important constraint is:
     * all fields of the AttributeDefinition need to be the same across all attributes with the same name.
     * @Commercetools\Field(type="string")
     * @Commercetools\Name
     * @var string
     */
    private $name = '';

    /**
     * Describes the type of the attribute.
     * @Commercetools\Field(type="") TODO AttributeType
     * @Commercetools\Type
     * @var
     */
    private $type;

    /**
     * gets AttributeConstraint
     *
     * @return mixed
     */
    public function getAttributeConstraint()
    {
        return $this->attributeConstraint;
    }

    /**
     * gets InputHint
     *
     * @return mixed
     */
    public function getInputHint()
    {
        return $this->inputHint;
    }

    /**
     * gets InputTip
     *
     * @return mixed
     */
    public function getInputTip()
    {
        return $this->inputTip;
    }

    /**
     * gets Label
     *
     * @return mixed
     */
    public function getLabel()
    {
        return $this->label;
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
     * gets Type
     *
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * iss IsRequired
     *
     * @return boolean
     */
    public function isIsRequired(): bool
    {
        return $this->isRequired;
    }

    /**
     * iss IsSearchable
     *
     * @return boolean
     */
    public function isIsSearchable(): bool
    {
        return $this->isSearchable;
    }

    /**
     * Sets AttributeConstraint
     *
     * @param mixed $attributeConstraint
     */
    public function setAttributeConstraint($attributeConstraint)
    {
        $this->attributeConstraint = $attributeConstraint;
    }

    /**
     * Sets InputHint
     *
     * @param mixed $inputHint
     */
    public function setInputHint($inputHint)
    {
        $this->inputHint = $inputHint;
    }

    /**
     * Sets InputTip
     *
     * @param mixed $inputTip
     */
    public function setInputTip($inputTip)
    {
        $this->inputTip = $inputTip;
    }

    /**
     * Sets IsRequired
     *
     * @param boolean $isRequired
     */
    public function setIsRequired(bool $isRequired)
    {
        $this->isRequired = $isRequired;
    }

    /**
     * Sets IsSearchable
     *
     * @param boolean $isSearchable
     */
    public function setIsSearchable(bool $isSearchable)
    {
        $this->isSearchable = $isSearchable;
    }

    /**
     * Sets Label
     *
     * @param mixed $label
     */
    public function setLabel($label)
    {
        $this->label = $label;
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
     * Sets Type
     *
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }
}
