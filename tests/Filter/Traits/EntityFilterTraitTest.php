<?php

namespace Fludio\DoctrineFilter\Tests\Filter\Traits;

use Fludio\DoctrineFilter\FilterBuilder;
use Fludio\DoctrineFilter\Tests\Dummy\Entity\Post;
use Fludio\DoctrineFilter\Tests\Dummy\Fixtures\LoadPostCollectionData;
use Fludio\DoctrineFilter\Tests\Dummy\LoadFixtures;
use Fludio\DoctrineFilter\Tests\Dummy\TestCase;
use Fludio\DoctrineFilter\Tests\Dummy\Traits\TestFilterTrait;

class EntityFilterTraitTest extends TestCase
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
    public function it_paginates_results()
    {
        $results = $this->em->getRepository(Post::class)->paginate($this->filter, [], 0, 2);

        $this->assertCount(2, $results);
        $this->assertEquals('Post 3', $results[0]->getTitle());
        $this->assertEquals('Post 2', $results[1]->getTitle());

        $results = $this->em->getRepository(Post::class)->paginate($this->filter, [], 2, 2);

        $this->assertCount(1, $results);
        $this->assertEquals('Post 1', $results[0]->getTitle());

        $results = $this->em->getRepository(Post::class)->paginate($this->filter, [], 3, 2);

        $this->assertCount(0, $results);
    }

}
