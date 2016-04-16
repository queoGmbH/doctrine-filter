<?php

namespace BiteCodes\DoctrineFilter\Tests\Dummy\Entity25;

use Doctrine\ORM\EntityRepository;
use BiteCodes\DoctrineFilter\Traits\EntityFilterTrait;

class ShipRepo extends EntityRepository
{
    use EntityFilterTrait;
}