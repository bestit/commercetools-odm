<?php

namespace BestIt\CommercetoolsODM\Mapping\Annotations;

use Doctrine\Common\Annotations\Annotation\Required;

/**
 * Provides the class name to insert data to the database.
 * @Annotation
 * @author lange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM
 * @subpackage Mapping\Annotations
 * @Target("CLASS")
 * @version $id$
 */
class DraftClass
{
    /**
     * The full qualified class name for the draft class.
     * @Required
     * @var string
     */
    public $value = '';

    /**
     * Returns the full qualified class name for the draft class.
     * @return string
     */
    public function getDraft()
    {
        return $this->value;
    }
}
