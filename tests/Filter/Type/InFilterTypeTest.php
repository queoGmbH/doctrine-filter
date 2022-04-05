<?php

namespace BiteCodes\DoctrineFilter\Tests\Filter\Type;

use BiteCodes\DoctrineFilter\FilterBuilder;
use BiteCodes\DoctrineFilter\Type\InFilterType;
use BiteCodes\DoctrineFilter\Tests\Dummy\Entity\Post;
use BiteCodes\DoctrineFilter\Tests\Dummy\Fixtures\LoadPostData;
use BiteCodes\DoctrineFilter\Tests\Dummy\Fixtures\LoadTagData;
use BiteCodes\DoctrineFilter\Tests\Dummy\LoadFixtures;
use BiteCodes\DoctrineFilter\Tests\Dummy\TestCase;
use BiteCodes\DoctrineFilter\Tests\Dummy\Traits\TestFilterTrait;

class InFilterTypeTest extends TestCase
{
    use LoadFixtures, TestFilterTrait;

    public function loadFixtures()
    {
        return [
            new LoadTagData(),
            new LoadPostData()
        ];
    }

    public function getFilterDefinition()
    {
        return function (FilterBuilder $builder) {
            $builder
                ->add('tags', InFilterType::class, [
                    'fields' => 'tags.name'
                ]);
        };
    }

    /** @test */
    public function it_returns_entites_when_value_is_in_search_query()
    {
        $posts = self::$em->getRepository(Post::class)->filter($this->filter, [
            'tags' => ['Tag 1', 'Unknown Tag']
        ]);

        $this->assertCount(1, $posts);
    }

    /** @test */
    public function it_returns_no_result_when_value_is_not_in_search_query()
    {
        $posts = self::$em->getRepository(Post::class)->filter($this->filter, [
            'tags' => ['Unknown Tag', 'Another Tag']
        ]);

        $this->assertCount(0, $posts);
    }

    /** @test */
    public function it_handles_empty_values()
    {
        $posts = self::$em->getRepository(Post::class)->filter($this->filter, [
            'tags' => []
        ]);

        $this->assertCount(1, $posts);

        $this->filter->defineFilter(function (FilterBuilder $builder) {
            $builder
                ->add('tags', InFilterType::class, [
                    'fields' => 'tags.name',
                    'allow_empty' => false
                ]);
        });

        $posts = self::$em->getRepository(Post::class)->filter($this->filter, [
            'tags' => []
        ]);

        $this->assertCount(0, $posts);
    }

    /** @test */
    public function it_returns_only_entities_that_have_all_values()
    {
        $this->filter->defineFilter(function (FilterBuilder $builder) {
            $builder
                ->add('tags', InFilterType::class, [
                    'fields' => 'tags.name',
                    'match_all' => true
                ]);
        });

        $posts = self::$em->getRepository(Post::class)->filter($this->filter, [
            'tags' => ['Tag 1', 'Tag 2']
        ]);

        $this->assertCount(1, $posts);

        $posts = self::$em->getRepository(Post::class)->filter($this->filter, [
            'tags' => ['Tag 1', 'Tag 2', 'Tag 3']
        ]);

        $this->assertCount(0, $posts);
    }

    /** @test */
    public function for_match_all_it_handles_passing_in_the_same_value_multiple_times()
    {
        $this->filter->defineFilter(function (FilterBuilder $builder) {
            $builder
                ->add('tags', InFilterType::class, [
                    'fields' => 'tags.name',
                    'match_all' => true
                ]);
        });

        $posts = self::$em->getRepository(Post::class)->filter($this->filter, [
            'tags' => ['Tag 1', 'Tag 1', 'Tag 2', 'Tag 2', 'Tag 2']
        ]);

        $this->assertCount(1, $posts);
    }
}
