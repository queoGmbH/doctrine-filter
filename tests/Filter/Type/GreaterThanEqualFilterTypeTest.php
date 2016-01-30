<?php

namespace Fludio\DoctrineFilter\Tests\Filter\Type;

use Fludio\DoctrineFilter\Tests\Dummy\Entity\Post;
use Fludio\DoctrineFilter\Tests\Dummy\Filter\PostFilter;
use Fludio\DoctrineFilter\Tests\Dummy\LoadFixtures;
use Fludio\DoctrineFilter\Tests\Dummy\TestCase;

class GreaterThanEqualFilterTypeTest extends TestCase
{
    use LoadFixtures;

    /** @test */
    public function it_returns_an_entity_if_the_search_value_is_less_than_the_value()
    {
        $posts = $this->em->getRepository(Post::class)->filter(new PostFilter(), [
            'createdAt_gte' => '2015-12-01 12:00:00'
        ]);

        $this->assertCount(1, $posts);
    }

    /** @test */
    public function it_returns_an_entity_if_the_search_value_is_equal_to_the_value()
    {
        $posts = $this->em->getRepository(Post::class)->filter(new PostFilter(), [
            'createdAt_gte' => '2016-01-01 12:00:00'
        ]);

        $this->assertCount(1, $posts);
    }

    /** @test */
    public function it_returns_no_results_if_the_search_value_is_greater_than_the_value()
    {
        $posts = $this->em->getRepository(Post::class)->filter(new PostFilter(), [
            'createdAt_gte' => '2016-02-01 12:00:00'
        ]);

        $this->assertCount(0, $posts);
    }
}
