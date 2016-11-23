<?php

namespace BestIt\CommercetoolsODM\Mapping\Annotations;

use Doctrine\Common\Annotations\Annotation\Required;

/**
 * Annotation to mark the fields of an commercetools entity.
 * @Annotation
 * @author lange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM
 * @subpackage Mapping\Annotations
 * @Target({"PROPERTY","ANNOTATION"})
 * @version $id$
 */
class Field implements Annotation
{
    /**
     * The type for this field in the commercetools platform.
     * @Enum({"int","string"})
     * @Required
     * @var string
     */
    public $type = 'string';

    /**
     * Returns the type for this field.
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }
}
