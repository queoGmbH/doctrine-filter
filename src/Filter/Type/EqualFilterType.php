<?php

namespace Fludio\DoctrineFilter\Filter\Type;

use Fludio\DoctrineFilter\Filter\FilterBuilder;

class EqualFilterType extends AbstractFilterType
{
    public function expand(FilterBuilder $filterBuilder, $value, $table)
    {
        $qb = $filterBuilder->getQueryBuilder();

        return $qb
            ->andWhere($qb->expr()->eq($table . '.' . $this->fieldOnTable(), $filterBuilder->placeValue($value)));
    }

    /**
     * @return string
     */
    protected function fieldOnTable()
    {
        $fields = preg_split('/\./', $this->field);
        return array_pop($fields);
    }
}