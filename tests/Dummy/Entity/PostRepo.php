<?php

namespace Fludio\DoctrineFilter\Tests\Dummy\Entity;

use Doctrine\ORM\EntityRepository;
use Fludio\DoctrineFilter\Filter\AbstractFilter;
use Fludio\DoctrineFilter\Filter\FilterBuilder;
use Fludio\DoctrineFilter\Filter\OnSteroids;

class PostRepo extends EntityRepository
{
    public function filter($searchParams, AbstractFilter $filter)
    {
        $qb = $this->createQueryBuilder('x');
        $filterBuilder = new FilterBuilder($qb);

        $filter->buildFilter($filterBuilder);

        return $filterBuilder->build($searchParams);
    }
}