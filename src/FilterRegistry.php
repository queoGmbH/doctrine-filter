<?php

namespace Queo\DoctrineFilter;

class FilterRegistry
{
    /**
     * @var FilterInterface[]
     */
    protected $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    /**
     * @param string $filter
     * @return bool
     */
    public function has($filter)
    {
        return isset($this->filters[$filter]);
    }

    /**
     * @param string $filter
     * @return FilterInterface
     */
    public function get($filter)
    {
        return $this->filters[$filter];
    }

    /**
     * @param FilterInterface $filter
     */
    public function add(FilterInterface $filter)
    {
        $this->filters[get_class($filter)] = $filter;
    }
}
