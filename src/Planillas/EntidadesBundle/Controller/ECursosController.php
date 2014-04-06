<?php

namespace Planillas\EntidadesBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Planillas\EntidadesBundle\Entity\ECursos;
use Planillas\EntidadesBundle\Form\Type\ECursosType;

/**
 * ECursos controller.
 *
 */
class ECursosController extends Controller
{

    /**
     * Lists all ECursos entities.
     *
     */
    public function indexAction($id_empleado)
    {
        $em = $this->getDoctrine()->getManager();
        $eEmpleado = $em->getRepository('PlanillasCoreBundle:CEmpleado')->find($id_empleado);

        $entities = $em->createQuery('Select f from PlanillasEntidadesBundle:ECursos f where f.empleado='.$id_empleado);
        $entities=$entities->getResult();
        $aDeleteForm =  array();
        foreach ($entities as $entity) {
            $aDeleteForm[$entity->getId()] = $this->createDeleteForm($entity->getId())->createView();
        }

        return $this->render('PlanillasEntidadesBundle:ECursos:index.html.twig', array(
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

        $entity = new ECursos();
        $entity->setEmpleado($eEmpleado);

        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('cursos', array('id_empleado' => $eEmpleado->getId())));
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
    private function createCreateForm(ECursos $entity)
    {
        $form = $this->createForm(new ECursosType(), $entity, array(
            'action' => $this->generateUrl('cursos_create', array('id_empleado'=>$entity->getEmpleado()->getId())),
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

        $entity = new ECursos();
        $entity->setEmpleado($eEmpleado);
        $form   = $this->createCreateForm($entity);

        return $this->render('PlanillasEntidadesBundle:ECursos:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'eEmpleado'=>$eEmpleado
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

        $entity = $em->getRepository('PlanillasEntidadesBundle:ECursos')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ECursos entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('PlanillasEntidadesBundle:ECursos:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a ECursos entity.
    *
    * @param ECursos $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(ECursos $entity)
    {
        $form = $this->createForm(new ECursosType(true), $entity, array(
            'action' => $this->generateUrl('cursos_update', array('id' => $entity->getId())),
            'method' => 'post',
        ));

       // $form->add('submit', 'submit', array('label' => 'Actualizar', 'attr'=>array('class'=>'btn btn-success')));
        return $form;
    }
    /**
     * Edits an existing ECursos entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PlanillasEntidadesBundle:ECursos')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ECursos entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('cursos_edit', array('id' => $id)));
        }

        return $this->render('PlanillasEntidadesBundle:ECursos:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a ECursos entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('PlanillasEntidadesBundle:ECursos')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find ECursos entity!!');
            }

            $iIdempleado = $entity->getEmpleado()->getId();
            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('cursos', array( 'id_empleado'=> $iIdempleado )));
    }

    /**
     * Creates a form to delete a ECursos entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('cursos_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Eliminar', 'attr'=>array('class'=>'btn btn-primary')))
            ->getForm()
        ;
    }
}
