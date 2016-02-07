<?php

namespace Fludio\DoctrineFilter\Traits;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Fludio\DoctrineFilter\FilterBuilder;
use Fludio\DoctrineFilter\FilterInterface;

trait EntityFilterTrait
{
    /**
     * @param FilterInterface $filter
     * @param $searchParams
     * @return array
     */
    public function filter(FilterInterface $filter, $searchParams)
    {
        /** @var QueryBuilder $qb */
        $qb = $this->createQueryBuilder('x');

        return FilterBuilder::create()
            ->setQueryBuilder($qb)
            ->setFilter($filter)
            ->getResult($searchParams);
    }

    /**
     * @param FilterInterface $filter
     * @param $searchParams
     * @param $offset
     * @param $limit
     * @return array
     */
    public function paginate(FilterInterface $filter, $searchParams, $offset, $limit)
    {
        /** @var QueryBuilder $qb */
        $qb = $this->createQueryBuilder('x');

        $qb = FilterBuilder::create()
            ->setQueryBuilder($qb)
            ->setFilter($filter)
            ->buildQuery($searchParams)
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery();

        $paginator = new Paginator($qb);

        return iterator_to_array($paginator->getIterator());
    }
}