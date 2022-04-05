<?php

namespace Queo\DoctrineFilter;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Expr\Andx;
use Doctrine\ORM\Query\Expr\Orx;
use Doctrine\ORM\QueryBuilder;
use Queo\DoctrineFilter\Type\ClosureFilterType;
use Queo\DoctrineFilter\Type\OrderByType;
use Queo\DoctrineFilter\Type\AbstractFilterType;

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
     * Holds registered filters types
     *
     * @var FilterRegistry|null
     */
    protected $filterRegistry;

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
     * @param FilterRegistry $filterRegistry
     */
    public function __construct(FilterRegistry $filterRegistry = null)
    {
        $this->filters = new ArrayCollection();
        $this->filterRegistry = $filterRegistry;
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
        $this->resetBuilder();
        $this->addFiltersToQuery($searchParams);
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
     * @param $filter
     * @param $options
     * @return FilterBuilder $this
     */
    public function add($filterName, $filter, $options = [])
    {
        if (is_callable($filter)) {
            $filter = new ClosureFilterType($filterName, $options, $filter);
        } else {
            /** @var AbstractFilterType $filter */
            $filter = new $filter($filterName, $options);
        }

        $filter->addToFilters($this->filters);

        return $this;
    }

    /**
     * Add a orderBy to the filter
     *
     * @param $filterName
     * @param null $defaultSortOrder
     * @param array $options
     * @return $this
     */
    public function orderBy($filterName, $defaultSortOrder = null, $options = [])
    {
        if (empty($options['sort_order'])) {
            $options['sort_order'] = $defaultSortOrder;
            $options['default'] = $defaultSortOrder;
        }

        $this->add($filterName, OrderByType::class, $options);

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
     * @param FilterRegistry $registry
     * @return $this
     */
    public function setRegistry(FilterRegistry $registry)
    {
        $this->filterRegistry = $registry;

        return $this;
    }

    /**
     * @return FilterRegistry
     */
    public function getRegistry()
    {
        return $this->filterRegistry;
    }

    /**
     * @param FilterInterface $filter
     * @return $this
     * @throws \Exception
     */
    public function setFilter($filter)
    {
        $this->filters = new ArrayCollection();

        if (!$filter instanceof FilterInterface) {
            if (!class_exists($filter)) {
                throw new \Exception(sprintf('Filter class "%s" does not exist. Did you forget a "use" statement for another namespace?', $filter));
            }

            $refl = new \ReflectionClass($filter);

            if (!$refl->implementsInterface(FilterInterface::class)) {
                throw new \Exception(sprintf('Filter has to implement %s', FilterInterface::class));
            }


            if ($this->filterRegistry && $this->filterRegistry->has($filter)) {
                $filter = $this->filterRegistry->get($filter);
            } else {
                $filter = new $filter;
            }
        }

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

    protected function resetBuilder()
    {
        $rootEntities = $this->qb->getRootEntities();
        $this->qb = $this->qb->getEntityManager()->getRepository($rootEntities[0])->createQueryBuilder('x');
        $this->joinAliases = [];
        $this->parametersMap = [];
        $this->orderBy = [];
    }

    /**
     * @param $searchParams
     */
    protected function addFiltersToQuery($searchParams)
    {
        /**
         * @var string $filterName
         * @var AbstractFilterType $filter
         */
        foreach ($this->filters as $filterName => $filter) {
            $filterIsCalled = isset($searchParams[$filterName]);

            if ($filter->hasDefault()
                || $filterIsCalled
            ) {
                if ($filter->hasDefault() && !$filter->allowDefaultOverride()) {
                    $value = $filter->getDefault();
                } elseif ($filterIsCalled) {
                    $value = $searchParams[$filterName];
                } elseif ($filter->hasDefault()) {
                    $value = $filter->getDefault();
                } else {
                    $value = null;
                }

                $this->addFilterToQuery($filterName, $value);
            }
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
        $fields = $this->getFieldsFromFilterName($filterName);
        /** @var AbstractFilterType $filter */
        $filter = $this->filters->get($filterName);
        $buildOr = count($fields) > 1 && !$filter->doMatchAll();

        $expressions = [];

        if($filter->isPartialMatch()) {
            $values = explode(' ', $value);
            $values = array_filter(array_map('trim', $values));
        } else {
            $values = [$value];
        }

        $buildMultiple = count($values) > 1;

        foreach ($values as $value) {
            $expressions = [];

            foreach ($fields as $field) {
                if ($this->isRelationship($field)) {
                    $table = $this->addAllJoins($field);
                    $field = $this->getRelationshipField($field, $table);
                } else {
                    $table = $this->getRootAlias();
                }

                $whereExpr = $buildOr ? Orx::class : Andx::class;

                $filter->expand($this, $value, $table, $field, $whereExpr);

                if ($buildOr || $buildMultiple) {
                    $expressions[] = $filter->getExpr();
                }
            }

            if($buildMultiple) {
                $orExpressions[] = $this->qb->expr()->orX()->addMultiple($expressions);
            }
        }

        if($buildMultiple) {
            $this->qb->andWhere(
                $this->qb->expr()->andX()->addMultiple($orExpressions)
            );
        } else if ($buildOr) {
            $this->qb->andWhere(
                $this->qb->expr()->orX()->addMultiple($expressions)
            );
        }
    }

    /**
     * Will add all necessary joins and it will
     * return the alias of the table for the field
     * to be queried
     *
     * @param $field
     * @return string
     */
    protected function addAllJoins($field)
    {
        $fieldParts = $this->splitOnDot($field);

        $joinAlias = $this->getRootAlias();
        while ($part = array_shift($fieldParts)) {
            if (!empty($fieldParts)) {
                $newAlias = $this->addJoin($part, $joinAlias);
                // If Embeddable, the new alias will be
                // the same as the previous join alias
                if ($newAlias == $joinAlias) {
                    break; // no more joins
                }

                $joinAlias = $newAlias;
            }
        }

        return $joinAlias;
    }

    /**
     * @param $field
     * @param null $alias
     * @return mixed|null
     */
    protected function addJoin($field, $alias)
    {
        if ($class = $this->getEntityClassFromAlias($alias)) {
            if ($this->isEmbeddable($field, $class)) {
                return $alias;
            }
        }

        $joinRelationship = $alias . '.' . $field; // x.tags

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
     * @param $field
     * @return bool
     */
    protected function isRelationship($field)
    {
        if ($this->isEmbeddable($field, $this->getRootEntity())) {
            return false;
        }

        $fieldParts = $this->splitOnDot($field);

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
     * @return array
     */
    protected function getFieldsFromFilterName($searchFilter)
    {
        $fields = $this->filters->get($searchFilter)->getFields();

        if (is_string($fields)) {
            $fields = [$fields];
        }

        return $fields;
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

        if (!property_exists($meta, 'embeddedClasses') || !$embeddedClasses = $meta->embeddedClasses) {
            return false;
        }

        $fieldParts = $this->splitOnDot($field);

        return isset($embeddedClasses[$fieldParts[0]]);
    }

    /**
     * @param $string
     * @param int $offset
     * @return array
     */
    protected function splitOnDot($string, $offset = 0)
    {
        $parts = preg_split('/\./', $string);

        return array_splice($parts, $offset, count($parts));
    }

    /**
     * Return entity class for an alias
     *
     * @param $alias
     * @return string
     */
    public function getEntityClassFromAlias($alias)
    {
        $meta = $this->getQueryBuilder()->getEntityManager()->getClassMetadata($this->getRootEntity());

        // It is not registered
        if (!$joinRelationship = array_search($alias, $this->joinAliases)) {
            return false;
        }

        $parts = $this->splitOnDot($joinRelationship, 1);

        foreach ($parts as $part) {
            $class = $meta->associationMappings[$part]['targetEntity'];
            $meta = $this->getQueryBuilder()->getEntityManager()->getClassMetadata($class);
        }

        return !empty($class) ? $class : '';
    }

    /**
     * Will return the field to query for a relationship
     * Takes care of embeddables
     *
     * @param $field
     * @param $table
     * @return mixed
     */
    protected function getRelationshipField($field, $table)
    {
        $joinRelationship = array_search($table, $this->joinAliases);

        $fieldParts = $this->splitOnDot($joinRelationship);
        $fieldStart = array_pop($fieldParts);
        // Keep dot to ensure it only matches full names
        return substr($field, strpos($field, $fieldStart . '.') + strlen($fieldStart) + 1, strlen($field));
    }
}
