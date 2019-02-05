<?php

namespace BestIt\CommercetoolsODM\Mapping\Annotations;

use Doctrine\Common\Annotations\Annotation\Required;

/**
 * Provides the class name to insert data to the database.
 *
 * @Annotation
 * @author lange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\Mapping\Annotations
 * @subpackage Mapping\Annotations
 * @Target("CLASS")
 */
class DraftClass implements Annotation
{
    /**
     * The full qualified class name for the draft class.
     *
     * @Required
     * @var string
     */
    public $value = '';

    /**
     * Returns the full qualified class name for the draft class.
     *
     * @return string
     */
    public function getDraft(): string
    {
        return $this->value;
    }
}
