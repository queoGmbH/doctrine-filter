<?php

namespace Fludio\DoctrineFilter\Traits;

use Doctrine\ORM\QueryBuilder;
use Fludio\DoctrineFilter\FilterBuilder;
use Fludio\DoctrineFilter\FilterInterface;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;

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
     * @param $page
     * @param $maxPerPage
     * @param Pagerfanta $pagerfanta
     * @return array
     */
    public function paginate(FilterInterface $filter, $searchParams, $page, $maxPerPage, &$pagerfanta = null)
    {
        /** @var QueryBuilder $qb */
        $qb = $this->createQueryBuilder('x');

        $query = FilterBuilder::create()
            ->setQueryBuilder($qb)
            ->setFilter($filter)
            ->buildQuery($searchParams)
            ->getQuery();

        $adapter = new DoctrineORMAdapter($query);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta
            ->setAllowOutOfRangePages(true)
            ->setMaxPerPage($maxPerPage)
            ->setCurrentPage($page);

        return iterator_to_array($pagerfanta->getCurrentPageResults());
    }
}