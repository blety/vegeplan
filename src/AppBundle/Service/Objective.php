<?php

namespace AppBundle\Service;

use AppBundle\Entity\Vegetable;
use Doctrine\ORM\EntityManagerInterface;

class Objective
{
    private $entityManager;

    public function __construct(EntityManagerInterface $em)
    {
        $this->entityManager = $em;
    }

    public function calculateObjectives()
    {
        $objectives = array();

        $vegetables = $this->entityManager->getRepository('AppBundle:Vegetable')
            ->findAll();

        $locatedVegetables = $this->entityManager->getRepository('AppBundle:LocatedVegetable')
            ->findAll();

        foreach ($vegetables as $vegetable) {
            foreach ($locatedVegetables as $locatedVegetable) {
                if ($vegetable->getId() === $locatedVegetable->getVegetable()->getId()) {
                    $plantationDate = $locatedVegetable->getStartDate();
                    $distributionWeeks = $locatedVegetable->getVegetable()->getDistributionWeeks();
                    $interval = new \DateInterval('P'.(7*$distributionWeeks).'D');
                    $plantationDate->add($interval);
                    if (array_key_exists($plantationDate->format("Y"), $objectives)) {
                        $objectives[$plantationDate->format("Y")][$vegetable->getId()]['vegetableId'] = $locatedVegetable->getVegetable()->getId();
                        if (array_key_exists('surface', $objectives[$plantationDate->format("Y")][$vegetable->getId()])) {
                            $objectives[$plantationDate->format("Y")][$vegetable->getId()]['surface'] += $locatedVegetable->getSurface();
                        } else {
                            $objectives[$plantationDate->format("Y")][$vegetable->getId()]['surface'] = $locatedVegetable->getSurface();
                        }
                    } else {
                        $objectives[$plantationDate->format("Y")] = array($vegetable->getId() => ['vegetableId' => $locatedVegetable->getVegetable()->getId(), 'surface' => $locatedVegetable->getSurface()]);
                    }
                }
            }
        }

        krsort($objectives);

        return $objectives;
    }

    public function calculateObjective(Vegetable $vegetable)
    {
        $objective = array();

        $locatedVegetables = $this->entityManager->getRepository('AppBundle:LocatedVegetable')
            ->findAll();

        foreach ($locatedVegetables as $locatedVegetable) {
            if ($vegetable->getId() === $locatedVegetable->getVegetable()->getId()) {
                $plantationDate = $locatedVegetable->getStartDate();
                $distributionWeeks = $locatedVegetable->getVegetable()->getDistributionWeeks();
                $interval = new \DateInterval('P'.(7*$distributionWeeks).'D');
                $plantationDate->add($interval);
                $now = new \DateTime();
                if ($plantationDate->format("Y") == $now->format("Y")) {
                    $objective['vegetableId'] = $locatedVegetable->getVegetable()->getId();
                    if (array_key_exists('surface', $objective)) {
                        $objective['surface'] += $locatedVegetable->getSurface();
                    } else {
                        $objective['surface'] = $locatedVegetable->getSurface();
                    }
                }
            }
        }

        if (array_key_exists('surface', $objective)) {
            $objective['progress'] = number_format($objective['surface'] / $vegetable->getObjective() * 100, 2);
        }

        if (empty($objective)) {
            $objective['vegetableId'] = $vegetable->getId();
            $objective['surface'] = 0.00;
            $objective['progress'] = 0.00;
        }

        return $objective;
    }
}