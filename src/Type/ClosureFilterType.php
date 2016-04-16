<?php

namespace BiteCodes\DoctrineFilter\Type;

use BiteCodes\DoctrineFilter\FilterBuilder;

class ClosureFilterType extends AbstractFilterType
{
    /**
     * @var \Closure
     */
    protected $closure;

    public function __construct($name, $options, \Closure $closure)
    {
        parent::__construct($name, $options);

        $this->closure = $closure;
    }

    public function expand(FilterBuilder $filterBuilder, $value, $table, $field)
    {
        $qb = $filterBuilder->getQueryBuilder();

        $getValue = function () use ($filterBuilder, $value) {
            return $filterBuilder->placeValue($value);
        };

        call_user_func($this->closure, $qb, $table, $field, $getValue);
    }
}