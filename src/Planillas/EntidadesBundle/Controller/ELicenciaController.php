<?php

namespace Planillas\EntidadesBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Planillas\EntidadesBundle\Entity\ELicencia;
use Planillas\EntidadesBundle\Form\Type\ELicenciaType;

/**
 * ELicencia controller.
 *
 */
class ELicenciaController extends Controller
{

    /**
     * Lists all ELicencia entities.
     *
     */
    public function indexAction($id_empleado)
    {
        $em = $this->getDoctrine()->getManager();

        //$entities = $em->getRepository('PlanillasEntidadesBundle:ELicencia')->findAll();
        $eEmpleado = $em->getRepository('PlanillasCoreBundle:CEmpleado')->find($id_empleado);
        
        $entities = $em->createQuery('Select f from PlanillasEntidadesBundle:ELicencia f where f.empleado='.$id_empleado);
        $entities=$entities->getResult();
        $aDeleteForm =  array();
        foreach($entities as $entity){
            $aDeleteForm[$entity->getId()] = $this->createDeleteForm($entity->getId())->createView(); 
        }

        return $this->render('PlanillasEntidadesBundle:ELicencia:index.html.twig', array(
            'entities' => $entities,
            'eEmpleado'=>$eEmpleado,
            'aDeleteForm'=>$aDeleteForm,
        ));
        
        /*$em = $this->getDoctrine()->getManager();
        $eEmpleado = $em->getRepository('PlanillasCoreBundle:CEmpleado')->find($id_empleado);
        
        $entities = $em->createQuery('Select f from PlanillasEntidadesBundle:ECursos f where f.empleado='.$id_empleado);
        $entities=$entities->getResult();
        $aDeleteForm =  array();
        foreach($entities as $entity){
            $aDeleteForm[$entity->getId()] = $this->createDeleteForm($entity->getId())->createView(); 
        }
        return $this->render('PlanillasEntidadesBundle:ECursos:index.html.twig', array(
            'entities' => $entities,
            'eEmpleado'=>$eEmpleado,
            'aDeleteForm'=>$aDeleteForm,
        ));
         */
    }
    /**
     * Creates a new ELicencia entity.
     *
     */
    public function createAction(Request $request, $id_empleado)
    {
        $em = $this->getDoctrine()->getManager();
        $eEmpleado = $em->getRepository('PlanillasCoreBundle:CEmpleado')->find($id_empleado);
        
        $entity = new ELicencia();
        $entity->setEmpleado($eEmpleado);
        
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            //return $this->redirect($this->generateUrl('elicencia_show', array('id' => $entity->getId())));
            return $this->redirect($this->generateUrl('elicencia', array('id_empleado' => $eEmpleado->getId())));
        }

        return $this->render('PlanillasEntidadesBundle:ELicencia:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a ELicencia entity.
    *
    * @param ELicencia $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(ELicencia $entity)
    {
        $form = $this->createForm(new ELicenciaType(true), $entity, array(
            'action' => $this->generateUrl('elicencia_create', array('id_empleado'=>$entity->getEmpleado()->getId())),
            'method' => 'POST',
        ));

        //$form->add('submit', 'submit', array('label' => 'Crear', 'attr'=>array('class'=>'btn btn-success')));

        return $form;
    }

    /**
     * Displays a form to create a new ELicencia entity.
     *
     */
    public function newAction($id_empleado)
    {
        $em = $this->getDoctrine()->getManager();
        $eEmpleado = $em->getRepository('PlanillasCoreBundle:CEmpleado')->find($id_empleado);
        
        $entity = new ELicencia();
        $entity->setEmpleado($eEmpleado);
        $form   = $this->createCreateForm($entity);

        return $this->render('PlanillasEntidadesBundle:ELicencia:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a ELicencia entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PlanillasEntidadesBundle:ELicencia')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ELicencia entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('PlanillasEntidadesBundle:ELicencia:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing ELicencia entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PlanillasEntidadesBundle:ELicencia')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ELicencia entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('PlanillasEntidadesBundle:ELicencia:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a ELicencia entity.
    *
    * @param ELicencia $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(ELicencia $entity)
    {
        $form = $this->createForm(new ELicenciaType(true), $entity, array(
            'action' => $this->generateUrl('elicencia_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Actualizar', 'attr'=>array('class'=>'btn btn-success')));

        return $form;
    }
    /**
     * Edits an existing ELicencia entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PlanillasEntidadesBundle:ELicencia')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ELicencia entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('elicencia_edit', array('id' => $id)));
        }

        return $this->render('PlanillasEntidadesBundle:ELicencia:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a ELicencia entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('PlanillasEntidadesBundle:ELicencia')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find ELicencia entity.');
            }
            $iIdempleado = $entity->getEmpleado()->getId();
            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('elicencia', array( 'id_empleado'=> $iIdempleado )));
    }

    /**
     * Creates a form to delete a ELicencia entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('elicencia_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Eliminar', 'attr'=>array('class'=>'btn btn-primary')))
            ->getForm()
        ;
    }
}
