<?php

namespace Fludio\DoctrineFilter\Tests\Filter\Type;

use Fludio\DoctrineFilter\FilterBuilder;
use Fludio\DoctrineFilter\Type\InstanceOfFilterType;
use Fludio\DoctrineFilter\Tests\Dummy\Entity\Bike;
use Fludio\DoctrineFilter\Tests\Dummy\Entity\Car;
use Fludio\DoctrineFilter\Tests\Dummy\Entity\Transport;
use Fludio\DoctrineFilter\Tests\Dummy\Fixtures\LoadTransportData;
use Fludio\DoctrineFilter\Tests\Dummy\LoadFixtures;
use Fludio\DoctrineFilter\Tests\Dummy\TestCase;
use Fludio\DoctrineFilter\Tests\Dummy\Traits\TestFilterTrait;

class InstanceOfFilterTypeTest extends TestCase
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
                ->add('type', InstanceOfFilterType::class);
        };
    }

    /** @test */
    public function it_returns_only_instances_of_the_given_type()
    {
        $vehicles = $this->em->getRepository(Transport::class)->filter($this->filter, [
            'type' => 'bike'
        ]);

        $this->assertCount(1, $vehicles);
        $this->assertInstanceOf(Bike::class, $vehicles[0]);

        $vehicles = $this->em->getRepository(Transport::class)->filter($this->filter, [
            'type' => 'car'
        ]);

        $this->assertCount(2, $vehicles);
        $this->assertInstanceOf(Car::class, $vehicles[0]);
    }
}
