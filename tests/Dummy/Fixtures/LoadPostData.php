<?php

namespace Fludio\DoctrineFilter\Tests\Dummy\Fixtures;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Fludio\DoctrineFilter\Tests\Dummy\Entity\Post;

class LoadPostData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $post = new Post();
        $post->setTitle('Post title');
        $post->setContent('My post content!');
        $post->setCreatedAt(new \DateTime('2016-01-01 12:00:00'));

        $manager->persist($post);
        $manager->flush();
    }
}