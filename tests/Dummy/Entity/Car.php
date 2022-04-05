<?php

namespace Queo\DoctrineFilter\Tests\Dummy\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Car
 *
 * @ORM\Entity(repositoryClass="Queo\DoctrineFilter\Tests\Dummy\Entity\CarRepo")
 */
class Car extends Transport
{
    /**
     * @ORM\ManyToOne(targetEntity="Person", inversedBy="cars")
     * @ORM\JoinColumn(name="owner_id", referencedColumnName="id")
     */
    protected $owner;

    /**
     * @return mixed
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * @param mixed $owner
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;
    }
}
