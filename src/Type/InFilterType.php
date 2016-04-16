<?php

namespace BiteCodes\DoctrineFilter\Type;

use BiteCodes\DoctrineFilter\FilterBuilder;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InFilterType extends AbstractFilterType
{
    public function expand(FilterBuilder $filterBuilder, $value, $table, $field)
    {
        if (empty($value)) {
            return $filterBuilder->getQueryBuilder();
        }

        if ($this->options['match_all']) {
            $qb = $this->buildQueryToMatchAll($filterBuilder, $value, $table, $field);
        } else {
            $qb = $this->buildQuery($filterBuilder, $value, $table, $field);
        }

        return $qb;
    }

    protected function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefault('match_all', false);
    }

    /**
     * Standard where in filter
     *
     * @param FilterBuilder $filterBuilder
     * @param $value
     * @param $table
     * @param $field
     * @return \Doctrine\ORM\QueryBuilder
     */
    private function buildQuery(FilterBuilder $filterBuilder, $value, $table, $field)
    {
        $qb = $filterBuilder->getQueryBuilder();

        return $qb
            ->andWhere($qb->expr()->in($table . '.' . $field, $filterBuilder->placeValue($value)));
    }

    /**
     * Where in filter where all values have to match
     *
     * @param FilterBuilder $filterBuilder
     * @param $value
     * @param $table
     * @param $field
     * @return \Doctrine\ORM\QueryBuilder
     */
    private function buildQueryToMatchAll(FilterBuilder $filterBuilder, $value, $table, $field)
    {
        $qb = $filterBuilder->getQueryBuilder();

        $count = count(array_unique($value));

        return $qb
            ->andWhere(
                $qb->expr()->in($table . '.' . $field, $filterBuilder->placeValue($value))
            )
            ->groupBy($qb->getRootAliases()[0])
            ->andHaving(
                $qb->expr()->eq(
                    $qb->expr()->countDistinct($table),
                    $count
                )
            );
    }
}
