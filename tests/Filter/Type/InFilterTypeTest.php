<?php

namespace Fludio\DoctrineFilter\Tests\Filter\Type;

use Fludio\DoctrineFilter\FilterBuilder;
use Fludio\DoctrineFilter\Type\InFilterType;
use Fludio\DoctrineFilter\Tests\Dummy\Entity\Post;
use Fludio\DoctrineFilter\Tests\Dummy\Fixtures\LoadPostData;
use Fludio\DoctrineFilter\Tests\Dummy\Fixtures\LoadTagData;
use Fludio\DoctrineFilter\Tests\Dummy\LoadFixtures;
use Fludio\DoctrineFilter\Tests\Dummy\TestCase;
use Fludio\DoctrineFilter\Tests\Dummy\Traits\TestFilterTrait;

class InFilterTypeTest extends TestCase
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
                ->add('tags', InFilterType::class, [
                    'field' => 'tags.name'
                ]);
        };
    }

    /** @test */
    public function it_returns_entites_when_value_is_in_search_query()
    {
        $posts = $this->em->getRepository(Post::class)->filter($this->filter, [
            'tags' => ['Tag 1', 'Unknown Tag']
        ]);

        $this->assertCount(1, $posts);
    }

    /** @test */
    public function it_returns_no_result_when_value_is_not_in_search_query()
    {
        $posts = $this->em->getRepository(Post::class)->filter($this->filter, [
            'tags' => ['Unknown Tag', 'Another Tag']
        ]);

        $this->assertCount(0, $posts);
    }
}
