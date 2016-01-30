<?php

namespace Fludio\DoctrineFilter\Filter\Traits;

use Fludio\DoctrineFilter\Filter\FilterBuilder;
use Fludio\DoctrineFilter\Filter\FilterInterface;

trait EntityFilterTrait
{
    public function filter(FilterInterface $filter, $searchParams)
    {
        $qb = $this->createQueryBuilder('x');
        $filterBuilder = new FilterBuilder($qb);

        $filter->buildFilter($filterBuilder);

        return $filterBuilder->getResult($searchParams);
    }
}