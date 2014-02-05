<?php

namespace Planillas\EntidadesBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Planillas\EntidadesBundle\Entity\EDatoLegal;
use Planillas\EntidadesBundle\Form\Type\EDatoLegalType;

/**
 * EDatoLegal controller.
 *
 */
class EDatoLegalController extends Controller
{

    /**
     * Lists all EDatoLegal entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('PlanillasEntidadesBundle:EDatoLegal')->findAll();

        return $this->render('PlanillasEntidadesBundle:EDatoLegal:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new EDatoLegal entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new EDatoLegal();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('datolegal_show', array('id' => $entity->getId())));
        }

        return $this->render('PlanillasEntidadesBundle:EDatoLegal:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a EDatoLegal entity.
    *
    * @param EDatoLegal $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(EDatoLegal $entity)
    {
        $form = $this->createForm(new EDatoLegalType(), $entity, array(
            'action' => $this->generateUrl('datolegal_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new EDatoLegal entity.
     *
     */
    public function newAction()
    {
        $entity = new EDatoLegal();
        $form   = $this->createCreateForm($entity);

        return $this->render('PlanillasEntidadesBundle:EDatoLegal:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a EDatoLegal entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PlanillasEntidadesBundle:EDatoLegal')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find EDatoLegal entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('PlanillasEntidadesBundle:EDatoLegal:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing EDatoLegal entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PlanillasEntidadesBundle:EDatoLegal')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find EDatoLegal entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('PlanillasEntidadesBundle:EDatoLegal:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a EDatoLegal entity.
    *
    * @param EDatoLegal $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(EDatoLegal $entity)
    {
        $form = $this->createForm(new EDatoLegalType(), $entity, array(
            'action' => $this->generateUrl('datolegal_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing EDatoLegal entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PlanillasEntidadesBundle:EDatoLegal')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find EDatoLegal entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('datolegal_edit', array('id' => $id)));
        }

        return $this->render('PlanillasEntidadesBundle:EDatoLegal:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a EDatoLegal entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('PlanillasEntidadesBundle:EDatoLegal')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find EDatoLegal entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('datolegal'));
    }

    /**
     * Creates a form to delete a EDatoLegal entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('datolegal_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
