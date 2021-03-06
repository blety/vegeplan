<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Vegetable
 *
 * @ORM\Table(name="vegetable")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\VegetableRepository")
 */
class Vegetable
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
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Unit")
     * @ORM\JoinColumn(nullable=false)
     */
    private $unit;

    /**
     * @var string
     *
     * @ORM\Column(name="price", type="float")
     */
    private $price;

    /**
     * @var string
     *
     * @ORM\Column(name="surfaceNeeded", type="float")
     */
    private $surfaceNeeded;

    /**
     * @var int
     *
     * @ORM\Column(name="objective", type="integer")
     */
    private $objective;

    /**
     * @var int
     *
     * @ORM\Column(name="fieldedYield", type="integer", nullable=true)
     */
    private $fieldedYield;

    /**
     * @var int
     *
     * @ORM\Column(name="shelteredYield", type="integer", nullable=true)
     */
    private $shelteredYield;

    /**
     * @var int
     *
     * @ORM\Column(name="quantity", type="integer")
     */
    private $quantity;

    /**
     * @var int
     *
     * @ORM\Column(name="amount", type="integer")
     */
    private $amount;

    /**
     * @var int
     *
     * @ORM\Column(name="distributionWeeks", type="integer")
     */
    private $distributionWeeks;

    /**
     * @var string
     *
     * @ORM\Column(name="amountPerWeek", type="decimal", precision=10, scale=0)
     */
    private $amountPerWeek;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\SoilType")
     * @ORM\JoinColumn(nullable=false)
     */
    private $soilType;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\VegetableCategory")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @var int
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\LocatedVegetable", mappedBy="vegetable")
     * @ORM\JoinColumn(nullable=true)
     */
    private $locatedVegetable;

    /**
     * @var string
     *
     * @ORM\Column(name="primaryColor", type="string", length=255)
     */
    private $primaryColor;

    /**
     * @var string
     *
     * @ORM\Column(name="secondaryColor", type="string", length=255)
     */
    private $secondaryColor;

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
     * Set unit
     *
     * @param string $unit
     *
     * @return Vegetable
     */
    public function setUnit($unit)
    {
        $this->unit = $unit;

        return $this;
    }

    /**
     * Get unit
     *
     * @return string
     */
    public function getUnit()
    {
        return $this->unit;
    }

    /**
     * Set price
     *
     * @param string $price
     *
     * @return Vegetable
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set surfaceNeeded
     *
     * @param string $surfaceNeeded
     *
     * @return Vegetable
     */
    public function setSurfaceNeeded($surfaceNeeded)
    {
        $this->surfaceNeeded = $surfaceNeeded;

        return $this;
    }

    /**
     * Get surfaceNeeded
     *
     * @return string
     */
    public function getSurfaceNeeded()
    {
        return $this->surfaceNeeded;
    }

    /**
     * Set objective
     *
     * @param integer $objective
     *
     * @return Vegetable
     */
    public function setObjective($objective)
    {
        $this->objective = $objective;

        return $this;
    }

    /**
     * Get objective
     *
     * @return int
     */
    public function getObjective()
    {
        return $this->objective;
    }

    /**
     * Set fieldedYield
     *
     * @param integer $fieldedYield
     *
     * @return Vegetable
     */
    public function setFieldedYield($fieldedYield)
    {
        $this->fieldedYield = $fieldedYield;

        return $this;
    }

    /**
     * Get fieldedYield
     *
     * @return int
     */
    public function getFieldedYield()
    {
        return $this->fieldedYield;
    }

    /**
     * Set shelteredYield
     *
     * @param integer $shelteredYield
     *
     * @return Vegetable
     */
    public function setShelteredYield($shelteredYield)
    {
        $this->shelteredYield = $shelteredYield;

        return $this;
    }

    /**
     * Get shelteredYield
     *
     * @return int
     */
    public function getShelteredYield()
    {
        return $this->shelteredYield;
    }

    /**
     * Set quantity
     *
     * @param integer $quantity
     *
     * @return Vegetable
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set amount
     *
     * @param integer $amount
     *
     * @return Vegetable
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return int
     */
    public function getAmount()
    {
        return $this->quantity * $this->price;
    }

    /**
     * Set distributionWeeks
     *
     * @param integer $distributionWeeks
     *
     * @return Vegetable
     */
    public function setDistributionWeeks($distributionWeeks)
    {
        $this->distributionWeeks = $distributionWeeks;

        return $this;
    }

    /**
     * Get distributionWeeks
     *
     * @return int
     */
    public function getDistributionWeeks()
    {
        return $this->distributionWeeks;
    }

    /**
     * Set amountPerWeek
     *
     * @param string $amountPerWeek
     *
     * @return Vegetable
     */
    public function setAmountPerWeek($amountPerWeek)
    {
        $this->amountPerWeek = $amountPerWeek;

        return $this;
    }

    /**
     * Get amountPerWeek
     *
     * @return string
     */
    public function getAmountPerWeek()
    {
        if ($this->getDistributionWeeks() !== 0) {
            return $this->getQuantity() / $this->getDistributionWeeks();
        }else {
            return 0;
        }
    }

    /**
     * Set category
     *
     * @param integer $category
     *
     * @return Vegetable
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return int
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Vegetable
     */
    public function setName($name)
    {
        $this->name = $name;

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

    /**
     * Set located vegetable
     *
     * @param integer $locatedVegetable
     *
     * @return Vegetable
     */
    public function setLocatedVegetable($locatedVegetable)
    {
        $this->locatedVegetable = $locatedVegetable;

        return $this;
    }

    /**
     * Get located vegetable
     *
     * @return int
     */
    public function getLocatedVegetable()
    {
        return $this->locatedVegetable;
    }

    public function getSoilType()
    {
        return $this->soilType;
    }

    public function setSoilType(SoilType $soilType)
    {
        $this->soilType = $soilType;

        return $this;
    }

    public function getPrimaryColor()
    {
        return $this->primaryColor;
    }

    public function setPrimaryColor($primaryColor)
    {
        $this->primaryColor = $primaryColor;

        return $this;
    }

    public function getSecondaryColor()
    {
        return $this->secondaryColor;
    }

    public function setSecondaryColor($secondaryColor)
    {
        $this->secondaryColor = $secondaryColor;

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }
}
