<?php

namespace Queo\DoctrineFilter\Type;

use Queo\DoctrineFilter\FilterBuilder;

class GreaterThanFilterType extends AbstractFilterType
{
    public function expand(FilterBuilder $filterBuilder, $value, $table, $field, $where)
    {
        $qb = $filterBuilder->getQueryBuilder();

        return $this->add($qb, $where, $qb->expr()->gt($table . '.' . $field, $filterBuilder->placeValue($value)));
    }
}
