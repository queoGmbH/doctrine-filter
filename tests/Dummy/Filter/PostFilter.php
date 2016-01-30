<?php

namespace Fludio\DoctrineFilter\Tests\Dummy\Filter;

use Fludio\DoctrineFilter\Filter\AbstractFilter;
use Fludio\DoctrineFilter\Filter\Type\EqualFilterType;
use Fludio\DoctrineFilter\Filter\Type\GreaterThanEqualFilterType;
use Fludio\DoctrineFilter\Filter\Type\GreaterThanFilterType;
use Fludio\DoctrineFilter\Filter\Type\LessThanEqualFilterType;
use Fludio\DoctrineFilter\Filter\Type\LessThanFilterType;
use Fludio\DoctrineFilter\Filter\Type\LikeFilterType;
use Fludio\DoctrineFilter\Filter\FilterBuilder;
use Fludio\DoctrineFilter\Filter\FilterInterface;
use Fludio\DoctrineFilter\Filter\Type\RangeFilterType;

class PostFilter implements FilterInterface
{
    public function buildFilter(FilterBuilder $builder)
    {
        $builder
            ->add('title', EqualFilterType::class)
            ->add('content', LikeFilterType::class)
            ->add('createdAt', RangeFilterType::class)
            ->add('createdAt', GreaterThanEqualFilterType::class, [
                'filterName' => 'createdAt_gte'
            ])
            ->add('createdAt', GreaterThanFilterType::class, [
                'filterName' => 'createdAt_gt'
            ])
            ->add('createdAt', LessThanEqualFilterType::class, [
                'filterName' => 'createdAt_lte'
            ])
            ->add('createdAt', LessThanFilterType::class, [
                'filterName' => 'createdAt_lt'
            ]);
    }
}