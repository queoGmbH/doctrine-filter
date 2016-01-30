<?php

namespace Fludio\DoctrineFilter\Tests\Dummy\Fixtures;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Fludio\DoctrineFilter\Tests\Dummy\Entity\Bike;
use Fludio\DoctrineFilter\Tests\Dummy\Entity\Car;

class LoadTransportData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $car = new Car();

        $manager->persist($car);
        $manager->flush();

        $bike = new Bike();

        $manager->persist($bike);
        $manager->flush();
    }
}