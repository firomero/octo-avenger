<?php

namespace Planillas\EntidadesBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Planillas\EntidadesBundle\Entity\EHistoriaSalud;
use Planillas\EntidadesBundle\Form\Type\EHistoriaSaludType;

/**
 * EHistoriaSalud controller.
 *
 */
class EHistoriaSaludController extends Controller
{

    /**
     * Lists all EHistoriaSalud entities.
     *
     */
    public function indexAction($id_empleado)
    {
        $em = $this->getDoctrine()->getManager();
        $eEmpleado = $em->getRepository('PlanillasCoreBundle:CEmpleado')->find($id_empleado);

        $entities = $em->createQuery('Select f from PlanillasEntidadesBundle:EHistoriaSalud f where f.empleado='.$id_empleado);
        $entities=$entities->getResult();
        $aDeleteForm =  array();
        foreach ($entities as $entity) {
            $aDeleteForm[$entity->getId()] = $this->createDeleteForm($entity->getId())->createView();
        }

        return $this->render('PlanillasEntidadesBundle:EHistoriaSalud:index.html.twig', array(
            'entities' => $entities,
            'eEmpleado'=>$eEmpleado,
            'aDeleteForm'=>$aDeleteForm,
        ));

        $entities = $em->getRepository('PlanillasEntidadesBundle:EHistoriaSalud')->findAll();

        return $this->render('PlanillasEntidadesBundle:EHistoriaSalud:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new EHistoriaSalud entity.
     *
     */
    public function createAction(Request $request, $id_empleado)
    {
        $em = $this->getDoctrine()->getManager();
        $eEmpleado = $em->getRepository('PlanillasCoreBundle:CEmpleado')->find($id_empleado);

        $entity = new EHistoriaSalud();
        $entity->setEmpleado($eEmpleado);

        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('historiasalud', array('id_empleado' => $eEmpleado->getId())));
        }

        return $this->render('PlanillasEntidadesBundle:EHistoriaSalud:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a EHistoriaSalud entity.
    *
    * @param EHistoriaSalud $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(EHistoriaSalud $entity)
    {
        $form = $this->createForm(new EHistoriaSaludType(true), $entity, array(
            'action' => $this->generateUrl('historiasalud_create', array('id_empleado'=>$entity->getEmpleado()->getId())),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Crear', 'attr'=>array('class'=>'btn btn-success')));

        return $form;
    }

    /**
     * Displays a form to create a new EHistoriaSalud entity.
     *
     */
    public function newAction($id_empleado)
    {
        $em = $this->getDoctrine()->getManager();
        $eEmpleado = $em->getRepository('PlanillasCoreBundle:CEmpleado')->find($id_empleado);

        $entity = new EHistoriaSalud();
        $entity->setEmpleado($eEmpleado);
        $form   = $this->createCreateForm($entity);

        return $this->render('PlanillasEntidadesBundle:EHistoriaSalud:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a EHistoriaSalud entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PlanillasEntidadesBundle:EHistoriaSalud')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find EHistoriaSalud entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('PlanillasEntidadesBundle:EHistoriaSalud:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing EHistoriaSalud entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PlanillasEntidadesBundle:EHistoriaSalud')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find EHistoriaSalud entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('PlanillasEntidadesBundle:EHistoriaSalud:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a EHistoriaSalud entity.
    *
    * @param EHistoriaSalud $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(EHistoriaSalud $entity)
    {
        $form = $this->createForm(new EHistoriaSaludType(true), $entity, array(
            'action' => $this->generateUrl('historiasalud_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Actualizar', 'attr'=>array('class'=>'btn btn-success')));

        return $form;
    }
    /**
     * Edits an existing EHistoriaSalud entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PlanillasEntidadesBundle:EHistoriaSalud')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find EHistoriaSalud entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('historiasalud_edit', array('id' => $id)));
        }

        return $this->render('PlanillasEntidadesBundle:EHistoriaSalud:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a EHistoriaSalud entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('PlanillasEntidadesBundle:EHistoriaSalud')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find EHistoriaSalud entity.');
            }

            $iIdempleado = $entity->getEmpleado()->getId();
            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('historiasalud', array( 'id_empleado'=> $iIdempleado )));
    }

    /**
     * Creates a form to delete a EHistoriaSalud entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('historiasalud_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Eliminar', 'attr'=>array('class'=>'btn btn-success')))
            ->getForm()
        ;
    }
}
