<?php

namespace Planillas\EstructuraBundle\Controller;

use Planillas\EstructuraBundle\Entity\Turno;
use Planillas\EstructuraBundle\Form\Type\TurnoType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TurnoController extends Controller
{
    /**
     * @return array
     *
     * @Template()
     */
    public function indexAction()
    {
        $form = $this->createForm(new TurnoType());

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * @param  Request $request
     * @return array
     *
     * @Template()
     */
    public function listAction(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $paginator = $this->get('knp_paginator');

        $query = $em->getRepository('PlanillasEstructuraBundle:Turno')->findAllNotDeleted();
        $entities = $paginator->paginate(
            $query,
            $request->get('page',1),
            10,
            array()
        );

        return array(
            'entities' => $entities,
        );
    }

    /**
     * @param  Request  $request
     * @return Response
     */
    public function createAction(Request $request)
    {
        $entity = new Turno();
        $form = $this->createForm(new TurnoType(), $entity);

        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->get('doctrine.orm.entity_manager');
            try {
                $em->persist($entity);
                $em->flush();

                return new Response("Se ha creado un nuevo Turno.", 200);
            } catch (\Exception $e) {
                $logger = $this->get('logger');
                $logger->addCritical($e->getMessage());
            }
        }

        $errors = array();
        foreach ($form->getErrors() as $error) {
            $errors['error'][] = $error->getMessage();
        }
        foreach ($form->getIterator() as $value) {
            /** @var $value FormInterface */
            if (count($value->getErrors())) {
                foreach ($value->getErrors() as $error) {
                    $errors[$value->getName()][] = $error->getMessage();
                }
            }
        }

        return new Response(json_encode($errors), 500);
    }

    /**
     * @param $id
     * @return array
     *
     * @Template()
     */
    public function turnosComboAction($id)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $turnos = $em->getRepository('PlanillasEstructuraBundle:Turno')
            ->findAllBySucursalId($id);

        return array(
            'turnos' => $turnos,
        );
    }
}
