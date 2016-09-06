<?php

namespace BiteCodes\DoctrineFilter\Tests\Filter\Type;

use BiteCodes\DoctrineFilter\FilterBuilder;
use BiteCodes\DoctrineFilter\Type\BetweenFilterType;
use BiteCodes\DoctrineFilter\Tests\Dummy\Entity\Post;
use BiteCodes\DoctrineFilter\Tests\Dummy\Filter\PostFilter;
use BiteCodes\DoctrineFilter\Tests\Dummy\LoadFixtures;
use BiteCodes\DoctrineFilter\Tests\Dummy\TestCase;
use BiteCodes\DoctrineFilter\Tests\Dummy\Traits\TestFilterTrait;
use Doctrine\ORM\Query\Expr\Andx;

class BetweenFilterTypeTest extends TestCase
{
    use LoadFixtures, TestFilterTrait;

    public function getFilterDefinition()
    {
        return function (FilterBuilder $builder) {
            $builder->add('createdAt', BetweenFilterType::class);
        };
    }

    /** @test */
    public function it_expands()
    {
        $filter = new BetweenFilterType('horsepower', []);
        $filter->expand(new FilterBuilder(), 'a', 'b', 'c', Andx::class);
    }

    /** @test */
    public function it_returns_a_result_if_the_value_is_within_the_range()
    {
        $posts = $this->em->getRepository(Post::class)->filter($this->filter, [
            'createdAt_since' => '2015-12-01 12:00:00',
            'createdAt_until' => '2016-01-31 12:00:00'
        ]);

        $this->assertCount(1, $posts);
        $this->assertEquals('2016-01-01 12:00:00', $posts[0]->getCreatedAt()->format('Y-m-d H:i:s'));
    }

    /** @test */
    public function it_does_not_return_a_result_if_the_value_if_not_within_the_range()
    {
        $posts = $this->em->getRepository(Post::class)->filter($this->filter, [
            'createdAt_since' => '2016-02-01 12:00:00',
            'createdAt_until' => '2016-03-31 12:00:00'
        ]);

        $this->assertCount(0, $posts);
    }

    /** @test */
    public function it_does_not_include_upper_bound_if_option_is_set()
    {
        $this->filter->defineFilter(function (FilterBuilder $filterBuilder) {
            $filterBuilder
                ->add('createdAt', BetweenFilterType::class, [
                    'include_upper_bound' => false
                ]);
        });

        $posts = $this->em->getRepository(Post::class)->filter($this->filter, [
            'createdAt_until' => '2016-01-01 13:00:00',
        ]);

        $this->assertCount(1, $posts);

        $posts = $this->em->getRepository(Post::class)->filter($this->filter, [
            'createdAt_until' => '2016-01-01 12:00:00',
        ]);

        $this->assertCount(0, $posts);
    }

    /** @test */
    public function it_does_not_include_lower_bound_if_option_is_set()
    {
        $this->filter->defineFilter(function (FilterBuilder $filterBuilder) {
            $filterBuilder
                ->add('createdAt', BetweenFilterType::class, [
                    'include_lower_bound' => false
                ]);
        });

        $posts = $this->em->getRepository(Post::class)->filter($this->filter, [
            'createdAt_since' => '2016-01-01 11:00:00',
        ]);

        $this->assertCount(1, $posts);

        $posts = $this->em->getRepository(Post::class)->filter($this->filter, [
            'createdAt_since' => '2016-01-01 12:00:00',
        ]);

        $this->assertCount(0, $posts);
    }

    /** @test */
    public function suffixes_can_be_changed()
    {
        $this->filter->defineFilter(function (FilterBuilder $filterBuilder) {
            $filterBuilder
                ->add('createdAt', BetweenFilterType::class, [
                    'lower_bound_suffix' => 'from',
                    'upper_bound_suffix' => 'to',
                ]);
        });

        $posts = $this->em->getRepository(Post::class)->filter($this->filter, [
            'createdAt_from' => '2016-01-01 11:00:00',
            'createdAt_to' => '2016-01-01 13:00:00',
        ]);

        $this->assertCount(1, $posts);

        $posts = $this->em->getRepository(Post::class)->filter($this->filter, [
            'createdAt_from' => '2016-01-01 13:00:00',
            'createdAt_to' => '2016-01-01 14:00:00',
        ]);

        $this->assertCount(0, $posts);
    }
}
