<?php

namespace BestIt\CommercetoolsODM\Mapping\Annotations;

/**
 * Topmost annotiation to mark an importing document.
 * @Annotation
 * @author lange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM
 * @subpackage Mapping\Annotations
 * @Target("CLASS")
 * @version $id$
 */
class Entity
{
    /**
     * The request map for this entity.
     * @var \BestIt\CommercetoolsODM\Mapping\Annotations\RequestMap
     */
    public $requestMap = null;

    /**
     * Returns the request map if there is one.
     * @return RequestMap|void
     */
    public function getRequestMap()
    {
        return $this->requestMap;
    }
}
