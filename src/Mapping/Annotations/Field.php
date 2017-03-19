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
     * Should this field only be red?
     * @var bool
     */
    public $readOnly = false;

    /**
     * The type for this field in the commercetools platform.
     * @Enum({"array","boolean","dateTime","int","resource","string","set"})
     * @Required
     * @var string
     */
    public $type = 'string';

    /**
     * Returns the name of the collection class.
     * @return string
     */
    public function getCollection(): string
    {
        return $this->collection;
    }

    /**
     * Is the field only read?
     * @return bool
     */
    public function isReadOnly(): bool
    {
        return $this->readOnly;
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
