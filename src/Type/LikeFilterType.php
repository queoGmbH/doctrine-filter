<?php

namespace Fludio\DoctrineFilter\Type;

use Fludio\DoctrineFilter\FilterBuilder;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LikeFilterType extends AbstractFilterType
{
    public function expand(FilterBuilder $filterBuilder, $value, $table, $field)
    {
        $qb = $filterBuilder->getQueryBuilder();

        $likeString = $this->getLikeString($value);

        return $qb
            ->andWhere(
                $qb->expr()->like($table . '.' . $field, $filterBuilder->placeValue($likeString))
            );
    }

    protected function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'field' => null,
            'start_with' => false,
            'end_with' => false
        ]);
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
