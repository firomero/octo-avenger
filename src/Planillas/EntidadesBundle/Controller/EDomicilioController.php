<?php

namespace Planillas\EntidadesBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Planillas\EntidadesBundle\Entity\EDomicilio;
use Planillas\EntidadesBundle\Form\Type\EDomicilioType;

/**
 * EDomicilio controller.
 *
 */
class EDomicilioController extends Controller
{

    /**
     * Lists all EDomicilio entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('PlanillasEntidadesBundle:EDomicilio')->findAll();

        return $this->render('PlanillasEntidadesBundle:EDomicilio:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new EDomicilio entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new EDomicilio();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('domicilio_show', array('id' => $entity->getId())));
        }

        return $this->render('PlanillasEntidadesBundle:EDomicilio:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a EDomicilio entity.
    *
    * @param EDomicilio $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(EDomicilio $entity)
    {
        $form = $this->createForm(new EDomicilioType(), $entity, array(
            'action' => $this->generateUrl('domicilio_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new EDomicilio entity.
     *
     */
    public function newAction()
    {
        $entity = new EDomicilio();
        $form   = $this->createCreateForm($entity);

        return $this->render('PlanillasEntidadesBundle:EDomicilio:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a EDomicilio entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PlanillasEntidadesBundle:EDomicilio')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find EDomicilio entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('PlanillasEntidadesBundle:EDomicilio:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing EDomicilio entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PlanillasEntidadesBundle:EDomicilio')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find EDomicilio entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('PlanillasEntidadesBundle:EDomicilio:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a EDomicilio entity.
    *
    * @param EDomicilio $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(EDomicilio $entity)
    {
        $form = $this->createForm(new EDomicilioType(), $entity, array(
            'action' => $this->generateUrl('domicilio_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing EDomicilio entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $manager=$this->getDoctrine()->getManager();

        $entity = $manager->getRepository('PlanillasEntidadesBundle:EDomicilio')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find EDomicilio entity.');
        }
//echo 'akii'; exit;
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
        $deleteForm = $this->createDeleteForm($id);
        
        if ($editForm->isValid()) {

            $manager->flush();
            //$this->get('core.domicilio.manager')->save($entity);


            $this->get('session')->getFlashBag()->add('info','Se han actualizado los datos correctamente');
            return $this->redirect($this->generateUrl('domicilio_edit', array('id' => $id)));
        }

        return $this->render('PlanillasEntidadesBundle:EDomicilio:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),

        ));
    }
    /**
     * Deletes a EDomicilio entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('PlanillasEntidadesBundle:EDomicilio')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find EDomicilio entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('domicilio'));
    }

    /**
     * Creates a form to delete a EDomicilio entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('domicilio_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
