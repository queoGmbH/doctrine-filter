<?php

namespace Fludio\DoctrineFilter\Tests\Filter\Type;

use Fludio\DoctrineFilter\FilterBuilder;
use Fludio\DoctrineFilter\Tests\Dummy\Entity\Post;
use Fludio\DoctrineFilter\Tests\Dummy\Fixtures\LoadPostCollectionData;
use Fludio\DoctrineFilter\Tests\Dummy\LoadFixtures;
use Fludio\DoctrineFilter\Tests\Dummy\TestCase;
use Fludio\DoctrineFilter\Tests\Dummy\Traits\TestFilterTrait;
use Fludio\DoctrineFilter\Type\ComparableFilterType;

class ComparableFilterTypeTest extends TestCase
{
    use LoadFixtures, TestFilterTrait;

    public function getFilterDefinition()
    {
        return function (FilterBuilder $builder) {
            $builder
                ->add('title', ComparableFilterType::class)
                ->add('createdAt', ComparableFilterType::class)
                ->orderBy('title_sort', 'ASC', ['field' => 'title']);
        };
    }

    public function loadFixtures()
    {
        return [
            new LoadPostCollectionData()
        ];
    }

    /** @test */
    public function it_expands()
    {
        $filter = new ComparableFilterType('horsepower', []);
        $filter->expand(new FilterBuilder(), 'a', 'b', 'c');
    }

    /** @test */
    public function it_works_for_not_equal()
    {
        $posts = $this->em->getRepository(Post::class)->filter($this->filter, [
            'title!' => 'Post 1'
        ]);

        $this->assertCount(2, $posts);
        $this->assertEquals('Post 2', $posts[0]->getTitle());
        $this->assertEquals('Post 3', $posts[1]->getTitle());
    }

    /** @test */
    public function it_works_for_less_than_or_equal()
    {
        $posts = $this->em->getRepository(Post::class)->filter($this->filter, [
            'createdAt<' => '2016-02-02 12:00:00'
        ]);

        $this->assertCount(2, $posts);
        $this->assertEquals('Post 1', $posts[0]->getTitle());
        $this->assertEquals('Post 2', $posts[1]->getTitle());
    }

    /** @test */
    public function it_works_for_greater_than_or_equal()
    {
        $posts = $this->em->getRepository(Post::class)->filter($this->filter, [
            'createdAt>' => '2016-02-02 12:00:00'
        ]);

        $this->assertCount(2, $posts);
        $this->assertEquals('Post 2', $posts[0]->getTitle());
        $this->assertEquals('Post 3', $posts[1]->getTitle());
    }
}
