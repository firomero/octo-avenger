<?php

namespace Planillas\CoreBundle\Controller;

use Planillas\CoreBundle\Form\Type\Filters\BuscarSolicitudEmpleoType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Planillas\CoreBundle\Entity\CSolicitudEmpleo;
use Planillas\CoreBundle\Form\Type\CSolicitudEmpleoType;

/**
 * CSolicitudEmpleo controller.
 *
 */
class CSolicitudEmpleoController extends Controller
{

    /**
     * Lists all CSolicitudEmpleo entities.
     *
     */
    public function indexAction()
    {

        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $aDatos = array();
        $form = $this->createForm(new BuscarSolicitudEmpleoType());
        $form->handleRequest($request);
        if ($form->isValid()) {

            $aDatos = $form->getData(); //filter data
        }
        //print_r($form->getErrors());exit;
        $result = $em->getRepository('PlanillasCoreBundle:CSolicitudEmpleo')->filterSolicitudEmpleo($aDatos);
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $result, $this->get('request')->query->get('page', 1), 20
        );

        return $this->render('PlanillasCoreBundle:CSolicitudEmpleo:index.html.twig', array(
            'pagination' => $pagination,
            'form' => $form->createView()
        ));
    }

    /**
     * Creates a new CSolicitudEmpleo entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new CSolicitudEmpleo();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $oCvacante = $this->getDoctrine()->getManager()->getRepository('PlanillasCoreBundle:CVacante')->find($entity->getId());

            return $this->redirect($this->generateUrl('csolicitudempleo', array(
                'id' => $entity->getId()
            )));
        }

        return $this->render('PlanillasCoreBundle:CSolicitudEmpleo:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a CSolicitudEmpleo entity.
     *
     * @param CSolicitudEmpleo $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(CSolicitudEmpleo $entity)
    {
        $form = $this->createForm(new CSolicitudEmpleoType(), $entity, array(
            'action' => $this->generateUrl('csolicitudempleo_create'),
            'method' => 'POST',
        ));

        //$form->add('submit', 'submit', array('label' => 'Create'));
        return $form;
    }

    /**
     * Displays a form to create a new CSolicitudEmpleo entity.
     *
     */
    public function newAction()
    {
        $entity = new CSolicitudEmpleo();
        $form = $this->createCreateForm($entity);

        return $this->render('PlanillasCoreBundle:CSolicitudEmpleo:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a CSolicitudEmpleo entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PlanillasCoreBundle:CSolicitudEmpleo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CSolicitudEmpleo entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('PlanillasCoreBundle:CSolicitudEmpleo:show.html.twig', array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),));
    }

    /**
     * Displays a form to edit an existing CSolicitudEmpleo entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PlanillasCoreBundle:CSolicitudEmpleo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CSolicitudEmpleo entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('PlanillasCoreBundle:CSolicitudEmpleo:edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a CSolicitudEmpleo entity.
     *
     * @param CSolicitudEmpleo $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(CSolicitudEmpleo $entity)
    {
        $form = $this->createForm(new CSolicitudEmpleoType(), $entity, array(
            'action' => $this->generateUrl('csolicitudempleo_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

       // $form->add('submit', 'submit', array('label' => 'Update'));
        return $form;
    }

    /**
     * Edits an existing CSolicitudEmpleo entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PlanillasCoreBundle:CSolicitudEmpleo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CSolicitudEmpleo entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
            $this->get('session')->getFlashBag()->add('info', 'Los datos han sido actualizados correctamente');

            return $this->redirect($this->generateUrl('csolicitudempleo'));
            //return $this->redirect($this->generateUrl('csolicitudempleo_edit', array('id' => $id)));
        }
        $this->get('session')->getFlashBag()->add('danger', 'No se pudieron actualizar los datos');

        return $this->render('PlanillasCoreBundle:CSolicitudEmpleo:edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a CSolicitudEmpleo entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        //$form = $this->createDeleteForm($id);
        //$form->handleRequest($request);

        //if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('PlanillasCoreBundle:CSolicitudEmpleo')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find CSolicitudEmpleo entity.');
            }

            $em->remove($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('info', 'Los datos han sido eliminados correctamente');
        //}
        return $this->redirect($this->generateUrl('csolicitudempleo'));
    }

    /**
     * Creates a form to delete a CSolicitudEmpleo entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('csolicitudempleo_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm();
    }
}
