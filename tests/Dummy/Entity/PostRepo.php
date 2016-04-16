<?php

namespace BiteCodes\DoctrineFilter\Tests\Dummy\Entity;

use Doctrine\ORM\EntityRepository;
use BiteCodes\DoctrineFilter\Traits\EntityFilterTrait;

class PostRepo extends EntityRepository
{
    use EntityFilterTrait;
}