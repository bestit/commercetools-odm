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
    protected $queryHelper;

    /**
     * @var GeneratorQueryHelper|null The commons query helper.
     */
    protected $generatorQueryHelper;

    /**
     * Returns the common query helper for commercetools in a type safe way.
     *
     * @deprecated Use "getBestitQueryHelper" instead. Will be removed/replaced in 2.0
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
     * @deprecated Use "setBestitQueryHelper" instead. Will be removed/replaced in 2.0
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

    /**
     * Returns the common query helper for commercetools in a type safe way.
     *
     * @return GeneratorQueryHelper
     */
    public function getGeneratorQueryHelper(): GeneratorQueryHelper
    {
        return $this->generatorQueryHelper;
    }

    /**
     * Sets the common query helper.
     *
     * @param GeneratorQueryHelper $generatorQueryHelper
     *
     * @return $this
     */
    public function setGeneratorQueryHelper(GeneratorQueryHelper $generatorQueryHelper): self
    {
        $this->generatorQueryHelper = $generatorQueryHelper;

        return $this;
    }
}
