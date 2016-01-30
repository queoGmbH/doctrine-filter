<?php

namespace Fludio\DoctrineFilter\Tests;

use Fludio\DoctrineFilter\Tests\Dummy\Entity\Post;
use Fludio\DoctrineFilter\Tests\Dummy\Filter\PostFilter;
use Fludio\DoctrineFilter\Tests\Dummy\LoadFixtures;
use Fludio\DoctrineFilter\Tests\Dummy\TestCase;

class FilterBuilderTest extends TestCase
{
    use LoadFixtures;

    /** @test */
    public function it_tracks_parameters_by_their_own_value()
    {
        $posts = $this->em->getRepository(Post::class)->filter(new PostFilter(), [
            'title' => 'Post title',
            'content_same' => 'My post content!'
        ]);

        $this->assertCount(1, $posts);
        $this->assertEquals('Post title', $posts[0]->getTitle());
    }
}
