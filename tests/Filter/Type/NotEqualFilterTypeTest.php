<?php

namespace Queo\DoctrineFilter\Tests\Filter\Type;

use Queo\DoctrineFilter\FilterBuilder;
use Queo\DoctrineFilter\Tests\Dummy\Entity\Post;
use Queo\DoctrineFilter\Tests\Dummy\Fixtures\LoadPostCollectionData;
use Queo\DoctrineFilter\Tests\Dummy\LoadFixtures;
use Queo\DoctrineFilter\Tests\Dummy\TestCase;
use Queo\DoctrineFilter\Tests\Dummy\Traits\TestFilterTrait;
use Queo\DoctrineFilter\Type\NotEqualFilterType;

class NotEqualFilterTypeTest extends TestCase
{
    use LoadFixtures, TestFilterTrait;

    public function getFilterDefinition()
    {
        return function (FilterBuilder $builder) {
            $builder
                ->add('title', NotEqualFilterType::class);
        };
    }

    public function loadFixtures()
    {
        return [
            new LoadPostCollectionData()
        ];
    }


    /** @test */
    public function it_returns_entities_if_the_search_value_is_not_exactly_the_same()
    {
        $posts = self::$em->getRepository(Post::class)->filter($this->filter, [
            'title' => 'Post 1'
        ]);

        $this->assertCount(2, $posts);
        $this->assertEquals('Post 2', $posts[0]->getTitle());
        $this->assertEquals('Post 3', $posts[1]->getTitle());
    }
}
