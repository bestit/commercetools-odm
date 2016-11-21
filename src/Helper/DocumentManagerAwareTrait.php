<?php

namespace BestIt\CommercetoolsODM\Helper;

use BestIt\CommercetoolsODM\DocumentManagerInterface;

/**
 * Helps to provide the document manager.
 * @author lange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM
 * @subpackage Helper
 * @version $id$
 */
trait DocumentManagerAwareTrait
{
    /**
     * The used document manager.
     * @var DocumentManagerInterface
     */
    private $documentManager = null;

    /**
     * Returns the used document manager.
     * @return DocumentManagerInterface
     */
    public function getDocumentManager(): DocumentManagerInterface
    {
        return $this->documentManager;
    }

    /**
     * Sets the used document manager.
     * @param DocumentManagerInterface $documentManager
     * @return $this
     */
    public function setDocumentManager(DocumentManagerInterface $documentManager)
    {
        $this->documentManager = $documentManager;

        return $this;
    }
}
