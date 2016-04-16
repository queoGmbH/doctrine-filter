<?php

namespace BiteCodes\DoctrineFilter\Tests\Dummy\Fixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use BiteCodes\DoctrineFilter\Tests\Dummy\Entity\Category;

class LoadCategoryData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $category1 = new Category();
        $category1->setName('Category 1');

        $category2 = new Category();
        $category2->setName('Category 2');

        $manager->persist($category1);
        $manager->persist($category2);

        $manager->flush();

        $this->addReference('category1', $category1);
        $this->addReference('category2', $category2);
    }

    public function getOrder()
    {
        return 1;
    }
}