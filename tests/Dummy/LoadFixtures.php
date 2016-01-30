<?php

namespace Fludio\DoctrineFilter\Tests\Dummy;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Fludio\DoctrineFilter\Tests\Dummy\Fixtures\LoadPostData;

trait LoadFixtures
{
    /** @before */
    public function loadFixtures()
    {
        $loader = new Loader();
        $loader->addFixture(new LoadPostData());

        $purger = new ORMPurger();
        $executor = new ORMExecutor($this->em, $purger);
        $executor->execute($loader->getFixtures());
    }
}