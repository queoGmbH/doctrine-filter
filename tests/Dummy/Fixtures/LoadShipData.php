<?php

namespace Fludio\DoctrineFilter\Tests\Dummy\Fixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Fludio\DoctrineFilter\Tests\Dummy\Entity25\Engine;
use Fludio\DoctrineFilter\Tests\Dummy\Entity25\Harbour;
use Fludio\DoctrineFilter\Tests\Dummy\Entity25\Ship;

class LoadShipData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $harbour = new Harbour();

        $engine1 = new Engine();
        $engine1->setHorsepower(200);
        $engine1->setCylinder(8);

        $ship1 = new Ship();
        $ship1->setEngine($engine1);

        $engine2 = new Engine();
        $engine2->setHorsepower(300);
        $engine2->setCylinder(10);

        $ship2 = new Ship();
        $ship2->setEngine($engine2);

        $harbour->addShip($ship1);
        $harbour->addShip($ship2);

        $manager->persist($ship1);
        $manager->persist($ship2);
        $manager->persist($harbour);
        $manager->flush();
    }

    public function getOrder()
    {
        return 20;
    }
}
