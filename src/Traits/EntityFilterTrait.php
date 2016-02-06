<?php

namespace Fludio\DoctrineFilter\Traits;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Fludio\DoctrineFilter\FilterBuilder;
use Fludio\DoctrineFilter\FilterInterface;

trait EntityFilterTrait
{
    public function filter(FilterInterface $filter, $searchParams)
    {
        $qb = $this->createQueryBuilder('x');
        $filterBuilder = new FilterBuilder();
        $filterBuilder->setQueryBuilder($qb);

        $filter->buildFilter($filterBuilder);

        return $filterBuilder->getResult($searchParams);
    }

    public function paginate(FilterInterface $filter, $searchParams, $offset, $limit)
    {
        /** @var QueryBuilder $qb */
        $qb = $this->createQueryBuilder('x');
        $filterBuilder = new FilterBuilder();
        $filterBuilder->setQueryBuilder($qb);

        $filter->buildFilter($filterBuilder);

        $filterBuilder->build($searchParams);

        $qb
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery();

        $paginator = new Paginator($qb);

        return new ArrayCollection(iterator_to_array($paginator->getIterator()));
    }
}