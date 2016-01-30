<?php

namespace Fludio\DoctrineFilter\Filter\Type;

use Doctrine\ORM\QueryBuilder;

class GreaterThanFilterType extends AbstractFilterType
{
    public function expand(QueryBuilder $qb, $value)
    {
        return $qb
            ->andWhere($qb->expr()->gt('x.' . $this->field, ':value3'))
            ->setParameter('value3', $value);
    }
}
