<?php

namespace BestIt\CommercetoolsODM\Entity;

use BestIt\CommercetoolsODM\Mapping\Annotations as Commercetools;

/**
 * Class Suggestion
 *
 * @package BestIt\CommercetoolsODM\Entity
 */
class Suggestion
{
    /**
     * The suggested text.
     * @Commercetools\Field(type="string")
     * @Commercetools\Text
     * @var string
     */
    private $text;

    /**
     * gets Text
     *
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Sets Text
     *
     * @param mixed $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }
}
