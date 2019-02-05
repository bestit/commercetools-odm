<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\Helper;

use Commercetools\Commons\Helper\QueryHelper;

/**
 * Helps with handling the query helper.
 *
 * @author lange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\Helper
 */
trait QueryHelperAwareTrait
{
    /**
     * @var QueryHelper|null The commons query helper.
     */
    protected $queryHelper = null;

    /**
     * Returns the common query helper for commercetools in a type safe way.
     *
     * @return QueryHelper
     */
    public function getQueryHelper(): QueryHelper
    {
        return $this->queryHelper;
    }

    /**
     * Sets the common query helper for commercetools.
     *
     * @param QueryHelper $queryHelper
     *
     * @return $this
     */
    public function setQueryHelper(QueryHelper $queryHelper): self
    {
        $this->queryHelper = $queryHelper;

        return $this;
    }
}
