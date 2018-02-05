<?php

namespace AppBundle\Controller;

use AppBundle\Entity\LocatedVegetable;
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
            $em->getRepository(Vegetable::class)->saveVegetable($vegetable);
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
     * @Route("/location/{locationId}", name="location")
     */
    public function locationAction(Request $request, $locationId)
    {
        $em = $this->getDoctrine()->getManager();

        $location = $em->getRepository('AppBundle:Location')
            ->findOneBy(array('id' => $locationId));

        $locatedVegetables = array();
        foreach ($location->getVegetables() as $k => $locatedVegetable) {
            $locatedVegetables[$k]['vegetable'] = $locatedVegetable;
            $locatedVegetables[$k]['surface'] = $locatedVegetable->getSurface();
        }

        if (is_null($location)) {
            $location = new Location();

            $em->persist($location);
            $em->flush();
        }

        $locationForm = $this->createForm(LocationType::class, $location);

        $allVegetables = $em->getRepository('AppBundle:Vegetable')->findAll();

        $locationForm->handleRequest($request);
        if ($locationForm->isSubmitted() && $locationForm->isValid()) {
            //$em->persist($location);
            //$em->flush();
        }

        return $this->render('backoffice/location.html.twig', array(
            'locationForm' => $locationForm->createView(),
            'location' => $location,
            'allVegetables' => $allVegetables,
            'locatedVegetables' => $locatedVegetables,
        ));
    }

    /**
     * @Route("update-location-vegetable/{locationId}/{vegetableId}/{surface}", name="update_location_vegetable", options={"expose"=true})
     */
    public function updateLocationVegetable($locationId, $vegetableId, $surface = 0)
    {
        $em = $this->getDoctrine()->getManager();

        $vegetable = $em->getRepository('AppBundle:Vegetable')->findOneBy(array('id' => $vegetableId));
        $location = $em->getRepository('AppBundle:Location')->findOneBy(array('id' => $locationId));

        $locatedVegetable = new LocatedVegetable();
        $locatedVegetable->setLocation($location);
        $locatedVegetable->setVegetable($vegetable);
        $locatedVegetable->setSurface($surface);

        $em->persist($locatedVegetable);
        $em->flush();

        return new JsonResponse(true);
    }

    /**
     * @Route("/locations/{new}", name="locations")
     */
    public function locationsAction(Request $request, $new = null)
    {
        $em = $this->getDoctrine()->getManager();
        
        if (!is_null($new)) {
            $location = new Location();
            
            $em->persist($location);
            $em->flush();
        }

        $locations = $em->getRepository('AppBundle:Location')->findAll();

        return $this->render('backoffice/locations.html.twig', array(
            'locations' => $locations,
        ));
    }
    
    /**
     * 
     * @Route("/location-name-update/{locationId}/{name}", name="update_location_name", options={"expose"=true})
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param type $locationId
     * @param type $name
     */
    public function updateLocationName(Request $request, $locationId, $name)
    {
        $em = $this->getDoctrine()->getManager();
        
        $location = $em->getRepository('AppBundle:Location')->findOneBy(array('id' => $locationId));
        
        if (is_null($location)) {
            return new JsonResponse(false);
        }else {
            $location->setName($name);
            $em->persist($location);
            $em->flush();
            
            return new JsonResponse(true);
        }
    }
}
