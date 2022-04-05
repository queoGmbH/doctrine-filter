<?php

namespace Queo\DoctrineFilter\Traits;

use Doctrine\ORM\QueryBuilder;
use Queo\DoctrineFilter\FilterBuilder;
use Queo\DoctrineFilter\FilterInterface;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;

trait EntityFilterTrait
{
    /**
     * @param $filter
     * @param $searchParams
     * @return array
     */
    public function filter($filter, $searchParams = [])
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
    public function paginate($filter, $searchParams, $page, $maxPerPage, &$pagerfanta = null)
    {
        /** @var QueryBuilder $qb */
        $qb = $this->createQueryBuilder('x');

        $query = FilterBuilder::create()
            ->setQueryBuilder($qb)
            ->setFilter($filter)
            ->buildQuery($searchParams)
            ->getQuery();

        $adapter = new DoctrineORMAdapter($query, true, false);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta
            ->setAllowOutOfRangePages(true)
            ->setMaxPerPage($maxPerPage)
            ->setCurrentPage($page);

        return iterator_to_array($pagerfanta->getCurrentPageResults());
    }
}