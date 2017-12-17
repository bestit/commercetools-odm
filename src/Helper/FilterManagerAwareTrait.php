<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\Helper;

use BestIt\CommercetoolsODM\Filter\FilterManagerInterface;

/**
 * Getter and setter for filter manager.
 *
 * @author blange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\Helper
 */
trait FilterManagerAwareTrait
{
    /**
     * @var FilterManagerInterface|null The used filter manager
     */
    protected $filterManager = null;

    /**
     * Returns the used filter manager in a type safe way.
     *
     * @return FilterManagerInterface
     */
    public function getFilterManager(): FilterManagerInterface
    {
        return $this->filterManager;
    }

    /**
     * Sets the used filter manager
     *
     * @param FilterManagerInterface $filterManager
     * @return $this
     */
    public function setFilterManager(FilterManagerInterface $filterManager): self
    {
        $this->filterManager = $filterManager;

        return $this;
    }
}
