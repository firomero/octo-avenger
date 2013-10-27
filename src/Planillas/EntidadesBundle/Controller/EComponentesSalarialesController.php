<?php

namespace Planillas\EntidadesBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Planillas\EntidadesBundle\Entity\EComponentesSalariales;
use Planillas\EntidadesBundle\Form\EComponentesSalarialesType;

/**
 * EComponentesSalariales controller.
 *
 */
class EComponentesSalarialesController extends Controller
{

    /**
     * Lists all EComponentesSalariales entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('PlanillasEntidadesBundle:EComponentesSalariales')->findAll();

        return $this->render('PlanillasEntidadesBundle:EComponentesSalariales:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    public function componentesByIdEmpleadoAction($id_empleado)
    {
        $em = $this->getDoctrine()->getManager();
        $entities=$em->getRepository('PlanillasEntidadesBundle:EComponentesSalariales')->findBy(array('empleado'=>$id_empleado));
		$aDeleteForm =  array();
        foreach($entities as $entity){

            $aDeleteForm[$entity->getId()] = $this->createDeleteForm($entity->getId())->createView(); 
        }
        //print_r($aDeleteForm);exit;
        return $this->render('PlanillasEntidadesBundle:EComponentesSalariales:componentes.html.twig', array(
            'entities' => $entities,
			'aDeleteForm'=>$aDeleteForm,
            
        ));
        
    }
	public function salarioTotalByIdEmpleadoAction($id_empleado)
	{
	    $em = $this->getDoctrine()->getManager();
		$eEmpleado=$em->getRepository('PlanillasCoreBundle:CEmpleado')->find($id_empleado);
		if(!$eEmpleado)
		{
		 throw $this->createNotFoundException('Unable to find CEmpleado entity.');
		}
		$salarioBase=($eEmpleado->getSalarioBase()!=null)?$eEmpleado->getSalarioBase()->getSalarioBase():0;
		
		$componentesSalariales=$eEmpleado->getComponentesSalariales();
		if(count($componentesSalariales)>0){
		  foreach($componentesSalariales as $componenteSalarial){
		    
		    if($componenteSalarial->getComponente()==0)
		    $salarioBase-=$componenteSalarial->getCantidad();
			else $salarioBase+=$componenteSalarial->getCantidad();
		  }
		}
        return $this->render('PlanillasEntidadesBundle:EComponentesSalariales:salariototal.html.twig', array(
            'salarioTotal' => $salarioBase,
			
            
        ));
	}
    /**
     * Creates a new EComponentesSalariales entity.
     *
     */
    public function createAction(Request $request,$id_empleado)
    {   $em = $this->getDoctrine()->getManager();
		$eEmpleado=$em->getRepository('PlanillasCoreBundle:CEmpleado')->find($id_empleado);
		if(!$eEmpleado){
		 throw $this->createNotFoundException('Unable to find CEmpleado entity.');
		}
        $entity = new EComponentesSalariales();
		$entity->setEmpleado($eEmpleado);
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('info', 'Los datos han sido adicionados correctamente.');
            return $this->redirect($this->generateUrl('salariobase_new', array('id_empleado' => $entity->getEmpleado()->getId())));
        }
        $this->get('session')->getFlashBag()->add('danger', 'Se detectaron errores al guardar los datos.');
        return $this->render('PlanillasEntidadesBundle:EComponentesSalariales:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
			'eEmpleado'=>$eEmpleado
        ));
    }

    /**
    * Creates a form to create a EComponentesSalariales entity.
    *
    * @param EComponentesSalariales $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(EComponentesSalariales $entity)
    {
        $form = $this->createForm(new EComponentesSalarialesType(), $entity, array(
            'action' => $this->generateUrl('ecomponentessalariales_create',array('id_empleado'=>$entity->getEmpleado()->getId())),
            'method' => 'POST',
			'attr'=>array('class'=>"form-horizontal")
        ));

        $form->add('submit', 'submit', array('label' => 'Agregar','attr'=>array('class'=>"btn btn-primary")));

        return $form;
    }

    /**
     * Displays a form to create a new EComponentesSalariales entity.
     *
     */
    public function newAction($id_empleado)
    {
	    $em = $this->getDoctrine()->getManager();
        $eEmpleado = $em->getRepository('PlanillasCoreBundle:CEmpleado')->find((int)$id_empleado);
        if (!$eEmpleado) {
            throw $this->createNotFoundException('Unable to find CEmpleado entity.');
        }
        $entity = new EComponentesSalariales();
		$entity->setEmpleado($eEmpleado);
        $form   = $this->createCreateForm($entity);
		//print_r($entity->getEmpleado());exit;
        return $this->render('PlanillasEntidadesBundle:EComponentesSalariales:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
			'eEmpleado'=>$eEmpleado
        ));
    }

    /**
     * Finds and displays a EComponentesSalariales entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PlanillasEntidadesBundle:EComponentesSalariales')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find EComponentesSalariales entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('PlanillasEntidadesBundle:EComponentesSalariales:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing EComponentesSalariales entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PlanillasEntidadesBundle:EComponentesSalariales')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find EComponentesSalariales entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('PlanillasEntidadesBundle:EComponentesSalariales:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a EComponentesSalariales entity.
    *
    * @param EComponentesSalariales $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(EComponentesSalariales $entity)
    {
        $form = $this->createForm(new EComponentesSalarialesType(true), $entity, array(
            'action' => $this->generateUrl('ecomponentessalariales_update', array('id' => $entity->getId())),
            'method' => 'PUT',
			'attr'=>array('class'=>"form-horizontal")
        ));

        //$form->add('submit', 'submit', array('label' => 'Actualizar','attr'=>array('class'=>'btn btn-success')));
		//$form->add('button', 'button', array('label' => 'Volver','attr'=>array('class'=>'btn btn-success')));

        return $form;
    }
    /**
     * Edits an existing EComponentesSalariales entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PlanillasEntidadesBundle:EComponentesSalariales')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find EComponentesSalariales entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
            $this->get('session')->getFlashBag()->add('info', 'Los datos han sido modificados correctamente.');
            return $this->redirect($this->generateUrl('salariobase_new', array('id_empleado' => $entity->getEmpleado()->getId())));
        }

        return $this->render('PlanillasEntidadesBundle:EComponentesSalariales:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
			
        ));
    }
    /**
     * Deletes a EComponentesSalariales entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        //$form = $this->createDeleteForm($id);
        //$form->handleRequest($request);
        
        //if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('PlanillasEntidadesBundle:EComponentesSalariales')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find EComponentesSalariales entity.');
            }
            $eEmpleado=$entity->getEmpleado();
            $em->remove($entity);
            $em->flush();
       // }
		$this->get('session')->getFlashBag()->add('info', 'Se ha eliminado la entidad correctamente.');
        return $this->redirect($this->generateUrl('salariobase_new', array('id_empleado' => $eEmpleado->getId())));
        //return $this->redirect($this->generateUrl('ecomponentessalariales'));
    }

    /**
     * Creates a form to delete a EComponentesSalariales entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
       //echo $id;exit;
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('ecomponentessalariales_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Borrar','attr'=>array('class'=>'btn btn-primary')))
            ->getForm()
        ;
    }
}
