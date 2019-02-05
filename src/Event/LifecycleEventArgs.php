<?php

namespace BestIt\CommercetoolsODM\Event;

use BestIt\CommercetoolsODM\DocumentManagerInterface;
use Doctrine\Common\EventArgs;

/**
 * The lifecycle event args.
 *
 * @author lange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\Event
 * @subpackage Event
 */
class LifecycleEventArgs extends EventArgs
{
    /**
     * The triggering document.
     *
     * @var mixed
     */
    private $document;

    /**
     * The used document manager.
     *
     * @var DocumentManagerInterface
     */
    private $documentManager;

    /**
     * LifecycleEventArgs constructor.
     *
     * @param mixed $document
     * @param DocumentManagerInterface $documentManager
     */
    public function __construct($document, DocumentManagerInterface $documentManager)
    {
        $this->document = $document;
        $this->documentManager = $documentManager;
    }

    /**
     * Returns the triggering document.
     *
     * @return mixed
     */
    public function getDocument()
    {
        return $this->document;
    }

    /**
     * Returns the used document manager.
     *
     * @return DocumentManagerInterface
     */
    public function getDocumentManager(): DocumentManagerInterface
    {
        return $this->documentManager;
    }
}
