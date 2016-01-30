<?php

namespace Fludio\DoctrineFilter\Filter\Type;

use Doctrine\ORM\QueryBuilder;

class EqualFilterType extends AbstractFilterType
{
    public function expand(QueryBuilder $qb, $value)
    {
        return $qb
            ->andWhere($qb->expr()->eq('x.' . $this->field, ':value1'))
            ->setParameter('value1', $value);
    }
}