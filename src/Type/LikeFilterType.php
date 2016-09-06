<?php

namespace BiteCodes\DoctrineFilter\Type;

use BiteCodes\DoctrineFilter\FilterBuilder;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LikeFilterType extends AbstractFilterType
{
    public function expand(FilterBuilder $filterBuilder, $value, $table, $field, $where)
    {
        $qb = $filterBuilder->getQueryBuilder();

        $likeString = $this->getLikeString($value);

        return $this->add($qb, $where, $qb->expr()->like($table . '.' . $field, $filterBuilder->placeValue($likeString)));
    }

    protected function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefault('start_with', false);
        $resolver->setDefault('end_with', false);
    }

    /**
     * @param $value
     * @return string
     */
    protected function getLikeString($value)
    {
        $likeString = '';
        $likeString .= !$this->options['start_with'] ? '%' : '';
        $likeString .= $value;
        $likeString .= !$this->options['end_with'] ? '%' : '';

        return $likeString;
    }


}
