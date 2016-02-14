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
        if ($this->runOnlyWithParam($value)) {
            return;
        }

        $value = !empty($this->options['sortOrder']) ? $this->options['sortOrder'] : $value;

        $qb = $filterBuilder->getQueryBuilder();

        return $qb
            ->orderBy($table . '.' . $field, $value);
    }

    /**
     * @param $value
     * @return bool
     */
    protected function runOnlyWithParam($value)
    {
        return !empty($this->options['only_on_call']) && is_null($value);
    }
}