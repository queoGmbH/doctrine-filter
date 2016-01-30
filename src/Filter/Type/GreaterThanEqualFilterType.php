<?php

namespace Fludio\DoctrineFilter\Filter\Type;

use Fludio\DoctrineFilter\Filter\FilterBuilder;

class GreaterThanEqualFilterType extends AbstractFilterType
{
    public function expand(FilterBuilder $filterBuilder, $value)
    {
        $qb = $filterBuilder->getQueryBuilder();

        return $qb
            ->andWhere($qb->expr()->gte('x.' . $this->field, $filterBuilder->addValue($value)));
    }
}
