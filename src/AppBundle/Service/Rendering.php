<?php

namespace AppBundle\Service;

use AppBundle\Entity\Vegetable;
use Doctrine\ORM\EntityManagerInterface;

class Rendering
{
    private $em;
    private $templating;

    public function __construct(EntityManagerInterface $em, \Twig_Environment $templating)
    {
        $this->em = $em;
        $this->templating = $templating;
    }

    public function renderVegetable(Vegetable $vegetable, $locationId, $surface)
    {
        $location = $this->em->getRepository('AppBundle:Location')->findOneBy(array(
            'id' => $locationId,
        ));

        $div = $this->templating->render('blocks/vegetableBlock.html.twig', array(
           'vegetable' => $vegetable,
            'location' => $location,
            'surface' => $surface,
        ));

        return $div;
    }
}