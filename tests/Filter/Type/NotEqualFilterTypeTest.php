<?php

namespace Fludio\DoctrineFilter\Tests\Filter\Type;

use Fludio\DoctrineFilter\FilterBuilder;
use Fludio\DoctrineFilter\Tests\Dummy\Entity\Post;
use Fludio\DoctrineFilter\Tests\Dummy\Fixtures\LoadPostCollectionData;
use Fludio\DoctrineFilter\Tests\Dummy\LoadFixtures;
use Fludio\DoctrineFilter\Tests\Dummy\TestCase;
use Fludio\DoctrineFilter\Tests\Dummy\Traits\TestFilterTrait;
use Fludio\DoctrineFilter\Type\NotEqualFilterType;

class NotEqualFilterTypeTest extends TestCase
{
    use LoadFixtures, TestFilterTrait;

    public function getFilterDefinition()
    {
        return function (FilterBuilder $builder) {
            $builder
                ->add('title', NotEqualFilterType::class)
                ->orderBy('title', 'ASC');
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
        $posts = $this->em->getRepository(Post::class)->filter($this->filter, [
            'title' => 'Post 1'
        ]);

        $this->assertCount(2, $posts);
        $this->assertEquals('Post 2', $posts[0]->getTitle());
        $this->assertEquals('Post 3', $posts[1]->getTitle());
    }
}
