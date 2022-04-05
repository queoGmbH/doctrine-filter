<?php

namespace Queo\DoctrineFilter\Type;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Expr\Comparison;
use Doctrine\Common\Collections\Expr\Expression;
use Doctrine\ORM\Query\Expr\Andx;
use Doctrine\ORM\Query\Expr\Base;
use Doctrine\ORM\Query\Expr\Orx;
use Doctrine\ORM\QueryBuilder;
use Queo\DoctrineFilter\FilterBuilder;
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
    /**
     * @var Expression|null
     */
    protected $expr;


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
     * @param $where
     * @return QueryBuilder
     */
    abstract public function expand(FilterBuilder $filterBuilder, $value, $table, $field, $where);

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
     * @return Expression|null
     */
    public function getExpr()
    {
        return $this->expr;
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
    public function doMatchAll()
    {
        return $this->options['match_all_fields'];
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
     * @return mixed
     */
    public function isPartialMatch()
    {
        return $this->options['partial_match'];
    }

    /**
     * @param OptionsResolver $resolver
     */
    protected function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'default' => null,
            'default_override' => false,
            'fields' => null,
            'match_all_fields' => false,
            'partial_match' => false
        ]);
    }

    /**
     * @param QueryBuilder $qb
     * @param $where
     * @param $expr
     * @return QueryBuilder
     * @throws \Exception
     */
    protected function add(QueryBuilder $qb, $where, $expr)
    {
        switch ($where) {
            case Andx::class:
                $qb->andWhere($expr);
                break;
            case Orx::class:
                $this->expr = $expr;
                break;
            default:
                throw new \Exception('Invalid $where');
        }

        return $qb;
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