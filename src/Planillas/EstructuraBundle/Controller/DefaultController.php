<?php

namespace Planillas\EstructuraBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }
}
