<?php

namespace BiteCodes\DoctrineFilter\Tests\Dummy\Filter;

use BiteCodes\DoctrineFilter\FilterBuilder;
use BiteCodes\DoctrineFilter\FilterInterface;

class TestFilter implements FilterInterface
{
    /**
     * @var \Closure
     */
    protected $filterDefinition;

    /**
     * @param FilterBuilder $builder
     */
    public function buildFilter(FilterBuilder $builder)
    {
        call_user_func($this->filterDefinition, $builder);
    }

    /**
     * @param \Closure $filterDefinition
     */
    public function defineFilter(\Closure $filterDefinition)
    {
        $this->filterDefinition = $filterDefinition;
    }
}
