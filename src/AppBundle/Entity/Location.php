<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Location
 *
 * @ORM\Table(name="location")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\LocationRepository")
 */
class Location
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(name="surface", type="decimal", precision=10, scale=2)
     */
    private $surface;

    /**
     * @ORM\OneToMany(targetEntity="LocatedVegetable", mappedBy="location")
     * @ORM\JoinColumn(nullable=false)
     */
    private $vegetables;

    /**
     * @ORM\Column(name="sheltered", type="boolean")
     */
    private $sheltered;

    public function __construct()
    {
        $this->name = 'Nouveau terrain';
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
     * Set name
     *
     * @param string $name
     *
     * @return Unit
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function getVegetables()
    {
        return $this->vegetables;
    }

    public function addVegetable(Vegetable $vegetable)
    {
        $this->vegetables[] = $vegetable;

        return $this;
    }

    public function removeVegetable(LocatedVegetable $vegetable)
    {
        $this->vegetables->removeElement($vegetable);

        return $this;
    }


    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    public function getSurface()
    {
        return $this->surface;
    }

    public function setSurface($surface)
    {
        $this->surface = $surface;

        return $this;
    }

    public function isSheltered()
    {
        return $this->sheltered;
    }

    public function setSheltered($sheltered)
    {
        $this->sheltered = $sheltered;

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }
}

