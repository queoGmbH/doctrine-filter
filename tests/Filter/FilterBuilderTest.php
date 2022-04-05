<?php

namespace Queo\DoctrineFilter\Tests;

use Queo\DoctrineFilter\Tests\Dummy\Entity\Bike;
use Doctrine\ORM\QueryBuilder;
use Queo\DoctrineFilter\FilterBuilder;
use Queo\DoctrineFilter\Tests\Dummy\Entity\Car;
use Queo\DoctrineFilter\Tests\Dummy\Entity\Person;
use Queo\DoctrineFilter\Tests\Dummy\Entity\Tag;
use Queo\DoctrineFilter\Tests\Dummy\Entity\Transport;
use Queo\DoctrineFilter\Tests\Dummy\Entity25\Harbour;
use Queo\DoctrineFilter\Tests\Dummy\Entity25\Ship;
use Queo\DoctrineFilter\Tests\Dummy\Filter\TestFilter;
use Queo\DoctrineFilter\Tests\Dummy\Fixtures\LoadPersonData;
use Queo\DoctrineFilter\Tests\Dummy\Fixtures\LoadShipData;
use Queo\DoctrineFilter\Tests\Dummy\Fixtures\LoadTransportData;
use Queo\DoctrineFilter\Type\EqualFilterType;
use Queo\DoctrineFilter\Type\GreaterThanEqualFilterType;
use Queo\DoctrineFilter\Type\InstanceOfFilterType;
use Queo\DoctrineFilter\Type\LikeFilterType;
use Queo\DoctrineFilter\Tests\Dummy\Entity\Post;
use Queo\DoctrineFilter\Tests\Dummy\Filter\PostFilter;
use Queo\DoctrineFilter\Tests\Dummy\Fixtures\LoadCategoryData;
use Queo\DoctrineFilter\Tests\Dummy\Fixtures\LoadPostData;
use Queo\DoctrineFilter\Tests\Dummy\Fixtures\LoadTagData;
use Queo\DoctrineFilter\Tests\Dummy\LoadFixtures;
use Queo\DoctrineFilter\Tests\Dummy\TestCase;
use Queo\DoctrineFilter\Tests\Dummy\Traits\TestFilterTrait;
use Doctrine\ORM\Version;

class FilterBuilderTest extends TestCase
{
    use LoadFixtures, TestFilterTrait;

    public function loadFixtures()
    {
        $fixtures = [
            new LoadCategoryData(),
            new LoadTagData(),
            new LoadPostData(),
            new LoadTransportData(),
            new LoadPersonData()
        ];

        if ($this->isAtLeastDoctrineVersion('2.5')) {
            $fixtures[] = new LoadShipData();
        }

        return $fixtures;
    }


    public function getFilterDefinition()
    {
        return function (FilterBuilder $builder) {
            $builder
                ->add('title', LikeFilterType::class)
                ->add('content', EqualFilterType::class)
                ->add('tags', EqualFilterType::class, [
                    'fields' => 'tags.name'
                ])
                ->add('category', EqualFilterType::class, [
                    'fields' => 'tags.category.name'
                ])
                ->add('blog', EqualFilterType::class, [
                    'fields' => 'blog'
                ]);
        };
    }

    /**
     * @test
     * @group now
     */
    public function it_returns_the_added_filters()
    {
        $builder = new FilterBuilder();
        $builder
            ->add('a', EqualFilterType::class)
            ->add('b', EqualFilterType::class);

        $this->assertCount(2, $builder->getFilters());
    }

    /** @test */
    public function it_tracks_parameters_by_their_own_value()
    {
        $posts = self::$em->getRepository(Post::class)->filter($this->filter, [
            'title' => 'Post title',
            'content' => 'My post content!'
        ]);

        $this->assertCount(1, $posts);
        $this->assertEquals('Post title with Tag 1', $posts[0]->getTitle());
    }

    /** @test */
    public function it_queries_relationships()
    {
        $posts = self::$em->getRepository(Post::class)->filter($this->filter, [
            'tags' => 'Tag 1',
        ]);

        $this->assertCount(1, $posts);
        $this->assertEquals('Tag 1', $posts[0]->getTags()->first()->getName());
    }

    /** @test */
    public function it_returns_no_results_if_relation_ship_query_is_not_fullfilled()
    {
        $posts = self::$em->getRepository(Post::class)->filter($this->filter, [
            'tags' => 'Tag 4',
        ]);

        $this->assertCount(0, $posts);
    }

    /** @test */
    public function it_queries_deeply_nested_relationships()
    {
        $posts = self::$em->getRepository(Post::class)->filter($this->filter, [
            'category' => 'Category 1',
        ]);

        $this->assertCount(1, $posts);
        $this->assertEquals('Category 1', $posts[0]->getTags()->first()->getCategory()->getName());
    }

    /** @test */
    public function it_returns_no_result_if_deeply_nested_relationship_query_is_not_fullfilled()
    {
        $posts = self::$em->getRepository(Post::class)->filter($this->filter, [
            'category' => 'Category 2000',
        ]);

        $this->assertCount(0, $posts);
    }

    /** @test */
    public function it_can_handle_multiple_relationship_queries()
    {
        $posts = self::$em->getRepository(Post::class)->filter($this->filter, [
            'title' => 'Post title',
            'tags' => 'Tag 2',
            'category' => 'Category 1'
        ]);

        $this->assertCount(1, $posts);

        $posts = self::$em->getRepository(Post::class)->filter($this->filter, [
            'title' => 'Post title',
            'tags' => 'Tag 2',
            'category' => 'Category 2'
        ]);

        $this->assertCount(0, $posts);
    }

    /**
     * @test
     */
    public function it_does_throw_an_exception_if_field_does_not_exist()
    {
        self::expectException(\Doctrine\ORM\Query\QueryException::class);

        self::$em->getRepository(Post::class)->filter($this->filter, [
            'blog' => 'Blog A'
        ]);
    }

    /** @test */
    public function it_can_filter_on_embeddables()
    {
        if (!$this->isAtLeastDoctrineVersion('2.5')) {
            $this->markTestSkipped('Embeddables not available prior to Doctrine 2.5');
        }

        $filter = new TestFilter();
        $filter->defineFilter(function (FilterBuilder $builder) {
            $builder
                ->add('horsepower', GreaterThanEqualFilterType::class, [
                    'fields' => 'engine.horsepower'
                ]);
        });

        $res1 = self::$em->getRepository(Ship::class)->filter($filter, [
            'horsepower' => 400
        ]);

        $this->assertEmpty($res1);

        $res2 = self::$em->getRepository(Ship::class)->filter($filter, [
            'horsepower' => 300
        ]);

        $this->assertCount(1, $res2);
    }

    /** @test */
    public function it_can_filter_on_embeddables_on_relationships()
    {
        if (!$this->isAtLeastDoctrineVersion('2.5')) {
            $this->markTestSkipped('Embeddables not available prior to Doctrine 2.5');
        }

        $filter = new TestFilter();
        $filter->defineFilter(function (FilterBuilder $builder) {
            $builder
                ->add('horsepower', GreaterThanEqualFilterType::class, [
                    'fields' => 'ships.engine.horsepower'
                ]);
        });

        $res1 = self::$em->getRepository(Harbour::class)->filter($filter, [
            'horsepower' => 400
        ]);

        $this->assertEmpty($res1);

        $res2 = self::$em->getRepository(Harbour::class)->filter($filter, [
            'horsepower' => 300
        ]);

        $this->assertCount(1, $res2);
    }

    /** @test */
    public function it_is_possible_to_query_on_multiple_fields()
    {
        $this->filter->defineFilter(function (FilterBuilder $builder) {
            $builder
                ->add('title_and_tags', LikeFilterType::class, [
                    'fields' => ['title', 'tags.name']
                ]);
        });

        $posts = self::$em->getRepository(Post::class)->filter($this->filter, [
            'title_and_tags' => 'Tag 1'
        ]);

        $this->assertCount(1, $posts);
    }

    /** @test */
    public function it_is_possible_to_pass_a_callback_to_the_add_function()
    {
        $this->filter->defineFilter(function (FilterBuilder $builder) {
            $builder
                ->add('limitToOne', function (QueryBuilder $qb, $table, $field, \Closure $getValue) {
                    $qb
                        ->orderBy($table . '.name', 'DESC')
                        ->setMaxResults(1);
                });
        });

        $tags = self::$em->getRepository(Tag::class)->filter($this->filter, [
            'limitToOne' => true
        ]);

        $this->assertCount(1, $tags);
        $this->assertEquals('Tag 3', $tags[0]->getName());
    }

    /** @test */
    public function a_default_value_can_be_set()
    {
        $this->filter->defineFilter(function (FilterBuilder $builder) {
            $builder
                ->add('type', InstanceOfFilterType::class, [
                    'default' => Car::class,
                ]);
        });

        $cars = self::$em->getRepository(Transport::class)->filter($this->filter);

        $this->assertCount(2, $cars);
    }

    /** @test */
    public function the_default_value_does_not_get_overridden_by_default()
    {
        $this->filter->defineFilter(function (FilterBuilder $builder) {
            $builder
                ->add('type', InstanceOfFilterType::class, [
                    'default' => Car::class,
                ]);
        });

        $cars = self::$em->getRepository(Transport::class)->filter($this->filter, [
            'type' => Bike::class
        ]);

        $this->assertCount(2, $cars);
        $this->assertInstanceOf(Car::class, $cars[0]);
        $this->assertInstanceOf(Car::class, $cars[1]);
    }

    /** @test */
    public function the_default_value_can_be_overridden_when_allowed()
    {
        $this->filter->defineFilter(function (FilterBuilder $builder) {
            $builder
                ->add('type', InstanceOfFilterType::class, [
                    'default' => Car::class,
                    'default_override' => true
                ]);
        });

        $bikes = self::$em->getRepository(Transport::class)->filter($this->filter, [
            'type' => Bike::class
        ]);

        $this->assertCount(1, $bikes);
        $this->assertInstanceOf(Bike::class, $bikes[0]);
    }

    /** @test */
    public function only_one_of_the_field_values_has_to_be_found_when_match_all_is_false()
    {
        $this->filter->defineFilter(function (FilterBuilder $builder) {
            $builder
                ->add('fullText', LikeFilterType::class, [
                    'fields' => ['title', 'content']
                ]);
        });

        $posts = self::$em->getRepository(Post::class)->filter($this->filter, [
            'fullText' => 'My post content'
        ]);

        $this->assertCount(1, $posts);
    }

    /** @test */
    public function all_field_values_have_to_be_found_when_match_all_is_true()
    {
        $this->filter->defineFilter(function (FilterBuilder $builder) {
            $builder
                ->add('fullText', LikeFilterType::class, [
                    'fields' => ['title', 'content'],
                    'match_all_fields' => true
                ]);
        });

        $posts = self::$em->getRepository(Post::class)->filter($this->filter, [
            'fullText' => 'ost'
        ]);

        $this->assertCount(1, $posts);

        $posts = self::$em->getRepository(Post::class)->filter($this->filter, [
            'fullText' => 'My post content'
        ]);

        $this->assertCount(0, $posts);
    }

    /** @test */
    public function the_filter_builder_can_be_used_multiple_times()
    {
        $qb = self::$em->getRepository(Post::class)->createQueryBuilder('x');
        $builder = new FilterBuilder();
        $posts = $builder
            ->setQueryBuilder($qb)
            ->setFilter($this->filter)
            ->getResult([
                'tags' => 'Tag 3'
            ]);

        $this->assertCount(0, $posts);

        $posts = $builder->getResult([
            'category' => 'Category 1'
        ]);

        $this->assertCount(1, $posts);
    }

    /** @test */
    public function the_filter_can_be_set_again()
    {
        $qb = self::$em->getRepository(Post::class)->createQueryBuilder('x');
        $builder = new FilterBuilder();
        $posts = $builder
            ->setQueryBuilder($qb)
            ->setFilter($this->filter)
            ->getResult([
                'title' => 'Post title'
            ]);

        $this->assertCount(1, $posts);

        // Change filter

        $this->filter->defineFilter(function (FilterBuilder $builder) {
            $builder
                ->add('content', LikeFilterType::class);
        });

        // Query again with new filter

        $posts = $builder
            ->setFilter($this->filter)
            ->getResult([
                'title' => 'Should be ignored',
                'content' => 'post content'
            ]);

        $this->assertCount(1, $posts);
    }
}
