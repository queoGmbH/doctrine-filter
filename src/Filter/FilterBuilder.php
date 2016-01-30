<?php

namespace Fludio\DoctrineFilter\Filter;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\QueryBuilder;
use Fludio\DoctrineFilter\Filter\Condition\AbstractCondition;

class FilterBuilder
{
    /**
     * @var QueryBuilder
     */
    private $qb;
    /**
     * @var ArrayCollection|AbstractType[]
     */
    protected $filters;

    /**
     * FilterBuilder constructor.
     * @param QueryBuilder $qb
     */
    public function __construct(QueryBuilder $qb)
    {
        $this->qb = $qb;
        $this->filters = new ArrayCollection();
    }

    /**
     * @param $field
     * @param $filterClass
     * @param $options
     * @return FilterBuilder $this
     */
    public function add($field, $filterClass, $options = [])
    {
        $filterName = isset($options['filterName']) ? $options['filterName'] : $field;

        /** @var AbstractCondition $filter */
        $filter = new $filterClass($field);
        $this->filters->set($filterName, $filter);

        return $this;
    }

    public function build($searchParams)
    {
        foreach ($searchParams as $filterName => $value) {
            /** @var AbstractCondition $filter */
            $filter = $this->filters->get($filterName);
            $filter->expand($this->qb, $value);
        }

        return $this->qb->getQuery()->getResult();
    }
}