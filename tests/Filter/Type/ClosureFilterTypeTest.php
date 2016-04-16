<?php

namespace BiteCodes\DoctrineFilter\Tests\Filter\Type;

use Doctrine\ORM\QueryBuilder;
use BiteCodes\DoctrineFilter\FilterBuilder;
use BiteCodes\DoctrineFilter\Tests\Dummy\Entity\Post;
use BiteCodes\DoctrineFilter\Tests\Dummy\Fixtures\LoadPostCollectionData;
use BiteCodes\DoctrineFilter\Tests\Dummy\LoadFixtures;
use BiteCodes\DoctrineFilter\Tests\Dummy\TestCase;
use BiteCodes\DoctrineFilter\Tests\Dummy\Traits\TestFilterTrait;

class ClosureFilterTypeTest extends TestCase
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
                ->add('content', function (QueryBuilder $qb, $table, $field, \Closure $getValue) {
                    $qb
                        ->andWhere(
                            $qb->expr()->orX(
                                $qb->expr()->eq($table . '.title', $getValue()),
                                $qb->expr()->eq($table . '.content', $getValue())
                            )
                        );
                });
        };
    }

    /** @test */
    public function it_includes_the_values_correctly_to_the_query()
    {
        $posts = $this->em->getRepository(Post::class)->filter($this->filter, [
            'content' => 'Post 1'
        ]);

        $this->assertCount(1, $posts);

        $posts = $this->em->getRepository(Post::class)->filter($this->filter, [
            'content' => 'Other content'
        ]);

        $this->assertCount(1, $posts);
    }
}
