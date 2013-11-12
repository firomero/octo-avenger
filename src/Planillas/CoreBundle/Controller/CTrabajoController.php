<?php

namespace Planillas\CoreBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Planillas\CoreBundle\Entity\CTrabajo;
use Planillas\CoreBundle\Form\CTrabajoType;
use Planillas\CoreBundle\Form\Type\SupervisorType;

/**
 * CTrabajo controller.
 *
 */
class CTrabajoController extends Controller
{

    /**
     * Lists all CTrabajo entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('PlanillasCoreBundle:CTrabajo')->findAll();

        return $this->render('PlanillasCoreBundle:CTrabajo:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Creates a new CTrabajo entity.
     *
     */
    public function createAction(Request $request, $id_empleado)
    {
        $em = $this->getDoctrine()->getManager();
        $eEmpleado = $em->getRepository('PlanillasCoreBundle:CEmpleado')->find($id_empleado);
        if (!$eEmpleado) {
            throw $this->createNotFoundException('Unable to find CEmpleado entity.');
        }
        $entity = new CTrabajo();
        $entity->setEmpleado($eEmpleado);

        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {

            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('ctrabajo_new', array('id_empleado' => $entity->getEmpleado()->getId())));
        }
        return $this->render('PlanillasCoreBundle:CTrabajo:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a CTrabajo entity.
     *
     * @param CTrabajo $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(CTrabajo $entity)
    {
        $form = $this->createForm(new CTrabajoType(), $entity, array(
            'action' => $this->generateUrl('ctrabajo_create', array('id_empleado' => $entity->getEmpleado()->getId())),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Actualizar','attr'=>array('class'=>'btn btn-primary')));

        return $form;
    }

    /**
     * Displays a form to create a new CTrabajo entity.
     *
     */
    public function newAction($id_empleado)
    {
        $em = $this->getDoctrine()->getManager();
        $eEmpleado = $em->getRepository('PlanillasCoreBundle:CEmpleado')->find((int)$id_empleado);
        if (!$eEmpleado) {
            throw $this->createNotFoundException('Unable to find CEmpleado entity.');
        }
        $entity = $em->getRepository('PlanillasCoreBundle:CTrabajo')->findOneByEmpleado($eEmpleado->getId());
        if (!$entity) {
            $entity = new CTrabajo();
            $entity->setEmpleado($eEmpleado);
            $form = $this->createCreateForm($entity);
        } else {
            $form = $this->createEditForm($entity);
        }

        $formSupervisor= $this->createForm(new SupervisorType($eEmpleado->getId()));

        return $this->render('PlanillasCoreBundle:CTrabajo:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
            'eEmpleado' => $eEmpleado,
            'formSupervisor'=>$formSupervisor->createView()
        ));
    }

    /**
     * Finds and displays a CTrabajo entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PlanillasCoreBundle:CTrabajo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CTrabajo entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('PlanillasCoreBundle:CTrabajo:show.html.twig', array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView()));
    }

    /**
     * Displays a form to edit an existing CTrabajo entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PlanillasCoreBundle:CTrabajo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CTrabajo entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('PlanillasCoreBundle:CTrabajo:edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'eEmpleado' => $entity->getEmpleado()
        ));
    }

    /**
     * Creates a form to edit a CTrabajo entity.
     *
     * @param CTrabajo $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(CTrabajo $entity)
    {
        $form = $this->createForm(new CTrabajoType(true), $entity, array(
            'action' => $this->generateUrl('ctrabajo_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Actualizar', 'attr' => array('class' => 'btn btn-primary')));

        return $form;
    }

    /**
     * Edits an existing CTrabajo entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PlanillasCoreBundle:CTrabajo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CTrabajo entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('ctrabajo_edit', array('id' => $id)));
        }
        $formSupervisor= $this->createForm(new SupervisorType($entity->getEmpleado()->getId()));
        return $this->render('PlanillasCoreBundle:CTrabajo:new.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'eEmpleado' => $entity->getEmpleado(),
            'formSupervisor'=>$formSupervisor->createView()
        ));
    }

    /**
     * Deletes a CTrabajo entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('PlanillasCoreBundle:CTrabajo')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find CTrabajo entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('ctrabajo'));
    }

    /**
     * Creates a form to delete a CTrabajo entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('ctrabajo_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm();
    }
    public function supervisorAction(Request $request,$id_empleado)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PlanillasCoreBundle:CEmpleado')->find($id_empleado);
        if(!$entity){
            throw $this->createNotFoundException('Unable to find CEmpleado entity.');
        }
        $form= $this->createForm(new SupervisorType());
        $form->handleRequest($request);
        if($form->isValid())
        {
             $supervisor=$form->getData();
             $data=$supervisor['supervisor'];
             //print_r($entity->getNombre());exit;
             $entity->setSupervisor($data);
             $em->persist($entity);
             $em->flush();
             return $this->redirect($this->generateUrl('ctrabajo_new', array('id_empleado' => $entity->getId())));
        }
        
        
    }
}
