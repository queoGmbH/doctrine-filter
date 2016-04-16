<?php

namespace BiteCodes\DoctrineFilter;

interface FilterInterface
{
    public function buildFilter(FilterBuilder $builder);
}