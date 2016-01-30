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
     * Keeps track of numeric placeholderand their values
     *
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

    /**
     * @param $searchParams
     * @return QueryBuilder
     */
    public function build($searchParams)
    {
        foreach ($searchParams as $filterName => $value) {
            /** @var AbstractFilterType $filter */
            $filter = $this->filters->get($filterName);
            $filter->expand($this, $value);
        }

        $this->qb->setParameters($this->parametersMap);

        return $this->qb;
    }

    /**
     * @param $searchParams
     * @return array
     */
    public function getResult($searchParams)
    {
        return $this
            ->build($searchParams)
            ->getQuery()
            ->getResult();
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
    public function placeValue($value)
    {
        $nextNumericPlaceholder = $this->getNextNumericPlaceholder();

        $this->parametersMap[$nextNumericPlaceholder] = $value;

        return '?' . $nextNumericPlaceholder;
    }

    /**
     * Return the next numeric placeholder for a parameter
     *
     * @return int
     */
    protected function getNextNumericPlaceholder()
    {
        return count($this->parametersMap) + 1;
    }
}