<?php

namespace Planillas\EntidadesBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Planillas\EntidadesBundle\Entity\EAntecedentePenal;
use Planillas\EntidadesBundle\Form\Type\EAntecedentePenalType;

/**
 * EAntecedentePenal controller.
 *
 */
class EAntecedentePenalController extends Controller
{

    /**
     * Lists all EAntecedentePenal entities.
     *
     */
    public function indexAction($id_empleado)
    {
        $em = $this->getDoctrine()->getManager();
        $eEmpleado = $em->getRepository('PlanillasCoreBundle:CEmpleado')->find($id_empleado);
        
        //$entities = $em->getRepository('PlanillasEntidadesBundle:EAntecedentePenal')->findAll();
        $entities = $em->createQuery('Select f from PlanillasEntidadesBundle:EAntecedentePenal f where f.empleado='.$id_empleado);
        $entities=$entities->getResult();
        $aDeleteForm =  array();
        foreach($entities as $entity){
            $aDeleteForm[$entity->getId()] = $this->createDeleteForm($entity->getId())->createView(); 
        }
        
        return $this->render('PlanillasEntidadesBundle:EAntecedentePenal:index.html.twig', array(
            'entities' => $entities,
            'eEmpleado'=>$eEmpleado,
            'aDeleteForm'=>$aDeleteForm,
        ));
    }
    /**
     * Creates a new EAntecedentePenal entity.
     *
     */
    public function createAction(Request $request, $id_empleado)
    {
        $em = $this->getDoctrine()->getManager();
        $eEmpleado = $em->getRepository('PlanillasCoreBundle:CEmpleado')->find($id_empleado);
        
        $entity = new EAntecedentePenal();
        $entity->setEmpleado($eEmpleado);
        
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            //return $this->redirect($this->generateUrl('antecedentepenal_show', array('id' => $entity->getId())));
            //return $this->redirect($this->generateUrl('antecedentepenal'));
            return $this->redirect($this->generateUrl('antecedentepenal', array('id_empleado' => $eEmpleado->getId())));
        }

        return $this->render('PlanillasEntidadesBundle:EAntecedentePenal:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a EAntecedentePenal entity.
    *
    * @param EAntecedentePenal $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(EAntecedentePenal $entity)
    {
        $form = $this->createForm(new EAntecedentePenalType(true), $entity, array(
            'action' => $this->generateUrl('antecedentepenal_create', array('id_empleado'=>$entity->getEmpleado()->getId())),
            'method' => 'POST',
        ));

        //$form->add('submit', 'submit', array('label' => 'Crear', 'attr'=>array('class'=>'btn btn-success')));

        return $form;
    }

    /**
     * Displays a form to create a new EAntecedentePenal entity.
     *
     */
    public function newAction($id_empleado)
    {
        $em = $this->getDoctrine()->getManager();
        $eEmpleado = $em->getRepository('PlanillasCoreBundle:CEmpleado')->find($id_empleado);
        
        $entity = new EAntecedentePenal();
        $entity->setEmpleado($eEmpleado);
        $form   = $this->createCreateForm($entity);

        return $this->render('PlanillasEntidadesBundle:EAntecedentePenal:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a EAntecedentePenal entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PlanillasEntidadesBundle:EAntecedentePenal')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find EAntecedentePenal entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('PlanillasEntidadesBundle:EAntecedentePenal:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing EAntecedentePenal entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PlanillasEntidadesBundle:EAntecedentePenal')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find EAntecedentePenal entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('PlanillasEntidadesBundle:EAntecedentePenal:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a EAntecedentePenal entity.
    *
    * @param EAntecedentePenal $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(EAntecedentePenal $entity)
    {
        $form = $this->createForm(new EAntecedentePenalType(true), $entity, array(
            'action' => $this->generateUrl('antecedentepenal_update', array('id' => $entity->getId())),
            'method' => 'post',
        ));

        //$form->add('submit', 'submit', array('label' => 'Actualizar', 'attr'=>array('class'=>'btn btn-primary')));

        return $form;
    }
    /**
     * Edits an existing EAntecedentePenal entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PlanillasEntidadesBundle:EAntecedentePenal')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find EAntecedentePenal entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
        

        if ($editForm->isValid()) {
         
            $em->flush();

            return $this->redirect($this->generateUrl('antecedentepenal_edit', array('id' => $id)));
        }

        return $this->render('PlanillasEntidadesBundle:EAntecedentePenal:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a EAntecedentePenal entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('PlanillasEntidadesBundle:EAntecedentePenal')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find EAntecedentePenal entity.');
            }

            $iIdempleado = $entity->getEmpleado()->getId();
            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('antecedentepenal', array( 'id_empleado'=> $iIdempleado )));
    }

    /**
     * Creates a form to delete a EAntecedentePenal entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('antecedentepenal_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Eliminar', 'attr'=>array('class'=>'btn btn-primary')))
            ->getForm()
        ;
    }
}
