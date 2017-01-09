<?php

namespace BestIt\CommercetoolsODM\Mapping\Annotations;

use Doctrine\Common\Annotations\Annotation\Required;

/**
 * Provides the class name to insert data to the database.
 * @Annotation
 * @author lange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM
 * @subpackage Mapping\Annotations
 * @Target("PROPERTY")
 * @version $id$
 */
class CustomTypeField implements Annotation
{
    /**
     * The type key for this custom type field.
     * @Required
     * @var string
     */
    public $value = '';

    /**
     * Returns the type key for this custom type field.
     * @return string
     */
    public function getType()
    {
        return $this->value;
    }
}
