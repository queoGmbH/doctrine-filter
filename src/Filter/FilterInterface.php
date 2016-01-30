<?php

namespace Fludio\DoctrineFilter\Filter;

interface FilterInterface
{
    public function buildFilter(FilterBuilder $builder);
}