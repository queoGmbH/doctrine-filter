<?php

namespace Queo\DoctrineFilter\Tests\Dummy\Entity25;

use Doctrine\ORM\EntityRepository;
use Queo\DoctrineFilter\Traits\EntityFilterTrait;

class HarbourRepo extends EntityRepository
{
    use EntityFilterTrait;
}