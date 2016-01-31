<?php

namespace Fludio\DoctrineFilter\Tests;

use Fludio\DoctrineFilter\Filter\FilterBuilder;
use Fludio\DoctrineFilter\Filter\Type\EqualFilterType;
use Fludio\DoctrineFilter\Tests\Dummy\Entity\Post;
use Fludio\DoctrineFilter\Tests\Dummy\Filter\PostFilter;
use Fludio\DoctrineFilter\Tests\Dummy\Fixtures\LoadPostData;
use Fludio\DoctrineFilter\Tests\Dummy\Fixtures\LoadTagData;
use Fludio\DoctrineFilter\Tests\Dummy\LoadFixtures;
use Fludio\DoctrineFilter\Tests\Dummy\TestCase;
use Fludio\DoctrineFilter\Tests\Dummy\Traits\TestFilterTrait;

class FilterBuilderTest extends TestCase
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
                ->add('title', EqualFilterType::class)
                ->add('content', EqualFilterType::class)
                ->add('tags.name', EqualFilterType::class, [
                    'filterName' => 'tags'
                ]);
        };
    }

    /** @test */
    public function it_tracks_parameters_by_their_own_value()
    {
        $posts = $this->em->getRepository(Post::class)->filter($this->filter, [
            'title' => 'Post title',
            'content' => 'My post content!'
        ]);

        $this->assertCount(1, $posts);
        $this->assertEquals('Post title', $posts[0]->getTitle());
    }

    /** @test */
    public function it_queries_relationships()
    {
        $posts = $this->em->getRepository(Post::class)->filter($this->filter, [
            'tags' => 'Tag 1',
        ]);

        $this->assertCount(1, $posts);
        $this->assertEquals('Tag 1', $posts[0]->getTags()->first()->getName());
    }

    /** @test */
    public function it_returns_no_results_if_relation_ship_query_is_not_fullfilled()
    {
        $posts = $this->em->getRepository(Post::class)->filter($this->filter, [
            'tags' => 'Tag 4',
        ]);

        $this->assertCount(0, $posts);
    }
}
