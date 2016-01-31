<?php

namespace Fludio\DoctrineFilter\Filter\Type;

use Fludio\DoctrineFilter\Filter\FilterBuilder;

class LikeFilterType extends AbstractFilterType
{
    public function expand(FilterBuilder $filterBuilder, $value, $table)
    {
        $qb = $filterBuilder->getQueryBuilder();

        return $qb
            ->andWhere(
                $qb->expr()->like($table . '.' . $this->getFieldOnTable(), $filterBuilder->placeValue('%' . $value . '%'))
            );
    }

}