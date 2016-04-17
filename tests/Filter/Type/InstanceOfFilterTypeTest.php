<?php

namespace BiteCodes\DoctrineFilter\Tests\Filter\Type;

use BiteCodes\DoctrineFilter\FilterBuilder;
use BiteCodes\DoctrineFilter\Type\InstanceOfFilterType;
use BiteCodes\DoctrineFilter\Tests\Dummy\Entity\Bike;
use BiteCodes\DoctrineFilter\Tests\Dummy\Entity\Car;
use BiteCodes\DoctrineFilter\Tests\Dummy\Entity\Transport;
use BiteCodes\DoctrineFilter\Tests\Dummy\Fixtures\LoadTransportData;
use BiteCodes\DoctrineFilter\Tests\Dummy\LoadFixtures;
use BiteCodes\DoctrineFilter\Tests\Dummy\TestCase;
use BiteCodes\DoctrineFilter\Tests\Dummy\Traits\TestFilterTrait;

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
            'type' => Bike::class
        ]);

        $this->assertCount(1, $vehicles);
        $this->assertInstanceOf(Bike::class, $vehicles[0]);

        $vehicles = $this->em->getRepository(Transport::class)->filter($this->filter, [
            'type' => Car::class
        ]);

        $this->assertCount(2, $vehicles);
        $this->assertInstanceOf(Car::class, $vehicles[0]);
    }

    /** @test */
    public function the_filter_accepts_the_discriminator_map_key()
    {
        if (!$this->isDoctrineVersion('2.3')) {
            $this->markTestSkipped('Doctrine can not handle discriminator maps for INSTANCE OF');
        }

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
