<?php

namespace BiteCodes\DoctrineFilter\Tests\Dummy\Fixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use BiteCodes\DoctrineFilter\Tests\Dummy\Entity\Post;

class LoadPostCollectionData extends AbstractFixture implements OrderedFixtureInterface
{
    protected $manager;

    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;

        $this->createNewPost('Post 2', 'Some same content', new \DateTime('2016-02-02 12:00:00'), 'tag3');
        $this->createNewPost('Post 1', 'Some same content', new \DateTime('2016-01-01 12:00:00'), 'tag1');
        $this->createNewPost('Post 3', 'Other content', new \DateTime('2016-03-03 12:00:00'), 'tag2');
    }

    protected function createNewPost($title, $content, $createdAt, $tag = null)
    {
        $post = new Post();
        $post->setTitle($title);
        $post->setContent($content);
        $post->setCreatedAt($createdAt);

        if ($tag && $this->hasReference($tag)) {
            $post->addTag($this->getReference($tag));
        }

        $this->manager->persist($post);
        $this->manager->flush();
    }

    public function getOrder()
    {
        return 50;
    }
}
