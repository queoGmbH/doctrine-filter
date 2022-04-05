<?php

namespace Queo\DoctrineFilter\Tests\Filter\Type;

use Queo\DoctrineFilter\FilterBuilder;
use Queo\DoctrineFilter\Tests\Dummy\Entity\Post;
use Queo\DoctrineFilter\Tests\Dummy\Fixtures\LoadPostCollectionData;
use Queo\DoctrineFilter\Tests\Dummy\LoadFixtures;
use Queo\DoctrineFilter\Tests\Dummy\TestCase;
use Queo\DoctrineFilter\Tests\Dummy\Traits\TestFilterTrait;
use Queo\DoctrineFilter\Type\ComparableFilterType;
use Doctrine\ORM\Query\Expr\Andx;

class ComparableFilterTypeTest extends TestCase
{
    use LoadFixtures, TestFilterTrait;

    public function getFilterDefinition()
    {
        return function (FilterBuilder $builder) {
            $builder
                ->add('title', ComparableFilterType::class)
                ->add('createdAt', ComparableFilterType::class)
                ->orderBy('title_sort', 'ASC', ['fields' => 'title']);
        };
    }

    public function loadFixtures()
    {
        return [
            new LoadPostCollectionData()
        ];
    }

    /** @test */
    public function it_works_for_not_equal()
    {
        $posts = self::$em->getRepository(Post::class)->filter($this->filter, [
            'title!' => 'Post 1'
        ]);

        $this->assertCount(2, $posts);
        $this->assertEquals('Post 2', $posts[0]->getTitle());
        $this->assertEquals('Post 3', $posts[1]->getTitle());
    }

    /** @test */
    public function it_works_for_less_than_or_equal()
    {
        $posts = self::$em->getRepository(Post::class)->filter($this->filter, [
            'createdAt<' => '2016-02-02 12:00:00'
        ]);

        $this->assertCount(2, $posts);
        $this->assertEquals('Post 1', $posts[0]->getTitle());
        $this->assertEquals('Post 2', $posts[1]->getTitle());
    }

    /** @test */
    public function it_works_for_greater_than_or_equal()
    {
        $posts = self::$em->getRepository(Post::class)->filter($this->filter, [
            'createdAt>' => '2016-02-02 12:00:00'
        ]);

        $this->assertCount(2, $posts);
        $this->assertEquals('Post 2', $posts[0]->getTitle());
        $this->assertEquals('Post 3', $posts[1]->getTitle());
    }
}
