<?php

namespace BestIt\CommercetoolsODM\Helper;

use BestIt\CommercetoolsODM\Filter\FilterManagerInterface;

/**
 * Getter and setter for filter manager
 * @package BestIt\CommercetoolsODM\Helper
 */
trait FilterManagerAwareTrait
{
    /**
     * The used filter manager
     *
     * @var FilterManagerInterface
     */
    private $filterManager;

    /**
     * Returns the used document manager
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
     *
     * @return $this
     */
    public function setFilterManager(FilterManagerInterface $filterManager)
    {
        $this->filterManager = $filterManager;

        return $this;
    }
}
