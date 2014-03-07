<?php

namespace Planillas\CoreBundle\Controller;

use Planillas\CoreBundle\Form\Type\BuscarVacanteType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Planillas\CoreBundle\Entity\CVacante;
use Planillas\CoreBundle\Form\Type\CVacanteType;

/**
 * CVacante controller.
 *
 */
class CVacanteController extends Controller
{

    /**
     * Lists all CVacante entities.
     *
     */
    public function indexAction()
    {

        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $aDatos = array();
        $form = $this->createForm(new BuscarVacanteType());
        $form->handleRequest($request);
        if ($form->isValid()) {
            $aDatos = $form->getData(); //filter data
        }

        $result = $em->getRepository('PlanillasCoreBundle:CVacante')->filterVacante($aDatos);
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $result, $this->get('request')->query->get('page', 1), 20
        );

        return $this->render('PlanillasCoreBundle:CVacante:index.html.twig', array(
            'pagination' => $pagination,
            'form' => $form->createView()
        ));
    }

    /**
     * Creates a new CVacante entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new CVacante();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('cvacante', array('id' => $entity->getId())));
        }

        return $this->render('PlanillasCoreBundle:CVacante:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a CVacante entity.
     *
     * @param CVacante $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(CVacante $entity)
    {
        $form = $this->createForm(new CVacanteType(), $entity, array(
            'action' => $this->generateUrl('cvacante_create'),
            'method' => 'POST',
        ));

        //$form->add('submit', 'submit', array('label' => 'Nuevo'));

        return $form;
    }

    /**
     * Displays a form to create a new CVacante entity.
     *
     */
    public function newAction()
    {
        $entity = new CVacante();
        $form = $this->createCreateForm($entity);

        return $this->render('PlanillasCoreBundle:CVacante:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a CVacante entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PlanillasCoreBundle:CVacante')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CVacante entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('PlanillasCoreBundle:CVacante:show.html.twig', array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView()));
    }

    /**
     * Displays a form to edit an existing CVacante entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PlanillasCoreBundle:CVacante')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CVacante entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('PlanillasCoreBundle:CVacante:edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a CVacante entity.
     *
     * @param CVacante $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(CVacante $entity)
    {
        $form = $this->createForm(new CVacanteType(), $entity, array(
            'action' => $this->generateUrl('cvacante_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        //$form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing CVacante entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PlanillasCoreBundle:CVacante')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CVacante entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
            $this->get('session')->getFlashBag()->add('info', 'Los datos han sido actualizados correctamente');
            return $this->redirect($this->generateUrl('cvacante_edit', array('id' => $id)));
        }
        $this->get('session')->getFlashBag()->add('danger', 'No se puedieron actualizar los datos');
        return $this->render('PlanillasCoreBundle:CVacante:edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a CVacante entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        //$form = $this->createDeleteForm($id);
        //$form->handleRequest($request);

        //  if ($form->isValid()) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('PlanillasCoreBundle:CVacante')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CVacante entity.');
        }

        $em->remove($entity);
        $em->flush();
        $this->get('session')->getFlashBag()->add('info', 'Los datos han sido eliminados correctamente');
        //  }

        return $this->redirect($this->generateUrl('cvacante'));
    }

    /**
     * Creates a form to delete a CVacante entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('cvacante_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Eliminar'))
            ->getForm();
    }
}
