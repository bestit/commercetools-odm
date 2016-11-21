<?php

namespace BestIt\CommercetoolsODM;

/**
 * Provides basic getters and setters for the commercetools manager.
 * @author lange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM
 * @version $id$
 */
trait CommercetoolsAwareTrait
{
    /**
     * The commercetools document manager.
     * @var DocumentManager|void
     */
    private $commercetools = null;

    /**
     * Returns the commercetools document manager.
     * @return DocumentManager|void
     */
    public function getCommercetools(): DocumentManager
    {
        return $this->commercetools;
    }

    /**
     * Sets the commercetools document manager.
     * @param DocumentManager $commercetools
     * @return CommercetoolsAwareTrait
     */
    public function setCommercetools(DocumentManager $commercetools)
    {
        $this->commercetools = $commercetools;

        return $this;
    }
}
