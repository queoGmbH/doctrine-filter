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
     */
    public function __construct()
    {
        $this->filters = new ArrayCollection();
    }

    public static function create()
    {
        return new self;
    }

    /**
     * Get QueryBuilder after search filters are attached to query
     *
     * @param $searchParams
     * @return QueryBuilder
     */
    public function buildQuery($searchParams)
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
            ->buildQuery($searchParams)
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
     * @return array|AbstractFilterType[]
     */
    public function getFilters()
    {
        return $this->filters->toArray();
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
     * @param QueryBuilder $qb
     * @return FilterBuilder
     */
    public function setQueryBuilder($qb)
    {
        $this->qb = $qb;

        return $this;
    }

    /**
     * @param FilterInterface $filter
     * @return $this
     */
    public function setFilter(FilterInterface $filter)
    {
        $filter->buildFilter($this);

        return $this;
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
        $field = $this->getFieldFromFilterName($filterName);

        if ($this->isEmbeddable($field, $this->getRootEntity())) {
            $table = $this->getRootAlias();
        } elseif ($this->isRelationship($filterName)) {
            $table = $this->addAllJoins($filterName);
            $field = $this->getRelationshipField($field, $table);
        } else {
            $table = $this->getRootAlias();
        }
        /** @var AbstractFilterType $filter */
        $filter = $this->filters->get($filterName);
        $filter->expand($this, $value, $table, $field);
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
        $fieldParts = $this->getFieldParts($field);

        $joinAlias = $this->getRootAlias();
        while ($part = array_shift($fieldParts)) {
            if (!empty($fieldParts)) {
                $newAlias = $this->addJoin($part, $joinAlias);
                if ($newAlias == $joinAlias) {
                    return $joinAlias;
                } else {
                    $joinAlias = $newAlias;
                }
            }
        }

        return $joinAlias;
    }

    /**
     * @param $field
     * @param null $rootAlias
     * @return mixed|null
     */
    protected function addJoin($field, $rootAlias)
    {
        if ($class = $this->getEntityClass($rootAlias)) {
            if ($this->isEmbeddable($field, $class)) {
                return $rootAlias;
            }
        }

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

        $fieldParts = $this->getFieldParts($field);

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

    /**
     * @return mixed
     */
    protected function getRootEntity()
    {
        $roots = $this->getQueryBuilder()->getRootEntities();
        return array_shift($roots);
    }

    /**
     * @param $field
     * @param $entity
     * @return bool
     */
    protected function isEmbeddable($field, $entity)
    {
        $meta = $this->getQueryBuilder()->getEntityManager()->getClassMetadata($entity);

        if (property_exists($meta, 'embeddedClasses') && $embeddedClass = $meta->embeddedClasses) {
            $fieldParts = $this->getFieldParts($field);
            if (isset($embeddedClass[$fieldParts[0]])) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param $field
     * @return array
     */
    protected function getFieldParts($field)
    {
        return preg_split('/\./', $field);
    }

    protected function getEntityClass($rootAlias)
    {
        $meta = $this->getQueryBuilder()->getEntityManager()->getClassMetadata($this->getRootEntity());

        if (!$joinRelationship = array_search($rootAlias, $this->joinAliases)) {
            return false;
        }

        $parts = $this->getFieldParts($joinRelationship);
        array_shift($parts);

        foreach ($parts as $part) {
            $class = $meta->associationMappings[$part]['targetEntity'];
            $meta = $this->getQueryBuilder()->getEntityManager()->getClassMetadata($class);
        }

        if (!empty($class)) {
            return $class;
        } else {
            return false;
        }
    }

    /**
     * @param $field
     * @param $table
     * @return mixed
     */
    protected function getRelationshipField($field, $table)
    {
        $joinRelationship = array_search($table, $this->joinAliases);

        $fieldParts = $this->getFieldParts($joinRelationship);
        $fieldStart = array_pop($fieldParts);
        return substr($field, strpos($field, $fieldStart) + strlen($fieldStart) + 1, strlen($field));
    }
}
