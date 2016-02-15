<?php

namespace Fludio\DoctrineFilter\Tests\Filter\Type;

use Doctrine\ORM\QueryBuilder;
use Fludio\DoctrineFilter\FilterBuilder;
use Fludio\DoctrineFilter\Tests\Dummy\Entity\Car;
use Fludio\DoctrineFilter\Tests\Dummy\Fixtures\LoadTransportData;
use Fludio\DoctrineFilter\Tests\Dummy\LoadFixtures;
use Fludio\DoctrineFilter\Tests\Dummy\TestCase;
use Fludio\DoctrineFilter\Tests\Dummy\Traits\TestFilterTrait;

class ClosureFilterTypeTest extends TestCase
{
    use LoadFixtures, TestFilterTrait;

    public function loadFixtures()
    {
        return [
            new LoadTransportData()
        ];
    }


    public function getFilterDefinition()
    {
        return function (FilterBuilder $builder) {
            $builder
                ->add('engine', function (QueryBuilder $qb, $table, $field, \Closure $getValue) {
                    $qb
                        ->andWhere(
                            $qb->expr()->orX(
                                $qb->expr()->eq($table . '.engine.horsepower', $getValue()),
                                $qb->expr()->eq($table . '.engine.cylinder', $getValue())
                            )
                        );
                }, ['fields' => 'engine']);
        };
    }

    /** @test */
    public function it_includes_the_values_correctly_to_the_query()
    {
        $cars = $this->em->getRepository(Car::class)->filter($this->filter, [
            'engine' => 8
        ]);

        $this->assertCount(2, $cars);

        $cars = $this->em->getRepository(Car::class)->filter($this->filter, [
            'engine' => 220
        ]);

        $this->assertCount(1, $cars);
    }
}
