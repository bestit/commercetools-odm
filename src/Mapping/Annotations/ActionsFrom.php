<?php

namespace BestIt\CommercetoolsODM\Mapping\Annotations;

use Doctrine\Common\Annotations\Annotation\Required;

/**
 * Annotation to get actions from another model.
 * @Annotation
 * @author lange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM
 * @subpackage Mapping\Annotations
 * @Target("CLASS")
 * @version $id$
 */
class ActionsFrom implements Annotation
{
    /**
     * The required class name.
     * @Required
     * @var string
     */
    public $value = '';

    /**
     * Returns the class name for the extended model.
     * @return string
     */
    public function getModelClass(): string
    {
        return $this->value;
    }

    /**
     * Sets the class name for the extended model.
     * @param string $value
     * @return ActionsFrom
     */
    public function setModelClass(string $value): ActionsFrom
    {
        $this->value = $value;

        return $this;
    }
}
