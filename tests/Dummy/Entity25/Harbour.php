<?php

namespace Queo\DoctrineFilter\Tests\Dummy\Entity25;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Queo\DoctrineFilter\Tests\Dummy\Entity25\HarbourRepo")
 */
class Harbour
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
     * @ORM\OneToMany(targetEntity="Ship", mappedBy="harbour")
     */
    protected $ships;

    public function __construct()
    {
        $this->ships = new ArrayCollection();
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
    public function getShips()
    {
        return $this->ships;
    }

    /**
     * @param mixed $ship
     */
    public function addShip(Ship $ship)
    {
        $this->ships->add($ship);
        $ship->setOwner($this);
    }

    /**
     * @param $ship
     */
    public function removeShip($ship)
    {
        $this->ships->removeElement($ship);
    }
}
