<?php

namespace Fludio\DoctrineFilter\OrderBy;

use Fludio\DoctrineFilter\FilterBuilder;
use Fludio\DoctrineFilter\Type\AbstractFilterType;

class OrderByType extends AbstractFilterType
{
    protected $doesAlwaysRun = true;

    protected $field;

    public function expand(FilterBuilder $filterBuilder, $value, $table, $field)
    {
        if (!empty($this->options['only_on_call'])) {
            if (is_null($value)) {
                return;
            }
        }

        if (!empty($this->options['sortOrder'])) {
            $value = $this->options['sortOrder'];
        }

        $qb = $filterBuilder->getQueryBuilder();

        return $qb
            ->orderBy($table . '.' . $field, $value);
    }
}