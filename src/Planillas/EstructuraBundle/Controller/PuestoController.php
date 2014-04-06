<?php

namespace Planillas\EstructuraBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PuestoController extends Controller
{
    /**
     * @return array
     *
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }

    /**
     * @param Request $request
     * @return array
     *
     * @Template()
     */
    public function listAction(Request $request)
    {
        return array();
    }

    public function createAction()
    {

    }
} 