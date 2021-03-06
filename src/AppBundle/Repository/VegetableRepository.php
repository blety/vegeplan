<?php

namespace AppBundle\Repository;

/**
 * VegetableRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class VegetableRepository extends \Doctrine\ORM\EntityRepository
{
    public function saveVegetable(&$vegetable)
    {
        $surfaceNeeded = 0;
        if ($vegetable->getSoilType()->getName() == 'Plein champ' && !is_null($vegetable->getFieldedYield()) && $vegetable->getFieldedYield() !== 0) {
            $surfaceNeeded = $vegetable->getObjective() / $vegetable->getFieldedYield();
        }elseif ($vegetable->getSoilType()->getName() == 'Abris' && !is_null($vegetable->getShelteredYield()) && $vegetable->getShelteredYield() !== 0) {
            $surfaceNeeded = $vegetable->getObjective() / $vegetable->getShelteredYield();
        }
        $vegetable->setSurfaceNeeded($surfaceNeeded);
        $vegetable->setAmount($vegetable->getQuantity() * $vegetable->getPrice());
        $amountPerWeek = $vegetable->getDistributionWeeks() === 0 ? 0 : $vegetable->getQuantity() / $vegetable->getDistributionWeeks();
        $vegetable->setAmountPerWeek($amountPerWeek);
    }
}
