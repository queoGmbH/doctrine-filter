<?php

namespace BiteCodes\DoctrineFilter\Tests\Dummy\Fixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use BiteCodes\DoctrineFilter\Tests\Dummy\Entity\Person;

class LoadPersonData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $person = new Person();

        if ($this->hasReference('car')) {
            $person->addCar($this->getReference('car'));
        }

        $manager->persist($person);
        $manager->flush();
    }

    public function getOrder()
    {
        return 50;
    }
}
