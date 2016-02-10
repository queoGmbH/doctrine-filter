<?php

namespace Fludio\DoctrineFilter\Type;

use Fludio\DoctrineFilter\FilterBuilder;

class LikeFilterType extends AbstractFilterType
{
    public function expand(FilterBuilder $filterBuilder, $value, $table, $field)
    {
        $qb = $filterBuilder->getQueryBuilder();

        return $qb
            ->andWhere(
                $qb->expr()->like($table . '.' . $field, $filterBuilder->placeValue('%' . $value . '%'))
            );
    }

}