<?php

namespace Fludio\DoctrineFilter\Filter\Type;

use Doctrine\ORM\QueryBuilder;

class LessThanFilterType extends AbstractFilterType
{
    public function expand(QueryBuilder $qb, $value)
    {
        return $qb
            ->andWhere($qb->expr()->lt('x.' . $this->field, ':value4'))
            ->setParameter('value4', $value);
    }
}
