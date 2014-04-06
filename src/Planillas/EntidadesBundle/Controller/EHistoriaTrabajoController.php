<?php

namespace Planillas\EntidadesBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Planillas\EntidadesBundle\Entity\EHistoriaTrabajo;
use Planillas\EntidadesBundle\Form\Type\EHistoriaTrabajoType;

/**
 * EHistoriaTrabajo controller.
 *
 */
class EHistoriaTrabajoController extends Controller
{

    /**
     * Lists all EHistoriaTrabajo entities.
     *
     */
    public function indexAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $eEmpleado = $em->getRepository('PlanillasCoreBundle:CEmpleado')->find($id);
        if (!$eEmpleado) {
          throw $this->createNotFoundException('Unable to find EHistoriaTrabajo entity.');
        }

        $entities = $em->createQuery('Select t from PlanillasEntidadesBundle:EHistoriaTrabajo t where t.empleado='.$id);
        $entities=$entities->getResult();
        //$entities = $em->getRepository('PlanillasEntidadesBundle:EHistoriaTrabajo')->findAll();
        return $this->render('PlanillasEntidadesBundle:EHistoriaTrabajo:index.html.twig', array(
            'entities' => $entities,
            'eEmpleado'=>$eEmpleado,
            'id_empleado'=>$id,
        ));
    }
    /**
     * Creates a new EHistoriaTrabajo entity.
     *
     */
    public function createAction(Request $request,$id_empleado)
    {
        //echo $idempleado;exit;
        $entity = new EHistoriaTrabajo();
        /*Obtener el empleado en cuestion*/
        $em = $this->getDoctrine()->getManager();
        //$formrequest=$request->request->get('planillas_entidadesbundle_ehistoriatrabajo');
        $eEmpleado = $em->getRepository('PlanillasCoreBundle:CEmpleado')->find($id_empleado);

        $entity->setEmpleado($eEmpleado);

        //creando y validando el formulario
        $form = $this->createCreateForm($entity);

        $form->handleRequest($request);
        if ($form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('ctrabajo_new', array('id_empleado' => $eEmpleado->getId())));
        }

        return $this->render('PlanillasEntidadesBundle:EHistoriaTrabajo:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'eEmpleado'=>$eEmpleado
        ));
    }

    /**
    * Creates a form to create a EHistoriaTrabajo entity.
    *
    * @param EHistoriaTrabajo $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(EHistoriaTrabajo $entity)
    {

        $form = $this->createForm(new EHistoriaTrabajoType(), $entity, array(
            'action' => $this->generateUrl('historiatrabajo_create',array('id_empleado'=>$entity->getEmpleado()->getId())),
            'method' => 'POST',
            //'idEmpleado'=>($entity->getEmpleado()!=null)?$entity->getEmpleado():null
        ));
        //$form->add('submit', 'submit', array('label' => 'Adicionar','attr'=>array('class'=>'btn btn-success')));
        //$form->add('link', 'submit', array('label' => 'Adicionar','attr'=>array('class'=>'btn btn-success')));
        return $form;
    }

    /**
     * Displays a form to create a new EHistoriaTrabajo entity.
     *
     */
    public function newAction($id_empleado)
    {
        $em = $this->getDoctrine()->getManager();
        $eEmpleado = $em->getRepository('PlanillasCoreBundle:CEmpleado')->find($id_empleado);
        $entity = new EHistoriaTrabajo();
        if (!$eEmpleado) {
          throw $this->createNotFoundException('Unable to find CEmpleado entity.');
        }
        $entity->setEmpleado($eEmpleado);
        $form   = $this->createCreateForm($entity);

        return $this->render('PlanillasEntidadesBundle:EHistoriaTrabajo:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'eEmpleado'=>$eEmpleado
        ));
    }

    /**
     * Finds and displays a EHistoriaTrabajo entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PlanillasEntidadesBundle:EHistoriaTrabajo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find EHistoriaTrabajo entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('PlanillasEntidadesBundle:EHistoriaTrabajo:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView()));
    }

    /**
     * Displays a form to edit an existing EHistoriaTrabajo entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PlanillasEntidadesBundle:EHistoriaTrabajo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find EHistoriaTrabajo entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('PlanillasEntidadesBundle:EHistoriaTrabajo:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'eEmpleado'   => $entity->getEmpleado()
        ));
    }

    /**
    * Creates a form to edit a EHistoriaTrabajo entity.
    *
    * @param EHistoriaTrabajo $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(EHistoriaTrabajo $entity)
    {
        $form = $this->createForm(new EHistoriaTrabajoType(true), $entity, array(
            'action' => $this->generateUrl('historiatrabajo_update', array('id' => $entity->getId())),
            'method' => 'POST',

        ));

        //$form->add('submit', 'submit', array('label' => 'Actualizar','attr'=>array('class'=>'btn btn-primary')));
        return $form;
    }
    /**
     * Edits an existing EHistoriaTrabajo entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PlanillasEntidadesBundle:EHistoriaTrabajo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find EHistoriaTrabajo entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('historiatrabajo_edit', array('id' => $id)));
        }

        return $this->render('PlanillasEntidadesBundle:EHistoriaTrabajo:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'eEmpleado'   => $entity->getEmpleado()
        ));
    }
    /**
     * Deletes a EHistoriaTrabajo entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

       // if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('PlanillasEntidadesBundle:EHistoriaTrabajo')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find EHistoriaTrabajo entity.');
            }
            $eEmpleado=$entity->getEmpleado();
            $em->remove($entity);
            $em->flush();
      //  }
        return $this->redirect($this->generateUrl('ctrabajo_new',array('id_empleado'=>$eEmpleado->getId())));
    }

    /**
     * Creates a form to delete a EHistoriaTrabajo entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('historiatrabajo_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
    public function createFormTrabajo()
    {
        $form = $this->createForm(new EHistoriaTrabajoType(true), $entity, array(
            'action' => $this->generateUrl('historiatrabajo_update', array('id' => $entity->getId())),
            'method' => 'POST',

        ));

        $form->add('submit', 'submit', array('label' => 'Actualizar','attr'=>array('class'=>'btn btn-success')));

        return $form;
    }
}
