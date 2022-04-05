<?php

namespace Queo\DoctrineFilter\Tests\Filter\Type;

use Queo\DoctrineFilter\FilterBuilder;
use Queo\DoctrineFilter\Type\EqualFilterType;
use Queo\DoctrineFilter\Tests\Dummy\Entity\Post;
use Queo\DoctrineFilter\Tests\Dummy\LoadFixtures;
use Queo\DoctrineFilter\Tests\Dummy\TestCase;
use Queo\DoctrineFilter\Tests\Dummy\Traits\TestFilterTrait;

class EqualFilterTypeTest extends TestCase
{
    use LoadFixtures, TestFilterTrait;

    public function getFilterDefinition()
    {
        return function (FilterBuilder $builder) {
            $builder
                ->add('title', EqualFilterType::class)
                ->add('content', EqualFilterType::class)
                ->add('isPublished', EqualFilterType::class);
        };
    }

    /** @test */
    public function it_returns_an_entity_if_the_search_value_is_exactly_the_same()
    {
        $posts = self::$em->getRepository(Post::class)->filter($this->filter, [
            'title' => 'Post title with Tag 1'
        ]);

        $this->assertCount(1, $posts);
        $this->assertEquals('Post title with Tag 1', $posts[0]->getTitle());
    }

    /** @test */
    public function it_returns_no_results_if_there_is_no_entity_with_the_exact_value()
    {
        $posts = self::$em->getRepository(Post::class)->filter($this->filter, [
            'title' => 'Another title'
        ]);

        $this->assertCount(0, $posts);
    }

    /** @test */
    public function it_works_with_boolean_values()
    {
        $posts = self::$em->getRepository(Post::class)->filter($this->filter, [
            'isPublished' => false
        ]);

        $this->assertCount(1, $posts);

        $posts = self::$em->getRepository(Post::class)->filter($this->filter, [
            'isPublished' => true
        ]);

        $this->assertCount(0, $posts);
    }

    /** @test */
    public function case_sensitivity_can_be_turned_off()
    {
        $this->filter->defineFilter(function (FilterBuilder $builder) {
            $builder
                ->add('title', EqualFilterType::class, ['case_sensitive' => false]);
        });

        $posts = self::$em->getRepository(Post::class)->filter($this->filter, [
            'title' => 'post title with TAG 1'
        ]);

        $this->assertCount(1, $posts);
        $this->assertEquals('Post title with Tag 1', $posts[0]->getTitle());
    }
}
