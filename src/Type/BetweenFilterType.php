<?php

namespace Fludio\DoctrineFilter\Type;

use Doctrine\Common\Collections\ArrayCollection;
use Fludio\DoctrineFilter\FilterBuilder;

class BetweenFilterType extends AbstractFilterType
{
    public function expand(FilterBuilder $filterBuilder, $value, $table, $field)
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
