<?php

namespace Queo\DoctrineFilter\Type;

use Doctrine\ORM\Query\Expr\Comparison;
use Doctrine\ORM\Version;
use Queo\DoctrineFilter\FilterBuilder;

class InstanceOfFilterType extends AbstractFilterType
{
    public function expand(FilterBuilder $filterBuilder, $value, $table, $field, $where)
    {
        $qb = $filterBuilder->getQueryBuilder();

        // We assume that the discriminator map name was given and no the FQCN
        if (!class_exists($value)) {
            $value = $filterBuilder->placeValue($value);
        }

        return $this->add($qb, $where, new Comparison($table, 'INSTANCE OF', $value));
    }
}
