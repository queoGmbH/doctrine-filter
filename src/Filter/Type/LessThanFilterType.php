<?php

namespace Fludio\DoctrineFilter\Filter\Type;

use Fludio\DoctrineFilter\Filter\FilterBuilder;

class LessThanFilterType extends AbstractFilterType
{
    public function expand(FilterBuilder $filterBuilder, $value)
    {
        $qb = $filterBuilder->getQueryBuilder();

        return $qb
            ->andWhere($qb->expr()->lt('x.' . $this->field, $filterBuilder->placeValue($value)));
    }
}
