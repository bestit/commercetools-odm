<?php

namespace BestIt\CommercetoolsODM\Filter;

use BestIt\CommercetoolsODM\Exception\FilterException;

/**
 * FilterManagerInterface executing filters
 *
 * @author Michel Chowanski <chowanski@bestit-online.de>
 * @package BestIt\CommercetoolsODM
 */
interface FilterManagerInterface
{
    /**
     * Add filter
     *
     * @param FilterInterface $filter
     *
     * @return void
     */
    public function add(FilterInterface $filter);

    /**
     * Apply filter
     *
     * @param string $key
     * @param mixed $request
     *
     * @return void
     * @throws FilterException
     */
    public function apply(string $key, $request);
}
