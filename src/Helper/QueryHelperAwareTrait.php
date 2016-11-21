<?php

namespace BestIt\CommercetoolsODM\Helper;

use Commercetools\Commons\Helper\QueryHelper;

/**
 * Helps with handling the query helper.
 * @author lange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM
 * @subpackage Helper
 * @version $id$
 */
trait QueryHelperAwareTrait
{
    /**
     * The commons query helper.
     * @var QueryHelper
     */
    private $queryHelper = null;

    /**
     * Returns the common query helper for commercetools.
     * @return QueryHelper
     */
    public function getQueryHelper(): QueryHelper
    {
        return $this->queryHelper;
    }

    /**
     * Sets the common query helper for commercetools.
     * @param QueryHelper $queryHelper
     * @return $this
     */
    public function setQueryHelper(QueryHelper $queryHelper)
    {
        $this->queryHelper = $queryHelper;

        return $this;
    }
}
