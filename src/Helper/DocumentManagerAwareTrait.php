<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\Helper;

use BestIt\CommercetoolsODM\DocumentManagerInterface;

/**
 * Helps to provide the document manager.
 *
 * @author lange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\Helper
 */
trait DocumentManagerAwareTrait
{
    /**
     * @var DocumentManagerInterface|null The used document manager.
     */
    protected $documentManager = null;

    /**
     * Returns the used document manager in a type safe way.
     *
     * @return DocumentManagerInterface
     */
    public function getDocumentManager(): DocumentManagerInterface
    {
        return $this->documentManager;
    }

    /**
     * Sets the used document manager.
     *
     * @param DocumentManagerInterface $documentManager The used document manager.
     *
     * @return $this
     */
    public function setDocumentManager(DocumentManagerInterface $documentManager): self
    {
        $this->documentManager = $documentManager;

        return $this;
    }
}
