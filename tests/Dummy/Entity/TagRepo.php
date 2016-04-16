<?php

namespace BiteCodes\DoctrineFilter\Tests\Dummy\Entity;

use Doctrine\ORM\EntityRepository;
use BiteCodes\DoctrineFilter\Traits\EntityFilterTrait;

class TagRepo extends EntityRepository
{
    use EntityFilterTrait;
}