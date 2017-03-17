<?php

namespace BiteCodes\DoctrineFilter\Type;

use BiteCodes\DoctrineFilter\FilterBuilder;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BooleanFilterType extends AbstractFilterType
{
    public function expand(FilterBuilder $filterBuilder, $value, $table, $field, $where)
    {
        $qb = $filterBuilder->getQueryBuilder();
        $tableField = $table . '.' . $field;

        if (in_array($value, $this->options['truthy_values'])) {
            $value = 1;
        } elseif (in_array($value, $this->options['falsy_values'])) {
            $value = 0;
        } else {
            return;
        }

        return $this->add($qb, $where, $qb->expr()->eq($tableField, $filterBuilder->placeValue($value)));
    }

    protected function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
            'truthy_values' => [1],
            'falsy_values'  => [0],
        ]);
    }
}