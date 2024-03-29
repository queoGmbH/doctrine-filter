<?php

namespace Queo\DoctrineFilter\Tests\Filter\Type;

use Queo\DoctrineFilter\FilterBuilder;
use Queo\DoctrineFilter\Type\LessThanFilterType;
use Queo\DoctrineFilter\Tests\Dummy\Entity\Post;
use Queo\DoctrineFilter\Tests\Dummy\Filter\PostFilter;
use Queo\DoctrineFilter\Tests\Dummy\LoadFixtures;
use Queo\DoctrineFilter\Tests\Dummy\TestCase;
use Queo\DoctrineFilter\Tests\Dummy\Traits\TestFilterTrait;

class LessThanFilterTypeTest extends TestCase
{
    use LoadFixtures, TestFilterTrait;

    public function getFilterDefinition()
    {
        return function (FilterBuilder $builder) {
            $builder
                ->add('createdAt', LessThanFilterType::class);
        };
    }

    /** @test */
    public function it_returns_an_entity_if_the_search_value_is_greater_than_the_value()
    {
        $posts = self::$em->getRepository(Post::class)->filter($this->filter, [
            'createdAt' => '2016-02-01 12:00:00'
        ]);

        $this->assertCount(1, $posts);
    }

    /** @test */
    public function it_returns_no_result_if_the_search_value_is_equal_to_the_value()
    {
        $posts = self::$em->getRepository(Post::class)->filter($this->filter, [
            'createdAt' => '2016-01-01 12:00:00'
        ]);

        $this->assertCount(0, $posts);
    }

    /** @test */
    public function it_returns_no_results_if_the_search_value_is_less_than_the_value()
    {
        $posts = self::$em->getRepository(Post::class)->filter($this->filter, [
            'createdAt' => '2015-12-01 12:00:00'
        ]);

        $this->assertCount(0, $posts);
    }
}
