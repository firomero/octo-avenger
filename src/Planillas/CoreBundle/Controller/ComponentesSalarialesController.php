<?php

namespace Planillas\CoreBundle\Controller;

use Planillas\CoreBundle\Form\Type\ComponenteBonificacionType;
use Planillas\CoreBundle\Form\Type\ComponenteRebajoType;
use Planillas\CoreBundle\Form\Type\Filters\BuscarHistorialComponentesSalarialesType;
use Planillas\EntidadesBundle\Entity\EComponentesSalariales;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;

class ComponentesSalarialesController extends Controller
{

    public function indexAction(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');

        $busqueda_historial_form = $this->createForm(new BuscarHistorialComponentesSalarialesType());
        $busqueda_historial_form->handleRequest($request);

        if($busqueda_historial_form->isValid()) {
            $data = $busqueda_historial_form->getData();
            $query = $em->getRepository('PlanillasEntidadesBundle:EComponentesSalariales')->findAllNotDeleted($data);
        } else {
            $query = $em->getRepository('PlanillasEntidadesBundle:EComponentesSalariales')->findAllNotDeleted();
        }

        $paginator = $this->get('knp_paginator');
        $results = $paginator->paginate(
            $query,
            $request->get('page',1),
            10,
            array()
        );

        $rebajos_form = $this->createForm(new ComponenteRebajoType());
        $bonificaciones_form = $this->createForm(new ComponenteBonificacionType());


        return $this->render('PlanillasCoreBundle:ComponentesSalariales:index.html.twig',
            array(
                'results'                   => $results,
                'rebajos_form'              => $rebajos_form->createView(),
                'bonificaciones_form'       => $bonificaciones_form->createView(),
                'busqueda_historial_form'   => $busqueda_historial_form->createView(),
            ));
    }

    public function createAction(Request $request, $componente)
    {
        if ($componente === 'rebajo') {
            $rebajos_form = $this->createForm(new ComponenteRebajoType());

            $rebajos_form->handleRequest($request);
            if ($rebajos_form->isValid()) {
                $data = $rebajos_form->getData();

                $rebajosManager = $this->get('payments.componente_rebajo.manager');
                try {
                    $rebajosManager->createRebajos($data);

                    $rebajos_form = $this->createForm(new ComponenteRebajoType());
                } catch (\Exception $e) {
                    $rebajos_form->addError(new FormError($e->getMessage()));
                }
            }

            return $this->render('PlanillasCoreBundle:ComponentesSalariales:_rebajo_form.html.twig',
                array(
                    'form' => $rebajos_form->createView(),
                ));
        } else {
            $bonificacion_form = $this->createForm(new ComponenteBonificacionType());

            $bonificacion_form->handleRequest($request);
            if($bonificacion_form->isValid()) {
                $data = $bonificacion_form->getData();

                $bonificacionesManager = $this->get('payments.componente_bonificacion.manager');
                if ($bonificacionesManager->createBonificacion($data)) {
                    $bonificacion_form = $this->createForm(new ComponenteBonificacionType());
                }
            }

            return $this->render('PlanillasCoreBundle:ComponentesSalariales:_bonificacion_form.html.twig',
                array(
                    'form' => $bonificacion_form->createView(),
                ));
        }
    }
}