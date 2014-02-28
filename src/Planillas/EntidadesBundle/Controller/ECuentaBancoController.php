<?php

namespace Planillas\EntidadesBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Planillas\CoreBundle\Entity\CEmpleado;
use Planillas\NomencladorBundle\Entity\NBanco;
use Planillas\EntidadesBundle\Form\Type\NBancoType;

/**
 * ECursos controller.
 *
 */
class ECuentaBancoController extends Controller
{

    /**
     * Lists all ECursos entities.
     *
     */
    public function indexAction($id_empleado)
    { 
        $em = $this->getDoctrine()->getManager();
        $eEmpleado = $em->getRepository('PlanillasCoreBundle:CEmpleado')->find($id_empleado);
        
        $entities = $eEmpleado->getCuentasBancos();
        
        //$entities = $em->createQuery('Select f from PlanillasEntidadesBundle:ECursos f where f.empleado='.$id_empleado);
        //$entities=$entities->getResult();
        $aDeleteForm =  array();
        foreach($entities as $entity){
            $aDeleteForm[$entity->getId()] = $this->createDeleteForm($entity->getId(), $eEmpleado->getId())->createView(); 
        }
        return $this->render('PlanillasEntidadesBundle:ECuentaBanco:index.html.twig', array(
            'entities' => $entities,
            'eEmpleado'=>$eEmpleado,
            'aDeleteForm'=>$aDeleteForm,
        ));
        
        
    }
    /**
     * Creates a new ECursos entity.
     *
     */
    public function createAction(Request $request, $id_empleado)
    {
        $em = $this->getDoctrine()->getManager();
        $eEmpleado = $em->getRepository('PlanillasCoreBundle:CEmpleado')->find($id_empleado);
        
        if (!$eEmpleado) {
            throw $this->createNotFoundException('Unable to find CEmpleado entity.');
        }
        
        $entity = new NBanco();
        $entity->addEmpleado($eEmpleado);
        
        $form = $this->createCreateForm($entity, $eEmpleado);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            return $this->redirect($this->generateUrl('cuentasbancos', array('id_empleado' => $eEmpleado->getId())));
        }

        return $this->render('PlanillasEntidadesBundle:ECursos:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a ECursos entity.
    *
    * @param ECursos $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(NBanco $entity, CEmpleado $oEmpleado)
    {
        $form = $this->createForm(new NBancoType(), $entity, array(
            'action' => $this->generateUrl('cuentasbancos_create', array('id_empleado'=>$oEmpleado->getId())),
            'method' => 'POST',
        ));

        //$form->add('submit', 'submit', array('label' => 'Crear', 'attr'=>array('class'=>'btn btn-success')));

        return $form;
    }

    /**
     * Displays a form to create a new ECursos entity.
     *
     */
    public function newAction($id_empleado)
    {
        $em = $this->getDoctrine()->getManager();
        $eEmpleado = $em->getRepository('PlanillasCoreBundle:CEmpleado')->find($id_empleado);
        
        $entity = new NBanco();
        $entity->addEmpleado($eEmpleado);
        $form   = $this->createCreateForm($eEmpleado);

        return $this->render('PlanillasEntidadesBundle:ECuentaBanco:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'empleado' => $eEmpleado
        ));
    }

    /**
     * Finds and displays a ECursos entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PlanillasEntidadesBundle:ECursos')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ECursos entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('PlanillasEntidadesBundle:ECursos:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing ECursos entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PlanillasCoreBundle:CEmpleado')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CEmpleado entity.');
        }

        $editForm = $this->createEditForm($entity);
        //$deleteForm = $this->createDeleteForm($id);

        return $this->render('PlanillasEntidadesBundle:ECuentaBanco:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            //'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a ECursos entity.
    *
    * @param ECursos $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(CEmpleado $entity)
    {
        $form = $this->createForm(new NBancoType(), $entity, array(
            'action' => $this->generateUrl('cuentasbancos_update', array('id' => $entity->getId())),
            'method' => 'post',
        ));

        //$form->add('submit', 'submit', array('label' => 'Actualizar', 'attr'=>array('class'=>'btn btn-success')));

        return $form;
    }
    /**
     * Edits an existing ECursos entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PlanillasCoreBundle:CEmpleado')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CEmpleado entity.');
        }

        //$deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('cuentasbancos_edit', array('id' => $id)));
        }

        return $this->render('PlanillasEntidadesBundle:ECuentaBanco:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            //'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a ECursos entity.
     *
     */
    public function deleteAction(Request $request, $id_banco, $id_empleado)
    {
        $form = $this->createDeleteForm($id_banco, $id_empleado);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $eBanco = $em->getRepository('PlanillasNomencladorBundle:NBanco')->find($id_banco);
            $eEmpleado = $em->getRepository('PlanillasCoreBundle:CEmpleado')->find($id_empleado);

            if (!$eBanco || !$eEmpleado) {
                throw $this->createNotFoundException('Unable to find CEmpleado or NBanco entity!!');
            }
            $eEmpleado->removeCuentasBanco($eBanco);
            //$em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('cuentasbancos', array( 'id_empleado'=> $eEmpleado->getId() )));
    }

    /**
     * Creates a form to delete a ECursos entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id_banco, $id_empleado)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('cuentasbancos_delete', array('id_banco' => $id_banco, 'id_empleado'=>$id_empleado)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Eliminar', 'attr'=>array('class'=>'btn btn-default')))
            ->getForm()
        ;
    }
}
