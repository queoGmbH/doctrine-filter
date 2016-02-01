<?php

namespace Fludio\DoctrineFilter;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\QueryBuilder;
use Fludio\DoctrineFilter\OrderBy\OrderByType;
use Fludio\DoctrineFilter\Type\AbstractFilterType;

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
     * The registered orderBy statements
     *
     * @var array
     */
    protected $orderBy = [];

    /**
     * Keeps track of numeric placeholder and their values
     *
     * @var array
     */
    protected $parametersMap = [];

    /**
     * Keeps track of the registered joins
     *
     * @var array
     */
    protected $joinAliases = [];

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
     * Get QueryBuilder after search filters are attached to query
     *
     * @param $searchParams
     * @return QueryBuilder
     */
    public function build($searchParams)
    {
        $this->addFiltersToQuery($searchParams);
        $this->addOrderByToQuery();
        $this->setParametersToQuery();

        return $this->qb;
    }

    /**
     * Get search filter result
     *
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
     * Add a filter to the builder
     *
     * @param $filterName
     * @param $filterClass
     * @param $options
     * @return FilterBuilder $this
     */
    public function add($filterName, $filterClass, $options = [])
    {
        /** @var AbstractFilterType $filter */
        $filter = new $filterClass($filterName, $options);
        $filter->addToFilters($this->filters);

        return $this;
    }

    /**
     * Add a orderBy to the filter
     *
     * @param $field
     * @param $sortOrder
     * @return $this
     */
    public function orderBy($field, $sortOrder)
    {
        $filter = new OrderByType($field, $sortOrder);
        $this->orderBy[] = $filter;

        return $this;
    }

    /**
     * Returns the QueryBuilder
     *
     * @return QueryBuilder
     */
    public function getQueryBuilder()
    {
        return $this->qb;
    }

    /**
     * Will return a string for the numeric placeholder
     * and add the value to the parameter map
     *
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
     * @param $searchParams
     */
    protected function addFiltersToQuery($searchParams)
    {
        foreach ($searchParams as $filterName => $value) {
            if ($this->filters->containsKey($filterName)) {
                $this->addFilterToQuery($filterName, $value);
            }
        }
    }

    /**
     * Add OrderBy statements
     */
    protected function addOrderByToQuery()
    {
        foreach ($this->orderBy as $orderBy) {
            $orderBy->expand($this);
        }
    }

    /**
     * Set Parameters to Query
     */
    protected function setParametersToQuery()
    {
        $this->qb->setParameters($this->parametersMap);
    }

    /**
     * @param $filterName
     * @param $value
     */
    protected function addFilterToQuery($filterName, $value)
    {
        if ($this->isRelationship($filterName)) {
            $table = $this->addAllJoins($filterName);
        } else {
            $table = $this->getRootAlias();
        }
        /** @var AbstractFilterType $filter */
        $filter = $this->filters->get($filterName);
        $filter->expand($this, $value, $table);
    }

    /**
     * Will add all necessary joins and it will
     * return the name of the table for the field
     * to be queried
     *
     * @param $searchFilter
     * @return string
     */
    protected function addAllJoins($searchFilter)
    {
        $field = $this->getFieldFromFilterName($searchFilter);
        $fieldParts = preg_split('/\./', $field);

        $joinAlias = $this->getRootAlias();
        while ($part = array_shift($fieldParts)) {
            if (!empty($fieldParts)) {
                $joinAlias = $this->addJoin($part, $joinAlias);
            }
        }

        return $joinAlias;
    }

    /**
     * @param $field
     * @param null $rootAlias
     * @return mixed|null
     */
    protected function addJoin($field, $rootAlias = null)
    {
        $joinRelationship = $rootAlias . '.' . $field; // x.tags

        if (!isset($this->joinAliases[$joinRelationship])) {
            $joinAlias = 'join' . $this->getNextNumericJoinCount(); // join1
            $this->joinAliases[$joinRelationship] = $joinAlias; // [x.tags => join1]
            $this->qb
                ->leftJoin($joinRelationship, $joinAlias);
        } else {
            $joinAlias = $this->joinAliases[$joinRelationship];
        }

        return $joinAlias;
    }

    /**
     * @param $searchFilter
     * @return bool
     */
    protected function isRelationship($searchFilter)
    {
        $field = $this->getFieldFromFilterName($searchFilter);

        $fieldParts = preg_split('/\./', $field);

        return count($fieldParts) > 1;
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

    /**
     * Return the next numeric join count
     *
     * @return int
     */
    protected function getNextNumericJoinCount()
    {
        return count($this->joinAliases) + 1;
    }

    /**
     * @param $searchFilter
     * @return mixed
     */
    protected function getFieldFromFilterName($searchFilter)
    {
        $filter = $this->filters->get($searchFilter);

        return $filter->getField();
    }

    /**
     * @return string
     */
    protected function getRootAlias()
    {
        $rootAlias = $this->qb->getRootAliases();
        return array_shift($rootAlias);
    }
}
