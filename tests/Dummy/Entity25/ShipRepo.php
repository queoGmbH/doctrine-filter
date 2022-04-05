<?php

namespace Queo\DoctrineFilter\Tests\Dummy\Entity25;

use Doctrine\ORM\EntityRepository;
use Queo\DoctrineFilter\Traits\EntityFilterTrait;

class ShipRepo extends EntityRepository
{
    use EntityFilterTrait;
}