<?php

namespace BiteCodes\DoctrineFilter\Type;

use BiteCodes\DoctrineFilter\FilterBuilder;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EqualFilterType extends AbstractFilterType
{
    public function expand(FilterBuilder $filterBuilder, $value, $table, $field)
    {
        $qb = $filterBuilder->getQueryBuilder();
        $tableField = $table . '.' . $field;

        if (!$this->options['case_sensitive']) {
            $tableField = $qb->expr()->lower($tableField);
            $value = strtolower($value);
        }

        return $qb
            ->andWhere($qb->expr()->eq($tableField, $filterBuilder->placeValue($value)));
    }

    protected function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefault('case_sensitive', true);
    }
}
