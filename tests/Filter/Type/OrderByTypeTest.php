<?php

namespace BiteCodes\DoctrineFilter\Tests\Filter\Type;

use BiteCodes\DoctrineFilter\FilterBuilder;
use BiteCodes\DoctrineFilter\Tests\Dummy\Entity\Post;
use BiteCodes\DoctrineFilter\Tests\Dummy\Fixtures\LoadPostCollectionData;
use BiteCodes\DoctrineFilter\Tests\Dummy\Fixtures\LoadTagData;
use BiteCodes\DoctrineFilter\Tests\Dummy\LoadFixtures;
use BiteCodes\DoctrineFilter\Tests\Dummy\TestCase;
use BiteCodes\DoctrineFilter\Tests\Dummy\Traits\TestFilterTrait;

class OrderByTypeTest extends TestCase
{
    use LoadFixtures, TestFilterTrait;

    public function loadFixtures()
    {
        return [
            new LoadPostCollectionData(),
            new LoadTagData()
        ];
    }

    public function getFilterDefinition()
    {
        return function (FilterBuilder $builder) {
            $builder
                ->orderBy('createdAt');
        };
    }

    /** @test */
    public function it_orders_results_by_a_column_ascending()
    {
        $result = self::$em->getRepository(Post::class)->filter($this->filter, [
            'createdAt' => 'ASC'
        ]);

        $this->assertCount(3, $result);
        $this->assertEquals('Post 1', $result[0]->getTitle());
        $this->assertEquals('Post 2', $result[1]->getTitle());
        $this->assertEquals('Post 3', $result[2]->getTitle());
    }

    /** @test */
    public function it_orders_results_by_default_value()
    {
        $this->filter->defineFilter(function (FilterBuilder $filterBuilder) {
            $filterBuilder
                ->orderBy('createdAt', 'DESC');
        });

        $result = self::$em->getRepository(Post::class)->filter($this->filter, [
            'createdAt' => 'ASC' // Will be ignored as default is set
        ]);

        $this->assertCount(3, $result);
        $this->assertEquals('Post 3', $result[0]->getTitle());
        $this->assertEquals('Post 2', $result[1]->getTitle());
        $this->assertEquals('Post 1', $result[2]->getTitle());
    }

    /** @test */
    public function it_orders_results_by_value_of_parameter()
    {
        $this->filter->defineFilter(function (FilterBuilder $filterBuilder) {
            $filterBuilder
                ->orderBy('createdAt');
        });

        $result = self::$em->getRepository(Post::class)->filter($this->filter, [
            'createdAt' => 'DESC'
        ]);

        $this->assertCount(3, $result);
        $this->assertEquals('Post 3', $result[0]->getTitle());
        $this->assertEquals('Post 2', $result[1]->getTitle());
        $this->assertEquals('Post 1', $result[2]->getTitle());
    }

    /** @test */
    public function it_ignores_param_value_if_default_sort_order_is_given()
    {
        $this->filter->defineFilter(function (FilterBuilder $filterBuilder) {
            $filterBuilder
                ->orderBy('createdAt', 'DESC');
        });

        $result = self::$em->getRepository(Post::class)->filter($this->filter, [
            'createdAt' => 'ASC'
        ]);

        $this->assertCount(3, $result);
        $this->assertEquals('Post 3', $result[0]->getTitle());
        $this->assertEquals('Post 2', $result[1]->getTitle());
        $this->assertEquals('Post 1', $result[2]->getTitle());
    }

    /** @test */
    public function filter_will_run_if_option_only_with_param_is_true_and_param_is_given()
    {
        $this->filter->defineFilter(function (FilterBuilder $filterBuilder) {
            $filterBuilder
                ->orderBy('createdAt', null, [
                    'only_with_param' => true
                ]);
        });

        $result = self::$em->getRepository(Post::class)->filter($this->filter, [
            'createdAt' => 'DESC'
        ]);

        $this->assertCount(3, $result);
        $this->assertEquals('Post 3', $result[0]->getTitle());
        $this->assertEquals('Post 2', $result[1]->getTitle());
        $this->assertEquals('Post 1', $result[2]->getTitle());
    }

    /** @test */
    public function filter_will_not_run_if_option_only_with_param_is_true_and_param_is_not_given()
    {
        $this->filter->defineFilter(function (FilterBuilder $filterBuilder) {
            $filterBuilder
                ->orderBy('createdAt', null, [
                    'only_with_param' => true
                ]);
        });

        $result = self::$em->getRepository(Post::class)->filter($this->filter);

        $this->assertCount(3, $result);
        $this->assertEquals('Post 2', $result[0]->getTitle());
        $this->assertEquals('Post 1', $result[1]->getTitle());
        $this->assertEquals('Post 3', $result[2]->getTitle());
    }

    /** @test */
    public function order_by_can_have_a_different_name()
    {
        $this->filter->defineFilter(function (FilterBuilder $filterBuilder) {
            $filterBuilder
                ->orderBy('date', null, [
                    'fields' => 'createdAt'
                ]);
        });

        $result = self::$em->getRepository(Post::class)->filter($this->filter, [
            'date' => 'DESC'
        ]);

        $this->assertCount(3, $result);
        $this->assertEquals('Post 3', $result[0]->getTitle());
        $this->assertEquals('Post 2', $result[1]->getTitle());
        $this->assertEquals('Post 1', $result[2]->getTitle());
    }

    /** @test */
    public function it_orders_by_fields_of_relationsships()
    {
        $this->filter->defineFilter(function (FilterBuilder $filterBuilder) {
            $filterBuilder
                ->orderBy('tagName', 'DESC', [
                    'fields' => 'tags.name'
                ]);
        });

        $result = self::$em->getRepository(Post::class)->filter($this->filter);

        $this->assertCount(3, $result);
        $this->assertEquals('Post 2', $result[0]->getTitle());
        $this->assertEquals('Post 3', $result[1]->getTitle());
        $this->assertEquals('Post 1', $result[2]->getTitle());
    }

    /** @test */
    public function multiple_filters_can_be_used()
    {
        $this->filter->defineFilter(function (FilterBuilder $filterBuilder) {
            $filterBuilder
                ->orderBy('content', 'ASC')
                ->orderBy('title', 'ASC');
        });

        $result = self::$em->getRepository(Post::class)->filter($this->filter);

        $this->assertCount(3, $result);
        $this->assertEquals('Post 3', $result[0]->getTitle());
        $this->assertEquals('Post 1', $result[1]->getTitle());
        $this->assertEquals('Post 2', $result[2]->getTitle());
    }

    /** @test */
    public function it_ignores_input_other_than_asc_or_desc()
    {
        $result = self::$em->getRepository(Post::class)->filter($this->filter, [
            'createdAt' => 'nonsense'
        ]);

        $this->assertCount(3, $result);
        $this->assertEquals('Post 1', $result[0]->getTitle());
        $this->assertEquals('Post 2', $result[1]->getTitle());
        $this->assertEquals('Post 3', $result[2]->getTitle());
    }
}
