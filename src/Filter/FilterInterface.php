<?php

namespace BestIt\CommercetoolsODM\Filter;

/**
 * Interface FilterInterface
 *
 * @author Michel Chowanski <chowanski@bestit-online.de>
 * @package BestIt\CommercetoolsODM\Filter
 */
interface FilterInterface
{
    /**
     * Return filter key
     *
     * @return string
     */
    public function getKey(): string;

    /**
     * Apply filter on request
     *
     * @param mixed $request
     *
     * @return void
     */
    public function apply($request);
}
