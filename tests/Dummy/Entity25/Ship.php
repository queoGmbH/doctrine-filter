<?php

namespace Queo\DoctrineFilter\Tests\Dummy\Entity25;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Ship
 *
 * @ORM\Entity(repositoryClass="Queo\DoctrineFilter\Tests\Dummy\Entity25\ShipRepo")
 */
class Ship
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @var Engine
     *
     * @ORM\Embedded(class="Engine")
     */
    private $engine;

    /**
     * @ORM\ManyToOne(targetEntity="Harbour", inversedBy="ships")
     * @ORM\JoinColumn(name="harbour_id", referencedColumnName="id")
     */
    protected $harbour;

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
    public function getHarbour()
    {
        return $this->harbour;
    }

    /**
     * @param mixed $harbour
     */
    public function setOwner($harbour)
    {
        $this->harbour = $harbour;
    }

    /**
     * @return mixed
     */
    public function getEngine()
    {
        return $this->engine;
    }

    /**
     * @param mixed $engine
     */
    public function setEngine($engine)
    {
        $this->engine = $engine;
    }
}
