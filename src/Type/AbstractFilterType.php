<?php

namespace BiteCodes\DoctrineFilter\Type;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\QueryBuilder;
use BiteCodes\DoctrineFilter\FilterBuilder;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractFilterType
{
    /**
     * @var string
     */
    protected $name;
    /**
     * @var string
     */
    protected $fields;
    /**
     * @var mixed
     */
    protected $default;
    /**
     * @var boolean
     */
    protected $allowDefaultOverride;
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
        $this->options = $this->getResolvedOptions($options);
        $this->fields = isset($this->options['fields']) ? $this->options['fields'] : $name;
        $this->default = $this->options['default'];
        $this->allowDefaultOverride = $this->options['default_override'];
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
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @return bool
     */
    public function doesAlwaysRun()
    {
        return $this->doesAlwaysRun;
    }

    /**
     * @return bool
     */
    public function hasDefault()
    {
        return !is_null($this->default);
    }

    /**
     * @return bool|mixed
     */
    public function allowDefaultOverride()
    {
        return $this->allowDefaultOverride;
    }

    /**
     * @return mixed
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * @param OptionsResolver $resolver
     */
    protected function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'default' => null,
            'default_override' => false,
            'fields' => null
        ]);
    }

    /**
     * @param array $options
     * @return array
     */
    private function getResolvedOptions(array $options)
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);
        return $resolver->resolve($options);
    }
}