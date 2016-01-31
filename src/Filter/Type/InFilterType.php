<?php

namespace Fludio\DoctrineFilter\Filter\Type;

use Doctrine\ORM\QueryBuilder;
use Fludio\DoctrineFilter\Filter\FilterBuilder;

class InFilterType extends AbstractFilterType
{
    public function expand(FilterBuilder $filterBuilder, $value, $table)
    {
        $qb = $filterBuilder->getQueryBuilder();

        return $qb
            ->andWhere($qb->expr()->in($table . '.' . $this->getFieldOnTable(), $filterBuilder->placeValue($value)));
    }
}
