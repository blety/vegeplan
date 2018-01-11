<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Vegetable;
use AppBundle\Form\VegetableType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Repository\VegetableRepository;

class DefaultController extends Controller
{
    /**
     * @Route("/{id}", name="homepage")
     */
    public function indexAction(Request $request, $id = null)
    {
        $em = $this->getDoctrine()->getManager();
        $vegetable = is_null($id) ? new Vegetable() : $em->getRepository(VegetableRepository::class)->findOneBy(array('id' => $id));
        $vegetableForm = $this->createForm(VegetableType::class, $vegetable);
        $vegetableForm->handleRequest($request);
        if ($vegetableForm->isSubmitted() && $vegetableForm->isValid()) {
            $em->getRepository(VegetableRepository::class)->saveVegetable($vegetable);
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
