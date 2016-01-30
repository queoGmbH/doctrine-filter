<?php

namespace Fludio\DoctrineFilter\Filter\Type;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\QueryBuilder;
use Fludio\DoctrineFilter\Filter\FilterBuilder;

abstract class AbstractFilterType
{
    /**
     * @var string
     */
    protected $field;
    /**
     * @var array
     */
    protected $options;

    public function __construct($field, array $options)
    {
        $this->field = $field;
        $this->options = $options;
    }

    /**
     * @param FilterBuilder $filterBuilder
     * @param $value
     * @return QueryBuilder
     * @internal param QueryBuilder $qb
     */
    abstract public function expand(FilterBuilder $filterBuilder, $value);

    /**
     * @param ArrayCollection $filters
     */
    public function addToFilters(ArrayCollection $filters)
    {
        $filterName = isset($this->options['filterName']) ? $this->options['filterName'] : $this->field;

        $filters->set($filterName, $this);
    }
}