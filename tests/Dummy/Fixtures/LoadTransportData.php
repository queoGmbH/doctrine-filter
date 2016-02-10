<?php

namespace Fludio\DoctrineFilter\Tests\Dummy\Fixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Fludio\DoctrineFilter\Tests\Dummy\Entity\Bike;
use Fludio\DoctrineFilter\Tests\Dummy\Entity\Car;
use Fludio\DoctrineFilter\Tests\Dummy\Entity\Engine;

class LoadTransportData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $engine = new Engine();
        $engine->setCylinder(8);
        $engine->setHorsepower(220);

        $car = new Car();
        $car->setEngine($engine);

        $manager->persist($car);
        $manager->flush();

        $bike = new Bike();

        $manager->persist($bike);
        $manager->flush();

        $this->addReference('car', $car);
    }

    public function getOrder()
    {
        return 20;
    }
}
