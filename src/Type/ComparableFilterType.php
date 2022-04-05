<?php

namespace Queo\DoctrineFilter\Type;

use Doctrine\Common\Collections\ArrayCollection;
use Queo\DoctrineFilter\FilterBuilder;

class ComparableFilterType extends AbstractFilterType
{
    public function expand(FilterBuilder $filterBuilder, $value, $table, $field, $where)
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