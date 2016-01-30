<?php

namespace Fludio\DoctrineFilter\Tests\Filter\OrderBy;

use Fludio\DoctrineFilter\Filter\FilterBuilder;
use Fludio\DoctrineFilter\Tests\Dummy\Entity\Post;
use Fludio\DoctrineFilter\Tests\Dummy\Fixtures\LoadPostCollectionData;
use Fludio\DoctrineFilter\Tests\Dummy\LoadFixtures;
use Fludio\DoctrineFilter\Tests\Dummy\TestCase;
use Fludio\DoctrineFilter\Tests\Dummy\Traits\TestFilterTrait;

class OrderByTypeTest extends TestCase
{
    use LoadFixtures, TestFilterTrait;

    public function loadFixtures()
    {
        return [
            new LoadPostCollectionData()
        ];
    }

    public function getFilterDefinition()
    {
        return function (FilterBuilder $builder) {
            $builder
                ->orderBy('createdAt', 'DESC');
        };
    }

    /** @test */
    public function it_orders_results_by_a_column()
    {
        $result = $this->em->getRepository(Post::class)->filter($this->filter, []);

        $this->assertCount(3, $result);
        $this->assertEquals('Post 3', $result[0]->getTitle());
        $this->assertEquals('Post 2', $result[1]->getTitle());
        $this->assertEquals('Post 1', $result[2]->getTitle());
    }
}
