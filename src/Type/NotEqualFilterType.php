<?php

namespace Fludio\DoctrineFilter\Type;

use Fludio\DoctrineFilter\FilterBuilder;

class NotEqualFilterType extends AbstractFilterType
{
    public function expand(FilterBuilder $filterBuilder, $value, $table)
    {
        $qb = $filterBuilder->getQueryBuilder();

        return $qb
            ->andWhere($qb->expr()->neq($table . '.' . $this->getFieldOnTable(), $filterBuilder->placeValue($value)));
    }
}
