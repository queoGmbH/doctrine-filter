<?php

namespace BiteCodes\DoctrineFilter\Tests\Dummy\Entity;

use Doctrine\ORM\EntityRepository;
use BiteCodes\DoctrineFilter\Traits\EntityFilterTrait;

class PersonRepo extends EntityRepository
{
    use EntityFilterTrait;
}