<?php

namespace AppBundle\Controller;

use AppBundle\Entity\LocatedVegetable;
use AppBundle\Entity\Location;
use AppBundle\Entity\Vegetable;
use AppBundle\Form\LocationType;
use AppBundle\Form\VegetableType;
use AppBundle\Repository\VegetableRepository;
use AppBundle\Service\Objective;
use AppBundle\Service\Rendering;
use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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

            unset($vegetable);
            unset($vegetableForm);
            $vegetable = new Vegetable();
            $vegetableForm = $this->createForm(VegetableType::class, $vegetable);
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

        $allVegetables = $em->getRepository('AppBundle:Vegetable')->findAll();

        return $this->render('backoffice/location.html.twig', array(
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
    public function updateLocationSurface($locationId, $surface)
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
     * @Route("/calculate-next-period/{locationId}", name="calculate_next_period", options={"expose"=true})
     */
    public function calculateNextPeriod(Request $request, Objective $objectiveService, Rendering $renderingService, $locationId)
    {
        $em = $this->getDoctrine()->getManager();

        $location = $em->getRepository('AppBundle:Location')->findOneBy(array('id' => $locationId));

        if (is_null($location)) {
            return new JsonResponse(false);
        }else {
            $divs = $this->calculateNextVegetables($location->getVegetables(), $objectiveService, $renderingService, $locationId);

            $response = new JsonResponse($divs);
            $response->setStatusCode(200);

            return $response;
        }
    }

    public function calculateNextVegetables($vegetables, Objective $objectiveService, Rendering $renderingService, $locationId)
    {
        $nextVegetables = '';
        foreach($vegetables as $vegetable) {
            $nextVegetables .= $this->calculateNextVegetable($vegetable, $objectiveService, $renderingService, $locationId);
        }

        return $nextVegetables;
    }

    public function calculateNextVegetable(LocatedVegetable $locatedVegetable, Objective $objectiveService, Rendering $renderingService, $locationId)
    {
        $em = $this->getDoctrine()->getManager();

        $vegetable = $locatedVegetable->getVegetable();
        $nextCategoryId = $vegetable->getCategory()->getId() + 1;
        $nextCategory = $em->getRepository('AppBundle:VegetableCategory')->findOneBy(array(
           'id' => $nextCategoryId
        ));
        $nextCategoryVegetables = $em->getRepository('AppBundle:Vegetable')->findBy(array(
            'category' => $nextCategory
        ));

        $lowestProgress = 100.00;
        $lowestProgressVegetableId = null;

        foreach($nextCategoryVegetables as $nextCategoryVegetable) {
            $nextCategoryObjective = $objectiveService->calculateObjective($nextCategoryVegetable);
            if (array_key_exists('progress', $nextCategoryObjective)) {
                if ($nextCategoryObjective['progress'] < $lowestProgress) {
                    $lowestProgress = $nextCategoryObjective['progress'];
                    $lowestProgressVegetableId = $nextCategoryObjective['vegetableId'];
                }
            }
        }

        $nextVegetable = $em->getRepository('AppBundle:Vegetable')->findOneBy(array(
           'id' => $lowestProgressVegetableId,
        ));

        return $renderingService->renderVegetable($nextVegetable, $locationId, $locatedVegetable->getSurface());
    }

    /**
     * @Route("/objectives", name="objectives")
     */
    public function objectivesAction(Request $request, Objective $objectiveService)
    {
        $em = $this->getDoctrine()->getManager();

        $vegetables = $em->getRepository('AppBundle:Vegetable')
            ->findAll();

        $now = new \DateTime();

        $objectives = $objectiveService->calculateObjectives();

        return $this->render('backoffice/objectives.html.twig', array(
            'vegetables' => $vegetables,
            'objectives' => $objectives,
            'now' => $now->format("Y"),
        ));
    }

    /**
     * @Route("/reset-vegetables/{locationId}", name="reset_vegetables")
     */
    public function resetVegetablesAction($locationId)
    {
        $em = $this->getDoctrine()->getManager();

        $location = $em->getRepository('AppBundle:Location')->findOneBy(array(
           'id' => $locationId,
        ));

        foreach($location->getVegetables() as $vegetable) {
            $location->removeVegetable($vegetable);
            $em->remove($vegetable);
        }

        $em->persist($location);
        $em->flush();

        return $this->redirect($this->generateUrl('location', array('locationId' => $locationId)));
    }

    /**
     * @Route("/switch-sheltered/{locationId}", name="switch_sheltered", options={"expose"=true})
     */
    public function switchShelteredLocationAction($locationId)
    {
        $em = $this->getDoctrine()->getManager();

        $location = $em->getRepository('AppBundle:Location')->findOneBy(array(
           'id' => $locationId,
        ));

        $location->setSheltered(!$location->isSheltered());

        $em->persist($location);
        $em->flush();

        return new JsonResponse(true);
    }
}
