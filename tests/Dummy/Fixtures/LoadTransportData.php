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
        $engine1 = new Engine();
        $engine1->setCylinder(8);
        $engine1->setHorsepower(220);

        $car1 = new Car();
        $car1->setEngine($engine1);

        $manager->persist($car1);
        $manager->flush();

        $engine2 = new Engine();
        $engine2->setCylinder(8);
        $engine2->setHorsepower(280);

        $car2 = new Car();
        $car2->setEngine($engine2);

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
