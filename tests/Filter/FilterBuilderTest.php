<?php

namespace Fludio\DoctrineFilter\Tests\Filter;

use Fludio\DoctrineFilter\Tests\Dummy\Entity\Post;
use Fludio\DoctrineFilter\Tests\Dummy\Filter\PostFilter;
use Fludio\DoctrineFilter\Tests\Dummy\TestCase;

class FilterBuilderTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $p = new Post();
        $p->setTitle('Test');
        $p->setContent('My content');

        $this->em->persist($p);
        $this->em->flush();
    }

    public function testA()
    {
        $posts = $this->em->getRepository(Post::class)->filter([
            'title' => 'Test'
        ], new PostFilter());

        $this->assertCount(1, $posts);
        $this->assertEquals('Test', $posts[0]->getTitle());
    }

    public function tearDown()
    {
        $this->em->getConnection()->close();
        $this->em->close();
    }
}
