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
    protected $qb;
    /**
     * @var ArrayCollection|AbstractType[]
     */
    protected $filters;
    /**
     * @var array
     */
    protected $parametersMap = [];

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
            $filter->expand($this, $value);
        }

        $this->qb->setParameters($this->parametersMap);

        return $this->qb->getQuery()->getResult();
    }

    /**
     * @return QueryBuilder
     */
    public function getQueryBuilder()
    {
        return $this->qb;
    }

    /**
     * @param $value
     * @return string
     */
    public function addValue($value)
    {
        $nextNumericPlaceholder = $this->getNextNumericPlaceholder();

        $this->parametersMap[$nextNumericPlaceholder] = $value;

        return '?' . $nextNumericPlaceholder;
    }

    protected function getNextNumericPlaceholder()
    {
        return count($this->parametersMap) + 1;
    }
}