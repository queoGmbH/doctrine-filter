<?php

namespace Fludio\DoctrineFilter\Tests\Filter\Type;

use Fludio\DoctrineFilter\Filter\FilterBuilder;
use Fludio\DoctrineFilter\Filter\Type\LikeFilterType;
use Fludio\DoctrineFilter\Tests\Dummy\Entity\Post;
use Fludio\DoctrineFilter\Tests\Dummy\Filter\PostFilter;
use Fludio\DoctrineFilter\Tests\Dummy\LoadFixtures;
use Fludio\DoctrineFilter\Tests\Dummy\TestCase;
use Fludio\DoctrineFilter\Tests\Dummy\Traits\TestFilterTrait;

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
}
