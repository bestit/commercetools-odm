<?php
namespace BestIt\CommercetoolsODM\Entity;

use BestIt\CommercetoolsODM\Mapping\Annotations as Commercetools;

/**
 * Class SubRate
 * @package BestIt\CommercetoolsODM\Entity
 */
class SubRate
{
    /**
     * The Number for the type.
     * @Commercetools\Field(type="string")
     * @Commercetools\Name
     * @var string
     */
    private $name = '';
    /**
     * Number Percentage in the range of [0..1].
     * @Commercetools\Field(type="") TODO
     * @Commercetools\Discount
     * @var  TODO
     */
    private $amount = '';

    /**
     * Returns the Amount for the type.
     * @return string
     */
    public function getAmount(): string
    {
        return $this->amount;
    }

    /**
     * Sets the Amount for the type.
     * @param string $amount
     */
    public function setAmount(string $amount)
    {
        $this->amount = $amount;
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

}
