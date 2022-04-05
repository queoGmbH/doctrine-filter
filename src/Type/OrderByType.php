<?php

namespace Queo\DoctrineFilter\Type;

use Queo\DoctrineFilter\FilterBuilder;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderByType extends AbstractFilterType
{
    protected $fields;

    public function expand(FilterBuilder $filterBuilder, $value, $table, $field, $where)
    {
        // Ignore invalid inputs
        if (!in_array(strtolower($value), ['asc', 'desc', null])) {
            $value = null;
        }

        if ($this->runOnlyWithParam($value)) {
            return;
        }

        $value = !empty($this->options['sort_order']) ? $this->options['sort_order'] : $value;

        $qb = $filterBuilder->getQueryBuilder();

        return $qb
            ->addOrderBy($table . '.' . $field, $value);
    }

    protected function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefault('sort_order', null);
        $resolver->setDefault('only_with_param', null);
    }

    /**
     * @param $value
     * @return bool
     */
    protected function runOnlyWithParam($value)
    {
        return !empty($this->options['only_with_param']) && is_null($value);
    }
}