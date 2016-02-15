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
        $filters->set($this->fields . '<', new LessThanEqualFilterType($this->fields, $this->options));
        $filters->set($this->fields . '>', new GreaterThanEqualFilterType($this->fields, $this->options));
        $filters->set($this->fields . '!', new NotEqualFilterType($this->fields, $this->options));
    }
}