<?php

namespace Queo\DoctrineFilter\Tests\Filter\Type;

use Queo\DoctrineFilter\FilterBuilder;
use Queo\DoctrineFilter\Type\InstanceOfFilterType;
use Queo\DoctrineFilter\Tests\Dummy\Entity\Bike;
use Queo\DoctrineFilter\Tests\Dummy\Entity\Car;
use Queo\DoctrineFilter\Tests\Dummy\Entity\Transport;
use Queo\DoctrineFilter\Tests\Dummy\Fixtures\LoadTransportData;
use Queo\DoctrineFilter\Tests\Dummy\LoadFixtures;
use Queo\DoctrineFilter\Tests\Dummy\TestCase;
use Queo\DoctrineFilter\Tests\Dummy\Traits\TestFilterTrait;

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
        $vehicles = self::$em->getRepository(Transport::class)->filter($this->filter, [
            'type' => Bike::class
        ]);

        $this->assertCount(1, $vehicles);
        $this->assertInstanceOf(Bike::class, $vehicles[0]);

        $vehicles = self::$em->getRepository(Transport::class)->filter($this->filter, [
            'type' => Car::class
        ]);

        $this->assertCount(2, $vehicles);
        $this->assertInstanceOf(Car::class, $vehicles[0]);
    }

    /** @test */
    public function the_filter_accepts_the_discriminator_map_key()
    {
        $vehicles = self::$em->getRepository(Transport::class)->filter($this->filter, [
            'type' => 'bike'
        ]);

        $this->assertCount(1, $vehicles);
        $this->assertInstanceOf(Bike::class, $vehicles[0]);

        $vehicles = self::$em->getRepository(Transport::class)->filter($this->filter, [
            'type' => 'car'
        ]);

        $this->assertCount(2, $vehicles);
        $this->assertInstanceOf(Car::class, $vehicles[0]);
    }
}
