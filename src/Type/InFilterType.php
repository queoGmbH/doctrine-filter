<?php

namespace BiteCodes\DoctrineFilter\Type;

use BiteCodes\DoctrineFilter\FilterBuilder;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InFilterType extends AbstractFilterType
{
    public function expand(FilterBuilder $filterBuilder, $value, $table, $field, $where)
    {
        if (empty($value) && ($value === [] && $this->options['allow_empty'])) {
            return $filterBuilder->getQueryBuilder();
        }

        if ($this->options['match_all']) {
            $qb = $this->buildQueryToMatchAll($filterBuilder, $value, $table, $field, $where);
        } else {
            $qb = $this->buildQuery($filterBuilder, $value, $table, $field, $where);
        }

        return $qb;
    }

    protected function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefault('match_all', false);
        $resolver->setDefault('allow_empty', true);
    }

    /**
     * Standard where in filter
     *
     * @param FilterBuilder $filterBuilder
     * @param $value
     * @param $table
     * @param $field
     * @param $where
     * @return \Doctrine\ORM\QueryBuilder
     */
    private function buildQuery(FilterBuilder $filterBuilder, $value, $table, $field, $where)
    {
        $qb = $filterBuilder->getQueryBuilder();

        return $this->add($qb, $where, $qb->expr()->in($table . '.' . $field, $filterBuilder->placeValue($value)));
    }

    /**
     * Where in filter where all values have to match
     *
     * @param FilterBuilder $filterBuilder
     * @param $value
     * @param $table
     * @param $field
     * @param $where
     * @return \Doctrine\ORM\QueryBuilder
     */
    private function buildQueryToMatchAll(FilterBuilder $filterBuilder, $value, $table, $field, $where)
    {
        $qb = $filterBuilder->getQueryBuilder();

        $count = count(array_unique($value));

        return $this->add($qb, $where, $qb->expr()->in($table . '.' . $field, $filterBuilder->placeValue($value)))
            ->groupBy($qb->getRootAliases()[0])
            ->andHaving(
                $qb->expr()->eq(
                    $qb->expr()->countDistinct($table),
                    $count
                )
            );
    }
}
