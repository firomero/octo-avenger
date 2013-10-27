<?php

namespace Planillas\CoreBundle\Controller;

use Planillas\CoreBundle\Form\Type\BuscarCEmpleadoType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Planillas\CoreBundle\Entity\CEmpleado;
use Planillas\CoreBundle\Form\Type\CEmpleadoType;

/**
 * CEmpleado controller.
 *
 */
class CEmpleadoController extends Controller
{

    /**
     * Lists all CEmpleado entities.
     *
     */
    public function indexAction()
    {

        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $aDatos = array();
        $form = $this->createForm(new BuscarCEmpleadoType());
        $form->handleRequest($request);
        if ($form->isValid()) {
            $aDatos = $form->getData(); //filter data
        }
        $result = $em->getRepository('PlanillasCoreBundle:CEmpleado')->filterEmpleado($aDatos);
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $result, $this->get('request')->query->get('page', 1), 1
        );

        return $this->render('PlanillasCoreBundle:CEmpleado:index.html.twig', array(
            'pagination' => $pagination,
            'form' => $form->createView()
        ));
    }
    public function updatePhotoAction(Request $request,$id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PlanillasCoreBundle:CEmpleado')->find($id);
        if(!$entity)
        {
            throw $this->createNotFoundException('Unable to find CEmpleado entity.');
        }
        if($request->getMethod()=="POST")
        {
          $file=$request->files->get('empleado');
          if($file)
          {
              $resultado=$this->get('core.empleado.manager')->uploadFoto($entity,$file['foto']);
              //print_r($resultado);exit;
              if($resultado)
              {
                  return $this->redirect($this->generateUrl('empleado_edit', array('id' => $entity->getId())));
              }
              else
              {
                  return $this->render('PlanillasCoreBundle:CEmpleado:foto.html.twig', array(
                      'entity' => $entity,
                  ));
              }
          }
          print_r($file);exit;
        }
        return $this->render('PlanillasCoreBundle:CEmpleado:foto.html.twig', array(
            'entity' => $entity,
        ));
    }

    /**
     * Creates a new CEmpleado entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new CEmpleado();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('info', 'Los datos han sido adicionados correctamente');
            return $this->redirect($this->generateUrl('empleado_edit', array('id' => $entity->getId())));
        }
        $this->get('session')->getFlashBag()->add('danger', 'No se pudieron agregar los datos');
        return $this->render('PlanillasCoreBundle:CEmpleado:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a CEmpleado entity.
     *
     * @param CEmpleado $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(CEmpleado $entity)
    {
        $form = $this->createForm(new CEmpleadoType(true), $entity, array(
            'action' => $this->generateUrl('empleado_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Adicionar', 'attr' => array('class' => 'btn btn-success')));

        return $form;
    }

    /**
     * Displays a form to create a new CEmpleado entity.
     *
     */
    public function newAction()
    {
        $entity = new CEmpleado();
        $form = $this->createCreateForm($entity);

        return $this->render('PlanillasCoreBundle:CEmpleado:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a CEmpleado entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PlanillasCoreBundle:CEmpleado')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CEmpleado entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('PlanillasCoreBundle:CEmpleado:show.html.twig', array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),));
    }

    /**
     * Displays a form to edit an existing CEmpleado entity.
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
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('PlanillasCoreBundle:CEmpleado:edit.html.twig', array(
            'entity' => $entity,
            'eEmpleado' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a CEmpleado entity.
     *
     * @param CEmpleado $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(CEmpleado $entity)
    {
        $form = $this->createForm(new CEmpleadoType(), $entity);
        return $form;
    }

    /**
     * Edits an existing CEmpleado entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $manager = $this->getDoctrine()->getManager();
        $entity = $manager->getRepository('PlanillasCoreBundle:CEmpleado')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CEmpleado entity.');
        }

        //$deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new CEmpleadoType(), $entity);

        $editForm->handleRequest($request);
        if ($editForm->isValid()) {

            $this->get('core.empleado.manager')->save($entity);
            $this->get('session')->getFlashBag()->add('info', 'Se han actualizado los datos correctamente');
            return $this->redirect($this->generateUrl('empleado_index', array('id' => $id)));
        }


        return $this->render('PlanillasCoreBundle:CEmpleado:edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            //'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a CEmpleado entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('PlanillasCoreBundle:CEmpleado')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find CEmpleado entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('empleado'));
    }

    /**
     * Creates a form to delete a CEmpleado entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('empleado_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm();
    }

    /**
     * funcion que busca un determinado empleado
     */
    public function findAction(Request $request)
    {
        $form = $this->createForm(new CEmpleadoType(), new CEmpleado());
        $form->handleRequest($request);
        $data = $form->getData();

        //print_r($data);exit;
        return $form;
    }

}
