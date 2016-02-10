<?php

namespace Fludio\DoctrineFilter\Type;

use Doctrine\Common\Collections\ArrayCollection;
use Fludio\DoctrineFilter\FilterBuilder;

class ComparableFilterType extends AbstractFilterType
{
    public function expand(FilterBuilder $filterBuilder, $value, $table, $field)
    {
    }

    /**
     * @param ArrayCollection $filters
     */
    public function addToFilters(ArrayCollection $filters)
    {
        $filters->set($this->field . '<', new LessThanEqualFilterType($this->field, $this->options));
        $filters->set($this->field . '>', new GreaterThanEqualFilterType($this->field, $this->options));
        $filters->set($this->field . '!', new NotEqualFilterType($this->field, $this->options));
    }
}