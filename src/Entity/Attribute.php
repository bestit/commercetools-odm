<?php

namespace BestIt\CommercetoolsODM\Entity;

use BestIt\CommercetoolsODM\Mapping\Annotations as Commercetools;

/**
 * Class Attribute
 *
 * @package BestIt\CommercetoolsODM\Entity
 */
class Attribute
{
    /**
     * @Commercetools\Field(type="string")
     * @Commercetools\Name
     * @var string
     */
    private $name;

    /**
     * A valid JSON value, based on an AttributeDefinition.
     * @Commercetools\Field(type="") TODO JSON
     * @Commercetools\Value
     * @var
     */
    private $value;

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
     * gets Value
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
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
     * Sets Value
     *
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }
}
