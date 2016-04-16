<?php

namespace BiteCodes\DoctrineFilter\Tests\Dummy\Fixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use BiteCodes\DoctrineFilter\Tests\Dummy\Entity\Post;

class LoadPostData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $post = new Post();
        $post->setTitle('Post title with Tag 1');
        $post->setContent('My post content!');
        $post->setCreatedAt(new \DateTime('2016-01-01 12:00:00'));
        if ($this->hasReference('tag1')) {
            $post->addTag($this->getReference('tag1'));
            $post->addTag($this->getReference('tag2'));
        }

        $manager->persist($post);
        $manager->flush();
    }

    public function getOrder()
    {
        return 10;
    }
}