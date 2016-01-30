<?php

namespace Fludio\DoctrineFilter\Tests\Filter\Type;

use Fludio\DoctrineFilter\Tests\Dummy\Entity\Post;
use Fludio\DoctrineFilter\Tests\Dummy\Filter\PostFilter;
use Fludio\DoctrineFilter\Tests\Dummy\LoadFixtures;
use Fludio\DoctrineFilter\Tests\Dummy\TestCase;

class RangeFilterTypeTest extends TestCase
{
    use LoadFixtures;

    /** @test */
    public function it_returns_a_result_if_the_value_is_within_the_range()
    {
        $posts = $this->em->getRepository(Post::class)->filter(new PostFilter(), [
            'createdAt_since' => '2015-12-01 12:00:00',
            'createdAt_until' => '2016-01-31 12:00:00'
        ]);

        $this->assertCount(1, $posts);
        $this->assertEquals('2016-01-01 12:00:00', $posts[0]->getCreatedAt()->format('Y-m-d H:i:s'));
    }

    /** @test */
    public function it_does_not_return_a_result_if_the_value_if_not_within_the_range()
    {
        $posts = $this->em->getRepository(Post::class)->filter(new PostFilter(), [
            'createdAt_since' => '2016-02-01 12:00:00',
            'createdAt_until' => '2016-03-31 12:00:00'
        ]);

        $this->assertCount(0, $posts);
    }
}
