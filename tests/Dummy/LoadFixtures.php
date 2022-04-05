<?php

namespace Queo\DoctrineFilter\Tests\Dummy;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Queo\DoctrineFilter\Tests\Dummy\Fixtures\LoadPostData;

trait LoadFixtures
{
    /** @before */
    public function doLoadFixtures()
    {
        $loader = new Loader();
        foreach ($this->loadFixtures() as $fixture) {
            $loader->addFixture($fixture);
        }

        $purger = new ORMPurger();
        $executor = new ORMExecutor(self::$em, $purger);
        $executor->execute($loader->getFixtures());
    }

    public function loadFixtures()
    {
        return [
            new LoadPostData()
        ];
    }
}