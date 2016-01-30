<?php

namespace Fludio\DoctrineFilter\Filter\Type;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\QueryBuilder;


class RangeFilterType extends AbstractFilterType
{
    public function expand(QueryBuilder $qb, $value)
    {
    }

    public function addToFilters(ArrayCollection $filters)
    {
        $lowerBound = $this->field . '_since';
        $upperBound = $this->field . '_until';

        $filters->set($lowerBound, new GreaterThanEqualFilterType($this->field, $this->options));
        $filters->set($upperBound, new LessThanEqualFilterType($this->field, $this->options));
    }


}
