<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Vegetable;
use AppBundle\Form\VegetableType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $vegetable = new Vegetable();
        $vegetableForm = $this->createForm(VegetableType::class, $vegetable);
        $vegetableForm->handleRequest($request);
        if ($vegetableForm->isSubmitted() && $vegetableForm->isValid()) {
            $surfaceNeeded = 0;
            if ($vegetable->getLocation()->getName() == 'Plein champ' && !is_null($vegetable->getFieldedYield()) && $vegetable->getFieldedYield() !== 0) {
                $surfaceNeeded = $vegetable->getObjective() / $vegetable->getFieldedYield();
            }elseif ($vegetable->getLocation()->getName() == 'Abris' && !is_null($vegetable->getShelteredYield()) && $vegetable->getShelteredYield() !== 0) {
                $surfaceNeeded = $vegetable->getObjective() / $vegetable->getShelteredYield();
            }
            $vegetable->setSurfaceNeeded($surfaceNeeded);
            $vegetable->setAmount($vegetable->getQuantity() * $vegetable->getPrice());
            $amountPerWeek = $vegetable->getDistributionWeeks() === 0 ? 0 : $vegetable->getQuantity() / $vegetable->getDistributionWeeks();
            $vegetable->setAmountPerWeek($amountPerWeek);
            $em->persist($vegetable);
            $em->flush();
        }

        $vegetables = $em->getRepository('AppBundle:Vegetable')
            ->findAll();

        return $this->render('backoffice/vegetables.html.twig', array(
            'vegetableForm' => $vegetableForm->createView(),
            'vegetables' => $vegetables,
        ));
    }
}
