<?php

namespace BiteCodes\DoctrineFilter\Tests\Filter\Type;

use BiteCodes\DoctrineFilter\FilterBuilder;
use BiteCodes\DoctrineFilter\Tests\Dummy\Entity\Post;
use BiteCodes\DoctrineFilter\Tests\Dummy\Fixtures\LoadPostCollectionData;
use BiteCodes\DoctrineFilter\Tests\Dummy\LoadFixtures;
use BiteCodes\DoctrineFilter\Tests\Dummy\TestCase;
use BiteCodes\DoctrineFilter\Tests\Dummy\Traits\TestFilterTrait;
use BiteCodes\DoctrineFilter\Type\NotEqualFilterType;

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
