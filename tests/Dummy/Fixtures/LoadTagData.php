<?php

namespace Fludio\DoctrineFilter\Tests\Dummy\Fixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Fludio\DoctrineFilter\Tests\Dummy\Entity\Tag;

class LoadTagData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $tag1 = new Tag();
        $tag1->setName('Tag 1');

        $tag2 = new Tag();
        $tag2->setName('Tag 2');

        $tag3 = new Tag();
        $tag3->setName('Tag 3');

        if ($this->hasReference('category2')) {
            $tag1->setCategory($this->getReference('category1'));
            $tag2->setCategory($this->getReference('category1'));
            $tag3->setCategory($this->getReference('category2'));
        }

        $manager->persist($tag1);
        $manager->persist($tag2);
        $manager->persist($tag3);
        $manager->flush();

        $this->addReference('tag1', $tag1);
        $this->addReference('tag2', $tag2);
        $this->addReference('tag3', $tag3);
    }

    public function getOrder()
    {
        return 5;
    }
}