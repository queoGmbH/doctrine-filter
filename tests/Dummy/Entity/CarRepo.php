<?php

namespace Fludio\DoctrineFilter\Tests\Dummy\Entity;

use Doctrine\ORM\EntityRepository;
use Fludio\DoctrineFilter\Traits\EntityFilterTrait;

class CarRepo extends EntityRepository
{
    use EntityFilterTrait;
}