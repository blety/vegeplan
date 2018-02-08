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

        $totalSurface = 0.00;

        foreach ($location->getVegetables() as $k => $locatedVegetable) {
            $locatedVegetables[$k]['vegetable'] = $locatedVegetable;
            $locatedVegetables[$k]['surface'] = $locatedVegetable->getSurface();
            $totalSurface += $locatedVegetable->getSurface();
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

        }

        return $this->render('backoffice/location.html.twig', array(
            'locationForm' => $locationForm->createView(),
            'location' => $location,
            'allVegetables' => $allVegetables,
            'locatedVegetables' => $locatedVegetables,
            'totalSurface' => $totalSurface,
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

        $locatedVegetable = $em->getRepository('AppBundle:LocatedVegetable')
            ->findOneBy(array('location' => $locationId, 'vegetable' => $vegetableId));

        $surface = str_replace(',', '.', $surface);

        if (is_null($locatedVegetable)) {
            $locatedVegetable = new LocatedVegetable();
            $locatedVegetable->setLocation($location);
            $locatedVegetable->setVegetable($vegetable);
            $locatedVegetable->setSurface($surface);
        }else {
            $locatedVegetable->setSurface($locatedVegetable->getSurface() + $surface);
        }


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

    /**
     * @Route("/location-surface-update/{locationId}/{surface}", name="update_location_surface", options={"expose"=true})
     *
     * @param Request $request
     * @param $locationId
     * @param $surface
     */
    public function updateLocationSurface(Request $request, $locationId, $surface)
    {
        $em = $this->getDoctrine()->getManager();

        $location = $em->getRepository('AppBundle:Location')->findOneBy(array('id' => $locationId));

        if (is_null($location)) {
            return new JsonResponse(false);
        }else {
            $location->setSurface($surface);
            $em->persist($location);
            $em->flush();

            return new JsonResponse(true);
        }
    }

    /**
     * @Route("/objectives", name="objectives")
     */
    public function objectivesAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $objectives = array();

        $vegetables = $em->getRepository('AppBundle:Vegetable')
            ->findAll();

        $locatedVegetables = $em->getRepository('AppBundle:LocatedVegetable')
            ->findAll();

        foreach ($vegetables as $vegetable) {
            foreach ($locatedVegetables as $locatedVegetable) {
                if ($vegetable->getId() === $locatedVegetable->getVegetable()->getId()) {
                    $objectives[$vegetable->getId()]['locatedVegetable'] = $locatedVegetable;
                    $objectives[$vegetable->getId()]['vegetableId'] = $locatedVegetable->getVegetable()->getId();
                    if (array_key_exists($vegetable->getId(), $objectives) && array_key_exists('surface', $objectives[$vegetable->getId()])) {
                        $objectives[$vegetable->getId()]['surface'] += $locatedVegetable->getSurface();
                    }else {
                        $objectives[$vegetable->getId()]['surface'] = $locatedVegetable->getSurface();
                    }
                }
            }
        }

        return $this->render('backoffice/objectives.html.twig', array(
            'vegetables' => $vegetables,
            'objectives' => $objectives,
        ));
    }
}
