<?php

namespace BiteCodes\DoctrineFilter\Tests\Dummy\Fixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use BiteCodes\DoctrineFilter\Tests\Dummy\Entity\Bike;
use BiteCodes\DoctrineFilter\Tests\Dummy\Entity\Car;
use BiteCodes\DoctrineFilter\Tests\Dummy\Entity\Engine;

class LoadTransportData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $car1 = new Car();
        $manager->persist($car1);
        $manager->flush();

        $car2 = new Car();
        $manager->persist($car2);
        $manager->flush();

        $bike = new Bike();

        $manager->persist($bike);
        $manager->flush();

        $this->addReference('car', $car1);
    }

    public function getOrder()
    {
        return 20;
    }
}
