<?php

namespace Fludio\DoctrineFilter\Filter\Type;

use Fludio\DoctrineFilter\Filter\FilterBuilder;

class LikeFilterType extends AbstractFilterType
{
    public function expand(FilterBuilder $filterBuilder, $value)
    {
        $qb = $filterBuilder->getQueryBuilder();

        return $qb
            ->andWhere(
                $qb->expr()->like('x.' . $this->field, $filterBuilder->placeValue('%' . $value . '%'))
            );
    }

}