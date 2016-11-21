<?php

namespace BestIt\CommercetoolsODM\Event;

use Doctrine\Common\EventArgs;

class OnFlushEventArgs extends EventArgs
{
    private $documentManager;

    public function __construct($documentManager)
    {
        $this->documentManager = $documentManager;
    }

    /**
     * @return \Doctrine\ODM\CouchDB\DocumentManager
     */
    public function getDocumentManager()
    {
        return $this->documentManager;
    }
}
