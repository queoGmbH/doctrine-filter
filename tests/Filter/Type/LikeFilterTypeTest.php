<?php

namespace Fludio\DoctrineFilter\Tests\Filter\Type;

use Fludio\DoctrineFilter\Tests\Dummy\Entity\Post;
use Fludio\DoctrineFilter\Tests\Dummy\Filter\PostFilter;
use Fludio\DoctrineFilter\Tests\Dummy\LoadFixtures;
use Fludio\DoctrineFilter\Tests\Dummy\TestCase;

class LikeFilterTypeTest extends TestCase
{
    use LoadFixtures;

    /** @test */
    public function it_returns_an_entity_if_the_database_value_contains_the_search_value()
    {
        $posts = $this->em->getRepository(Post::class)->filter(new PostFilter(), [
            'content' => 'post'
        ]);

        $this->assertCount(1, $posts);
        $this->assertEquals('My post content!', $posts[0]->getContent());
    }

    /** @test */
    public function it_returns_no_results_if_the_database_value_does_not_contain_the_search_value()
    {
        $posts = $this->em->getRepository(Post::class)->filter(new PostFilter(), [
            'content' => 'quark'
        ]);

        $this->assertCount(0, $posts);
    }
}
