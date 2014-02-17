<?php

namespace Planillas\CoreBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Planillas\CoreBundle\Entity\CHorarioDias;
use Planillas\CoreBundle\Form\CHorarioDiasType;

/**
 * CHorarioDias controller.
 *
 */
class CHorarioDiasController extends Controller
{

    /**
     * Lists all CHorarioDias entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('PlanillasCoreBundle:CHorarioDias')->findAll();

        return $this->render('PlanillasCoreBundle:CHorarioDias:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new CHorarioDias entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new CHorarioDias();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('chorariodias_show', array('id' => $entity->getId())));
        }

        return $this->render('PlanillasCoreBundle:CHorarioDias:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a CHorarioDias entity.
    *
    * @param CHorarioDias $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(CHorarioDias $entity)
    {
        $form = $this->createForm(new CHorarioDiasType(), $entity, array(
            'action' => $this->generateUrl('chorariodias_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new CHorarioDias entity.
     *
     */
    public function newAction()
    {
        $entity = new CHorarioDias();
        $form   = $this->createCreateForm($entity);

        return $this->render('PlanillasCoreBundle:CHorarioDias:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a CHorarioDias entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PlanillasCoreBundle:CHorarioDias')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CHorarioDias entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('PlanillasCoreBundle:CHorarioDias:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing CHorarioDias entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PlanillasCoreBundle:CHorarioDias')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CHorarioDias entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('PlanillasCoreBundle:CHorarioDias:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a CHorarioDias entity.
    *
    * @param CHorarioDias $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(CHorarioDias $entity)
    {
        $form = $this->createForm(new CHorarioDiasType(), $entity, array(
            'action' => $this->generateUrl('chorariodias_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing CHorarioDias entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PlanillasCoreBundle:CHorarioDias')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CHorarioDias entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('chorariodias_edit', array('id' => $id)));
        }

        return $this->render('PlanillasCoreBundle:CHorarioDias:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a CHorarioDias entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('PlanillasCoreBundle:CHorarioDias')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find CHorarioDias entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('chorariodias'));
    }

    /**
     * Creates a form to delete a CHorarioDias entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('chorariodias_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
