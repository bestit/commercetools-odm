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
     * The collection class.
     * @var string
     */
    public $collection = '';

    /**
     * The type for this field in the commercetools platform.
     * @Enum({"array","boolean","dateTime","int","string","set"})
     * @Required
     * @var string
     */
    public $type = 'string';

    /**
     * Returns the name of the collection class.
     * @return string
     */
    public function getCollection()
    {
        return $this->collection;
    }

    /**
     * Returns the type for this field.
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }
}
