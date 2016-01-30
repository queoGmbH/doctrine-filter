<?php

namespace Fludio\DoctrineFilter\Tests\Dummy\Entity;

use Doctrine\ORM\EntityRepository;
use Fludio\DoctrineFilter\Filter\FilterBuilder;
use Fludio\DoctrineFilter\Filter\FilterInterface;

class TransportRepo extends EntityRepository
{
    public function filter(FilterInterface $filter, $searchParams)
    {
        $qb = $this->createQueryBuilder('x');
        $filterBuilder = new FilterBuilder($qb);

        $filter->buildFilter($filterBuilder);

        return $filterBuilder->getResult($searchParams);
    }
}