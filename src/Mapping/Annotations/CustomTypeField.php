<?php

namespace BestIt\CommercetoolsODM\Mapping\Annotations;

use Doctrine\Common\Annotations\Annotation\Required;

/**
 * Maps a normal property to a custom type field for commercetools.
 *
 * @Annotation
 * @author lange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\Mapping\Annotations
 * @subpackage Mapping\Annotations
 * @Target("PROPERTY")
 */
class CustomTypeField implements Annotation
{
    /**
     * The type key for this custom type field.
     *
     * @Required
     * @var string
     */
    public $value = '';

    /**
     * Returns the type key for this custom type field.
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->value;
    }
}
