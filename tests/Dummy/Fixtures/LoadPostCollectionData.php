<?php

namespace Fludio\DoctrineFilter\Tests\Dummy\Fixtures;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Fludio\DoctrineFilter\Tests\Dummy\Entity\Post;

class LoadPostCollectionData implements FixtureInterface
{
    protected $manager;

    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;

        $this->createNewPost('Post 2', 'Content 2', new \DateTime('2016-02-02 12:00:00'));
        $this->createNewPost('Post 1', 'Content 1', new \DateTime('2016-01-01 12:00:00'));
        $this->createNewPost('Post 3', 'Content 3', new \DateTime('2016-03-03 12:00:00'));
    }

    protected function createNewPost($title, $content, $createdAt)
    {
        $post = new Post();
        $post->setTitle($title);
        $post->setContent($content);
        $post->setCreatedAt($createdAt);

        $this->manager->persist($post);
        $this->manager->flush();
    }
}