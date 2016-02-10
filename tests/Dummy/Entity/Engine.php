<?php

namespace Fludio\DoctrineFilter\Tests\Dummy\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable
 */
class Engine
{
    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $horsepower;

    /**
     * var int
     *
     * @ORM\Column(type="integer")
     */
    private $cylinder;

    /**
     * @return int
     */
    public function getHorsepower()
    {
        return $this->horsepower;
    }

    /**
     * @param int $horsepower
     */
    public function setHorsepower($horsepower)
    {
        $this->horsepower = $horsepower;
    }

    /**
     * @return mixed
     */
    public function getCylinder()
    {
        return $this->cylinder;
    }

    /**
     * @param mixed $cylinder
     */
    public function setCylinder($cylinder)
    {
        $this->cylinder = $cylinder;
    }
}
