<?php
/**
 * Created by PhpStorm.
 * User: cinfante
 * Date: 06/04/14
 * Time: 08:48 AM
 */

namespace Planillas\EstructuraBundle\Controller;

use Planillas\EstructuraBundle\Entity\Empresa;
use Planillas\EstructuraBundle\Form\Type\EmpresaType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class EmpresaController extends Controller
{
    /**
     * @param Request $request
     *
     * @Template()
     */
    public function indexAction()
    {
        $form = $this->createForm(new EmpresaType());

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

        $query = $em->getRepository('PlanillasEstructuraBundle:Empresa')->findAllNotDeleted();
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
        $entity = new Empresa();
        $form = $this->createForm(new EmpresaType(), $entity);

        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->get('doctrine.orm.entity_manager');
            try {
                $em->persist($entity);
                $em->flush();

                return new Response("Se ha creado una nueva Empresa.", 200);
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
}
