<?php

namespace Fludio\DoctrineFilter\Tests\Dummy\Filter;

use Fludio\DoctrineFilter\Filter\AbstractFilter;
use Fludio\DoctrineFilter\Filter\Condition\EqualsCondition;
use Fludio\DoctrineFilter\Filter\Condition\LikeCondition;
use Fludio\DoctrineFilter\Filter\FilterBuilder;

class PostFilter extends AbstractFilter
{
    public function buildFilter(FilterBuilder $builder)
    {
        $builder
            ->add('title', EqualsCondition::class)
            ->add('content', LikeCondition::class);
    }
}