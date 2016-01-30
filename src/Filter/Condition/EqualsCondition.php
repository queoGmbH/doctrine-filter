<?php

namespace Fludio\DoctrineFilter\Filter\Condition;

use Doctrine\ORM\QueryBuilder;

class EqualsCondition extends AbstractCondition
{
    public function expand(QueryBuilder $qb, $value)
    {
        return $qb
            ->andWhere($qb->expr()->eq('x.' . $this->field, ':value1'))
            ->setParameter('value1', $value);
    }
}