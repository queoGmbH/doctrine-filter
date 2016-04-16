<?php

namespace BiteCodes\DoctrineFilter\Tests\Dummy\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Post
 *
 * @ORM\Entity(repositoryClass="BiteCodes\DoctrineFilter\Tests\Dummy\Entity\TransportRepo")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({"car" = "Car", "bike" = "Bike"})
 */
abstract class Transport
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    protected $id;
}