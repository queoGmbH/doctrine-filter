<?php

namespace Fludio\DoctrineFilter\Filter\OrderBy;

use Fludio\DoctrineFilter\Filter\FilterBuilder;

class OrderByType
{
    protected $field;

    protected $ordering;

    public function __construct($field, $ordering)
    {
        $this->field = $field;
        $this->ordering = $ordering;
    }

    public function expand(FilterBuilder $filterBuilder)
    {
        $qb = $filterBuilder->getQueryBuilder();

        return $qb
            ->orderBy('x.' . $this->field, $this->ordering);
    }
}