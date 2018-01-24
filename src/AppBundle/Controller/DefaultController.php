<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Location;
use AppBundle\Entity\Vegetable;
use AppBundle\Form\LocationType;
use AppBundle\Form\VegetableType;
use AppBundle\Repository\VegetableRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

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
        $em = $this->getDoctrine()->getManager();

        $location = new Location();

        $em->persist($location);
        $em->flush();

        $locationForm = $this->createForm(LocationType::class, $location);

        $allVegetables = $em->getRepository('AppBundle:Vegetable')->findAll();

        $locationForm->handleRequest($request);
        if ($locationForm->isSubmitted() && $locationForm->isValid()) {
            $em->persist($location);
            $em->flush();
        }

        return $this->render('backoffice/location.html.twig', array(
            'locationForm' => $locationForm->createView(),
            'location' => $location,
            'allVegetables' => $allVegetables,
        ));
    }

    /**
     * @Route("update-location-vegetable/{locationId}/{vegetableId}", name="update_location_vegetable", options={"expose"=true})
     */
    public function updateLocationVegetable(Request $request, $locationId, $vegetableId)
    {
        $em = $this->getDoctrine()->getManager();

        $vegetable = $em->getRepository('AppBundle:Vegetable')->findOneBy(array('id' => $vegetableId));
        $location = $em->getRepository('AppBundle:Location')->findOneBy(array('id' => $locationId));

        $location->addVegetable($vegetable);

        $em->persist($location);
        $em->flush();

        return new JsonResponse(true);
    }
}
