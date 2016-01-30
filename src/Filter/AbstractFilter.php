<?php

namespace Fludio\DoctrineFilter\Filter;

abstract class AbstractFilter
{
    abstract public function buildFilter(FilterBuilder $builder);
}