<?php

namespace BestIt\CommercetoolsODM\Mapping\Annotations;

use Doctrine\Common\Annotations\Annotation\Required;

/**
 * Annotation to mark an repository.
 *
 * @Annotation
 * @author lange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\Mapping\Annotations
 * @subpackage Mapping\Annotations
 * @Target("CLASS")
 */
class Repository implements Annotation
{
    /**
     * The required class name.
     *
     * @Required
     * @var string
     */
    public $value = '';

    /**
     * Returns the class name for this repository.
     *
     * @return string
     */
    public function getClass(): string
    {
        return $this->value;
    }
}
