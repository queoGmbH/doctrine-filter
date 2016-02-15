<?php

namespace Fludio\DoctrineFilter\Type;

use Fludio\DoctrineFilter\FilterBuilder;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderByType extends AbstractFilterType
{
    protected $doesAlwaysRun = true;

    protected $fields;

    public function expand(FilterBuilder $filterBuilder, $value, $table, $field)
    {
        if ($this->runOnlyWithParam($value)) {
            return;
        }

        $value = !empty($this->options['sort_order']) ? $this->options['sort_order'] : $value;

        $qb = $filterBuilder->getQueryBuilder();

        return $qb
            ->orderBy($table . '.' . $field, $value);
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