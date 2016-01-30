<?php

namespace Fludio\DoctrineFilter\Filter\Type;

use Doctrine\ORM\QueryBuilder;

class LikeFilterType extends AbstractFilterType
{
    public function expand(QueryBuilder $qb, $value)
    {
        return $qb
            ->andWhere($qb->expr()->like('x.' . $this->field, ':value2'))
            ->setParameter('value2', '%' . $value . '%');
    }

}