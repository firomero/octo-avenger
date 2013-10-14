<?php

namespace Planillas\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('PlanillasCoreBundle:Default:index.html.twig', array());
    }
}
