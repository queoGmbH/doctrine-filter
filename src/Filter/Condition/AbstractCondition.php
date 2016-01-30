<?php

namespace Fludio\DoctrineFilter\Filter\Condition;

use Doctrine\ORM\QueryBuilder;

abstract class AbstractCondition
{
    protected $field;

    public function __construct($field)
    {
        $this->field = $field;
    }

    /**
     * @param QueryBuilder $qb
     * @param $value
     * @return QueryBuilder
     */
    abstract public function expand(QueryBuilder $qb, $value);
}