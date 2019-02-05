<?php

namespace BestIt\CommercetoolsODM\Mapping\Annotations;

/**
 * Topmost annotation to mark an importing document.
 *
 * @Annotation
 * @author lange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\Mapping\Annotations
 * @Target("CLASS")
 */
class Entity implements Annotation
{
    /**
     * The request map for this entity.
     *
     * @var \BestIt\CommercetoolsODM\Mapping\Annotations\RequestMap
     */
    public $requestMap = null;

    /**
     * Returns the request map if there is one.
     *
     * @return RequestMap|void
     */
    public function getRequestMap()
    {
        return $this->requestMap;
    }
}
