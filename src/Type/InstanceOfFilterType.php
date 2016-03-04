<?php

namespace Fludio\DoctrineFilter\Type;

use Doctrine\ORM\Query\Expr\Comparison;
use Doctrine\ORM\Version;
use Fludio\DoctrineFilter\FilterBuilder;

class InstanceOfFilterType extends AbstractFilterType
{
    public function expand(FilterBuilder $filterBuilder, $value, $table, $field)
    {
        $qb = $filterBuilder->getQueryBuilder();

        if (Version::VERSION != '2.3.0' && !class_exists($value)) {
            $value = $filterBuilder->placeValue($value);
        }

        return $qb
            ->andWhere(new Comparison($table, 'INSTANCE OF', $value));
    }
}
