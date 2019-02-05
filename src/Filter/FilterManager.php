<?php

namespace BestIt\CommercetoolsODM\Filter;

use BestIt\CommercetoolsODM\Exception\FilterException;

/**
 * FilterManager for collecting and executing filters
 *
 * @author Michel Chowanski <chowanski@bestit-online.de>
 * @package BestIt\CommercetoolsODM\Filter
 */
class FilterManager implements FilterManagerInterface
{
    /**
     * The collected filters
     *
     * @var FilterInterface[]
     */
    private $filters = [];

    /**
     * Adds a filter to this manager.
     *
     * @param FilterInterface $filter
     *
     * @return void
     */
    public function add(FilterInterface $filter)
    {
        $this->filters[$filter->getKey()] = $filter;
    }

    /**
     * Applies the filter to the given request.
     *
     * @throws FilterException
     *
     * @param string $key
     * @param mixed $request
     *
     * @return void
     */
    public function apply(string $key, $request)
    {
        if (!array_key_exists($key, $this->filters)) {
            throw new FilterException(sprintf('Filter with key `%s` not found', $key));
        }

        $this->filters[$key]->apply($request);
    }

    /**
     * Get all filters
     *
     * @return array
     */
    public function all(): array
    {
        return $this->filters;
    }
}
