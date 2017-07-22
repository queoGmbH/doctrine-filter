<?php

namespace BiteCodes\DoctrineFilter\Tests\Filter\Type;

use BiteCodes\DoctrineFilter\FilterBuilder;
use BiteCodes\DoctrineFilter\Type\LikeFilterType;
use BiteCodes\DoctrineFilter\Tests\Dummy\Entity\Post;
use BiteCodes\DoctrineFilter\Tests\Dummy\Filter\PostFilter;
use BiteCodes\DoctrineFilter\Tests\Dummy\LoadFixtures;
use BiteCodes\DoctrineFilter\Tests\Dummy\TestCase;
use BiteCodes\DoctrineFilter\Tests\Dummy\Traits\TestFilterTrait;

class LikeFilterTypeTest extends TestCase
{
    use LoadFixtures, TestFilterTrait;

    public function getFilterDefinition()
    {
        return function (FilterBuilder $builder) {
            $builder
                ->add('content', LikeFilterType::class);
        };
    }

    /** @test */
    public function it_returns_an_entity_if_the_database_value_contains_the_search_value()
    {
        $posts = $this->em->getRepository(Post::class)->filter($this->filter, [
            'content' => 'post'
        ]);

        $this->assertCount(1, $posts);
        $this->assertEquals('My post content!', $posts[0]->getContent());
    }

    /** @test */
    public function it_returns_no_results_if_the_database_value_does_not_contain_the_search_value()
    {
        $posts = $this->em->getRepository(Post::class)->filter($this->filter, [
            'content' => 'quark'
        ]);

        $this->assertCount(0, $posts);
    }

    /** @test */
    public function it_allows_for_like_query_that_start_with_a_value()
    {
        $this->filter->defineFilter(function (FilterBuilder $builder) {
            $builder
                ->add('content', LikeFilterType::class, [
                    'start_with' => true
                ]);
        });

        $posts = $this->em->getRepository(Post::class)->filter($this->filter, [
            'content' => 'My post'
        ]);

        $this->assertCount(1, $posts);

        $posts = $this->em->getRepository(Post::class)->filter($this->filter, [
            'content' => 'post'
        ]);

        $this->assertCount(0, $posts);
    }

    /** @test */
    public function it_allows_for_like_query_that_end_with_a_value()
    {
        $this->filter->defineFilter(function (FilterBuilder $builder) {
            $builder
                ->add('content', LikeFilterType::class, [
                    'end_with' => true
                ]);
        });

        $posts = $this->em->getRepository(Post::class)->filter($this->filter, [
            'content' => 'content!'
        ]);

        $this->assertCount(1, $posts);

        $posts = $this->em->getRepository(Post::class)->filter($this->filter, [
            'content' => 'My post'
        ]);

        $this->assertCount(0, $posts);
    }

    /** @test */
    public function it_finds_values_in_multiple_fields_but_it_only_requires_a_partial_match()
    {
        $this->filter->defineFilter(function (FilterBuilder $builder) {
            $builder
                ->add('post_content', LikeFilterType::class, [
                    'fields' => ['title', 'content'],
                    'partial_match' => true
                ]);
        });

        $posts = $this->em->getRepository(Post::class)->filter($this->filter, [
            'post_content' => 'title content'
        ]);

        $this->assertCount(1, $posts);

        $posts = $this->em->getRepository(Post::class)->filter($this->filter, [
            'post_content' => 'content'
        ]);

        $this->assertCount(1, $posts);

        $posts = $this->em->getRepository(Post::class)->filter($this->filter, [
            'post_content' => 'unknown content'
        ]);

        $this->assertCount(0, $posts);
    }

    /** @test */
    public function it_accepts_partial_matching_with_multiple_spaces()
    {
        $this->filter->defineFilter(function (FilterBuilder $builder) {
            $builder
                ->add('post_content', LikeFilterType::class, [
                    'fields' => ['title', 'content'],
                    'partial_match' => true
                ]);
        });

        $posts = $this->em->getRepository(Post::class)->filter($this->filter, [
            'post_content' => '  title   content  '
        ]);

        $this->assertCount(1, $posts);
    }
}
