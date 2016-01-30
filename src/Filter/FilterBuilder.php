<?php

namespace Fludio\DoctrineFilter\Filter;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\QueryBuilder;
use Fludio\DoctrineFilter\Filter\Type\AbstractFilterType;

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
        /** @var AbstractFilterType $filter */
        $filter = new $filterClass($field, $options);
        $filter->addToFilters($this->filters);

        return $this;
    }

    public function build($searchParams)
    {
        foreach ($searchParams as $filterName => $value) {
            /** @var AbstractFilterType $filter */
            $filter = $this->filters->get($filterName);
            $filter->expand($this->qb, $value);
        }

        return $this->qb->getQuery()->getResult();
    }
}