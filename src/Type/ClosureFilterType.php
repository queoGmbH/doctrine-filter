<?php

namespace Queo\DoctrineFilter\Type;

use Queo\DoctrineFilter\FilterBuilder;

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

    public function expand(FilterBuilder $filterBuilder, $value, $table, $field, $where)
    {
        $qb = $filterBuilder->getQueryBuilder();

        $getValue = function () use ($filterBuilder, $value) {
            return $filterBuilder->placeValue($value);
        };

        call_user_func($this->closure, $qb, $table, $field, $getValue);
    }
}