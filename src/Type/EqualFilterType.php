<?php

namespace Queo\DoctrineFilter\Type;

use Queo\DoctrineFilter\FilterBuilder;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EqualFilterType extends AbstractFilterType
{
    public function expand(FilterBuilder $filterBuilder, $value, $table, $field, $where)
    {
        $qb = $filterBuilder->getQueryBuilder();
        $tableField = $table . '.' . $field;

        if (!$this->options['case_sensitive']) {
            $tableField = $qb->expr()->lower($tableField);
            $value = strtolower($value);
        }

        // Doctrine 2.3 can not handle boolean values
        if (is_bool($value)) {
            $value = $value ? 1 : 0;
        }

        return $this->add($qb, $where, $qb->expr()->eq($tableField, $filterBuilder->placeValue($value)));
    }

    protected function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefault('case_sensitive', true);
    }
}
