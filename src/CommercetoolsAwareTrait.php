<?php

namespace BestIt\CommercetoolsODM;

/**
 * Provides basic getters and setters for the commercetools manager.
 *
 * @author lange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM
 */
trait CommercetoolsAwareTrait
{
    /**
     * The commercetools document manager.
     *
     * @var DocumentManager|void
     */
    private $commercetools = null;

    /**
     * Returns the commercetools document manager.
     *
     * @return DocumentManagerInterface|void
     */
    public function getCommercetools(): DocumentManagerInterface
    {
        return $this->commercetools;
    }

    /**
     * Sets the commercetools document manager.
     *
     * @param DocumentManagerInterface $commercetools
     * @phpcsSuppress BestIt.TypeHints.ReturnTypeDeclaration.MissingReturnTypeHint
     *
     * @return $this
     */
    public function setCommercetools(DocumentManagerInterface $commercetools)
    {
        $this->commercetools = $commercetools;

        return $this;
    }
}
