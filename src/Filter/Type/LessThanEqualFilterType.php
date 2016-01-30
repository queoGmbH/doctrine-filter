<?php

namespace Fludio\DoctrineFilter\Filter\Type;

use Doctrine\ORM\QueryBuilder;

class LessThanEqualFilterType extends AbstractFilterType
{
    public function expand(QueryBuilder $qb, $value)
    {
        return $qb
            ->andWhere($qb->expr()->lte('x.' . $this->field, ':value4'))
            ->setParameter('value4', $value);
    }
}
