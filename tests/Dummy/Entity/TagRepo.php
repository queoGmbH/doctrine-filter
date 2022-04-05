<?php

namespace Queo\DoctrineFilter\Tests\Dummy\Entity;

use Doctrine\ORM\EntityRepository;
use Queo\DoctrineFilter\Traits\EntityFilterTrait;

class TagRepo extends EntityRepository
{
    use EntityFilterTrait;
}