<?php

namespace Fludio\DoctrineFilter\Filter\Type;

use Fludio\DoctrineFilter\Filter\FilterBuilder;

class LessThanEqualFilterType extends AbstractFilterType
{
    public function expand(FilterBuilder $filterBuilder, $value)
    {
        $qb = $filterBuilder->getQueryBuilder();

        return $qb
            ->andWhere($qb->expr()->lte('x.' . $this->field, $filterBuilder->addValue($value)));
    }
}
