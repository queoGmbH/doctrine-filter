<?php

namespace BiteCodes\DoctrineFilter\Tests\Dummy\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="BiteCodes\DoctrineFilter\Tests\Dummy\Entity\PersonRepo")
 */
class Person
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @var ArrayCollection|Car[]
     *
     * @ORM\OneToMany(targetEntity="Car", mappedBy="owner")
     */
    protected $cars;

    public function __construct()
    {
        $this->cars = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getCars()
    {
        return $this->cars;
    }

    /**
     * @param mixed $car
     */
    public function addCar(Car $car)
    {
        $this->cars->add($car);
        $car->setOwner($this);
    }

    /**
     * @param $car
     */
    public function removeCar($car)
    {
        $this->cars->removeElement($car);
    }
}
