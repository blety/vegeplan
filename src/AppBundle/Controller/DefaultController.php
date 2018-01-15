<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Grid;
use AppBundle\Entity\Vegetable;
use AppBundle\Form\GridType;
use AppBundle\Form\VegetableType;
use AppBundle\Repository\VegetableRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Repository\VegetableRepository;

class DefaultController extends Controller
{
    /**
     * @Route("/index/{action}/{id}", name="homepage", defaults={"action": "create", "id": null})
     */
    public function indexAction(Request $request, $action, $id)
    {
        $em = $this->getDoctrine()->getManager();

        if (!is_null($id)) {
            $vegetable = $em->getRepository(Vegetable::class)->findOneBy(array('id' => $id));
        }else {
            $vegetable = new Vegetable();
        }

        if ($action == "delete" && !is_null($vegetable)) {
            $em->remove($vegetable);
            $em->flush();

            return $this->redirect($this->generateUrl("homepage"));
        }

        $vegetableForm = $this->createForm(VegetableType::class, $vegetable);
        $vegetableForm->handleRequest($request);
        if ($vegetableForm->isSubmitted() && $vegetableForm->isValid()) {
            $em->getRepository(VegetableRepository::class)->saveVegetable($vegetable);
            $em->persist($vegetable);
            $em->flush();
        }

        $vegetables = $em->getRepository('AppBundle:Vegetable')
            ->findBy(array(), array('name' => 'ASC'));

        return $this->render('backoffice/vegetables.html.twig', array(
            'vegetableForm' => $vegetableForm->createView(),
            'vegetables' => $vegetables,
        ));
    }

    /**
     * @Route("/location", name="location")
     */
    public function locationAction(Request $request)
    {
        $blankGrid = new Grid();
        $blankGrid->setX(10);
        $blankGrid->setY(10);
        $blankGridForm = $this->createForm(GridType::class, $blankGrid);
        $blankGridForm->handleRequest($request);

        if ($blankGridForm->isSubmitted() && $blankGridForm->isValid()) {

        }

        return $this->render('backoffice/location.html.twig', array(
            'gridForm' => $blankGridForm->createView(),
            'grid' => $blankGrid,
        ));
    }
}
