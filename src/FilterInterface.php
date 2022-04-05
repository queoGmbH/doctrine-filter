<?php

namespace Queo\DoctrineFilter;

interface FilterInterface
{
    public function buildFilter(FilterBuilder $builder);
}