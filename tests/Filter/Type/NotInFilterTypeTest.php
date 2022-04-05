<?php

namespace Queo\DoctrineFilter\Tests\Filter\Type;

use Queo\DoctrineFilter\FilterBuilder;
use Queo\DoctrineFilter\Tests\Dummy\Entity\Post;
use Queo\DoctrineFilter\Tests\Dummy\Fixtures\LoadPostData;
use Queo\DoctrineFilter\Tests\Dummy\Fixtures\LoadTagData;
use Queo\DoctrineFilter\Tests\Dummy\LoadFixtures;
use Queo\DoctrineFilter\Tests\Dummy\TestCase;
use Queo\DoctrineFilter\Tests\Dummy\Traits\TestFilterTrait;
use Queo\DoctrineFilter\Type\NotInFilterType;

class NotInFilterTypeTest extends TestCase
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
                ->add('tags', NotInFilterType::class, [
                    'fields' => 'tags.name'
                ])
                ->add('tagIds', NotInFilterType::class, [
                    'fields' => 'tags.id'
                ]);
        };
    }

    /** @test */
    public function it_returns_entites_when_value_is_not_in_search_query()
    {
        $posts = self::$em->getRepository(Post::class)->filter($this->filter, [
            'tags' => ['Tag 1', 'Another Tag']
        ]);

        $this->assertCount(1, $posts);
    }

    /** @test */
    public function it_returns_no_result_when_value_is_in_search_query()
    {
        $posts = self::$em->getRepository(Post::class)->filter($this->filter, [
            'tags' => ['Tag 1', 'Tag 2']
        ]);

        $this->assertCount(0, $posts);
    }

    /** @test */
    public function it_filters_by_id()
    {
        $posts = self::$em->getRepository(Post::class)->filter($this->filter, [
            'tagIds' => [1, 2]
        ]);

        $this->assertCount(1, $posts);
    }
}
