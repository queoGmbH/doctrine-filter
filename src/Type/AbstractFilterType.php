<?php

namespace Fludio\DoctrineFilter\Type;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\QueryBuilder;
use Fludio\DoctrineFilter\FilterBuilder;

abstract class AbstractFilterType
{
    /**
     * @var string
     */
    protected $name;
    /**
     * @var string
     */
    protected $field;
    /**
     * @var array
     */
    protected $options;
    /**
     * Should the filter run even if not in search params?
     *
     * @var bool
     */
    protected $doesAlwaysRun = false;

    public function __construct($name, array $options)
    {
        $this->name = $name;
        $this->options = $options;
        $this->field = isset($options['field']) ? $options['field'] : $name;
    }

    /**
     * @param FilterBuilder $filterBuilder
     * @param $value
     * @param $table
     * @param $field
     * @return QueryBuilder
     */
    abstract public function expand(FilterBuilder $filterBuilder, $value, $table, $field);

    /**
     * @param ArrayCollection $filters
     */
    public function addToFilters(ArrayCollection $filters)
    {
        $filters->set($this->name, $this);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @return string
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * @return bool
     */
    public function doesAlwaysRun()
    {
        return $this->doesAlwaysRun;
    }
}