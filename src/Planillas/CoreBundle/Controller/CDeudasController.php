<?php

namespace Planillas\CoreBundle\Controller;

use Planillas\CoreBundle\Form\Type\BuscarDeudasType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Planillas\CoreBundle\Entity\CDeudas;
use Planillas\CoreBundle\Form\Type\CDeudasType;

/**
 * CDeudas controller.
 *
 */
class CDeudasController extends Controller
{

    /**
     * Lists all CDeudas entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $aDatos = array();
        $form = $this->createForm(new BuscarDeudasType());
        $form->handleRequest($request);
        if ($form->isValid()) {

            $aDatos = $form->getData(); //filter data
        }

        $result = $em->getRepository('PlanillasCoreBundle:CDeudas')->filterDeudas($aDatos);
        $paginator = $this->get('knp_paginator');
        $session = $this->get('session')->set('filtros', array()); //hay que meter la busqueda en la sesion
        $pagination = $paginator->paginate(
            $result, $this->get('request')->query->get('page', 1), 10
        );
        $entity = new CDeudas();
        $form_new = $this->createCreateForm($entity);
        return $this->render('PlanillasCoreBundle:CDeudas:index.html.twig', array(
            'pagination' => $pagination,
            'form_buscar' => $form->createView(),
            'form' => $form_new->createView()
        ));
    }

    /**
     * Creates a new CDeudas entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new CDeudas();
        $em = $this->getDoctrine()->getManager();
        $form = new CDeudasType();

        $data = $request->get('planillas_id');


        if (isset($data['id']) && !empty($data['id'])) {
            $entity = $em->getRepository('PlanillasCoreBundle:CDeudas')->find($data['id']);
            if (!$entity) {
                throw $this->createNotFoundException('Unable to find CDeudas entity.');

            } else {


                $form = $this->createEditForm($entity);
            }

        } else {
            //$entity->setMontoRestante(0);
            $form = $this->createCreateForm($entity);
        }
        $form->handleRequest($request);
        if ($form->isValid()) {

            if ($entity->getPagado() == 1) {
                $entity->setMontoRestante(0);
            }
            $em->persist($entity);
            $em->flush();
            //poner un mensaje flash
            $this->get('session')->getFlashBag()->add('info', 'Los datos han sido guardados correctamente');
            return $this->redirect($this->generateUrl('cdeudas'));
        }

        $this->get('session')->getFlashBag()->add('danger', 'No se pudieron guardados los datos');
        return $this->redirect($this->generateUrl('cdeudas'));
    }

    /**
     * Creates a form to create a CDeudas entity.
     *
     * @param CDeudas $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(CDeudas $entity)
    {
        $form = $this->createForm(new CDeudasType(), $entity, array(
            'action' => $this->generateUrl('cdeudas_create'),
            'method' => 'POST',
        ));

        //$form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new CDeudas entity.
     *
     */
    public function newAction()
    {
        $entity = new CDeudas();
        $form = $this->createCreateForm($entity);

        return $this->render('PlanillasCoreBundle:CDeudas:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a CDeudas entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PlanillasCoreBundle:CDeudas')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CDeudas entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('PlanillasCoreBundle:CDeudas:show.html.twig', array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),));
    }

    /**
     * Displays a form to edit an existing CDeudas entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PlanillasCoreBundle:CDeudas')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CDeudas entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('PlanillasCoreBundle:CDeudas:edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a CDeudas entity.
     *
     * @param CDeudas $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(CDeudas $entity)
    {
        $form = $this->createForm(new CDeudasType(), $entity, array(
            'action' => $this->generateUrl('cdeudas_update', array('id' => $entity->getId())),
            'method' => 'POST',
        ));

        //$form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing CDeudas entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PlanillasCoreBundle:CDeudas')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CDeudas entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('cdeudas_edit', array('id' => $id)));
        }

        return $this->render('PlanillasCoreBundle:CDeudas:edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a CDeudas entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {

        try {

            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('PlanillasCoreBundle:CDeudas')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find CDeudas entity.');
            }

            $em->remove($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('info', 'Los datos han sido eliminados correctamente');

        } catch (Exception $e) {
            $this->get('session')->getFlashBag()->add('info', 'No se pudieron eliminar los datos');
        }
        return $this->redirect($this->generateUrl('cdeudas'));

    }

    /**
     * Creates a form to delete a CDeudas entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('cdeudas_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm();
    }

    public function editajaxAction(Request $request)
    {
        $id = $request->get('id');
        //print_r($id);exit;
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('PlanillasCoreBundle:CDeudas')->find($id);
        $response = array("success" => true);
        if (!$entity) {
            $response['success'] = false;
            return new \Symfony\Component\HttpFoundation\Response(json_encode($response));
        }
        $response['data'] = $entity->getJson();
        return new \Symfony\Component\HttpFoundation\Response(json_encode($response));
    }
}
