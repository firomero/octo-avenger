<?php

namespace Planillas\EntidadesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('PlanillasEntidadesBundle:Default:index.html.twig', array('name' => $name));
    }
}
