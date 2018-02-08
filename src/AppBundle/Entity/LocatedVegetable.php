<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LocatedVegetable
 *
 * @ORM\Table(name="located_vegetable")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\LocatedVegetableRepository")
 */
class LocatedVegetable
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Vegetable", inversedBy="locatedVegetable")
     * @ORM\JoinColumn(name="vegetable_id", referencedColumnName="id")
     */
    private $vegetable;

    /**
     * @ORM\ManyToOne(targetEntity="Location", inversedBy="vegetables")
     * @ORM\JoinColumn(name="location_id", referencedColumnName="id")
     */
    private $location;

    /**
     * @var string
     *
     * @ORM\Column(name="surface", type="decimal", precision=10, scale=2)
     */
    private $surface;

    /**
     * @var date
     *
     * @ORM\Column(name="startDate", type="date")
     */
    private $startDate;

    public function __construct()
    {
        $this->startDate = new \DateTime();
    }


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set vegetable
     *
     * @param string $vegetable
     *
     * @return LocatedVegetable
     */
    public function setVegetable($vegetable)
    {
        $this->vegetable = $vegetable;

        return $this;
    }

    /**
     * Get vegetable
     *
     * @return string
     */
    public function getVegetable()
    {
        return $this->vegetable;
    }

    /**
     * Set location
     *
     * @param string $location
     *
     * @return LocatedVegetable
     */
    public function setLocation($location)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Get location
     *
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Set surface
     *
     * @param decimal $surface
     *
     * @return LocatedVegetable
     */
    public function setSurface($surface)
    {
        $this->surface = $surface;

        return $this;
    }

    /**
     * Get surface
     *
     * @return decimal
     */
    public function getSurface()
    {
        return $this->surface;
    }

    public function getStartDate()
    {
        return $this->startDate;
    }

    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }
}

