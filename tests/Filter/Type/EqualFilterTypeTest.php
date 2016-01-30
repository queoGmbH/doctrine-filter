<?php

namespace Fludio\DoctrineFilter\Tests\Filter\Type;

use Fludio\DoctrineFilter\Tests\Dummy\Entity\Post;
use Fludio\DoctrineFilter\Tests\Dummy\Filter\PostFilter;
use Fludio\DoctrineFilter\Tests\Dummy\LoadFixtures;
use Fludio\DoctrineFilter\Tests\Dummy\TestCase;

class EqualFilterTypeTest extends TestCase
{
    use LoadFixtures;

    /** @test */
    public function it_returns_an_entity_if_the_search_value_is_exactly_the_same()
    {
        $posts = $this->em->getRepository(Post::class)->filter(new PostFilter(), [
            'title' => 'Post title'
        ]);

        $this->assertCount(1, $posts);
        $this->assertEquals('Post title', $posts[0]->getTitle());
    }

    /** @test */
    public function it_returns_no_results_if_there_is_no_entity_with_the_exact_value()
    {
        $posts = $this->em->getRepository(Post::class)->filter(new PostFilter(), [
            'title' => 'Another title'
        ]);

        $this->assertCount(0, $posts);
    }
}
