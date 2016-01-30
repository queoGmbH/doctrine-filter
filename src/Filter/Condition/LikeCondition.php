<?php

namespace Fludio\DoctrineFilter\Filter\Condition;

use Doctrine\ORM\QueryBuilder;

class LikeCondition extends AbstractCondition
{
    public function expand(QueryBuilder $qb, $value)
    {
        return $qb
            ->andWhere($qb->expr()->like('x.' . $this->field, ':value2'))
            ->setParameter('value2', '%' . $value . '%');
    }

}