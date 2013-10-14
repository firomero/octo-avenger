<?php

namespace Planillas\EntidadesBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Planillas\EntidadesBundle\Entity\EEducacion;
use Planillas\EntidadesBundle\Form\Type\EEducacionType;

/**
 * EEducacion controller.
 *
 */
class EEducacionController extends Controller
{

    /**
     * Lists all EEducacion entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('PlanillasEntidadesBundle:EEducacion')->findAll();

        return $this->render('PlanillasEntidadesBundle:EEducacion:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new EEducacion entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new EEducacion();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('educacion_show', array('id' => $entity->getId())));
        }

        return $this->render('PlanillasEntidadesBundle:EEducacion:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a EEducacion entity.
    *
    * @param EEducacion $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(EEducacion $entity)
    {
        $form = $this->createForm(new EEducacionType(), $entity, array(
            'action' => $this->generateUrl('educacion_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new EEducacion entity.
     *
     */
    public function newAction()
    {
        $entity = new EEducacion();
        $form   = $this->createCreateForm($entity);

        return $this->render('PlanillasEntidadesBundle:EEducacion:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a EEducacion entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PlanillasEntidadesBundle:EEducacion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find EEducacion entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('PlanillasEntidadesBundle:EEducacion:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing EEducacion entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PlanillasEntidadesBundle:EEducacion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find EEducacion entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('PlanillasEntidadesBundle:EEducacion:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a EEducacion entity.
    *
    * @param EEducacion $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(EEducacion $entity)
    {
        $form = $this->createForm(new EEducacionType(), $entity, array(
            'action' => $this->generateUrl('educacion_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing EEducacion entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PlanillasEntidadesBundle:EEducacion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find EEducacion entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('educacion_edit', array('id' => $id)));
        }

        return $this->render('PlanillasEntidadesBundle:EEducacion:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a EEducacion entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('PlanillasEntidadesBundle:EEducacion')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find EEducacion entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('educacion'));
    }

    /**
     * Creates a form to delete a EEducacion entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('educacion_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
