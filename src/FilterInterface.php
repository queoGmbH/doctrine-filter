<?php

namespace Fludio\DoctrineFilter;

interface FilterInterface
{
    public function buildFilter(FilterBuilder $builder);
}