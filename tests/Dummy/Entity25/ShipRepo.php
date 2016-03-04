<?php

namespace Fludio\DoctrineFilter\Tests\Dummy\Entity25;

use Doctrine\ORM\EntityRepository;
use Fludio\DoctrineFilter\Traits\EntityFilterTrait;

class ShipRepo extends EntityRepository
{
    use EntityFilterTrait;
}