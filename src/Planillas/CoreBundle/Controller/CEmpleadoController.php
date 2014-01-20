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
class CEmpleadoController extends Controller {

    /**
     * Lists all CEmpleado entities.
     *
     */
    public function indexAction() {

        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $aDatos = array();
        $form = $this->createForm(new BuscarCEmpleadoType());
        $form->handleRequest($request);
        if ($form->isValid()) {
            $aDatos = $form->getData(); //filter data

            $this->get('session')->set('empleado.filtros', $aDatos);
        }

        if ($request->get('query')) {
            $filtros = array();
            $this->get('session')->set('empleado.filtros', $filtros);
        }
        $filtros = $this->get('session')->get('empleado.filtros');
        $this->get('session')->set('empleado.page', $this->get('request')->query->get('page', 1));
        $page = (int) $this->get('session')->get('empleado.page', $this->get('request')->query->get('page', 1));


        //echo $page;exit;

        $result = $em->getRepository('PlanillasCoreBundle:CEmpleado')->filterEmpleado($filtros);
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $result, $page, 10
        );

        return $this->render('PlanillasCoreBundle:CEmpleado:index.html.twig', array(
                    'pagination' => $pagination,
                    'form' => $form->createView()
        ));
    }

    public function updatePhotoAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PlanillasCoreBundle:CEmpleado')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CEmpleado entity.');
        }
        if ($request->getMethod() == "POST") {
            $file = $request->files->get('empleado');
            if ($file) {
                $resultado = $this->get('core.empleado.manager')->uploadFoto($entity, $file['foto']);

                if ($resultado) {
                    return $this->redirect($this->generateUrl('empleado_edit', array('id' => $entity->getId())));
                } else {
                    return $this->render('PlanillasCoreBundle:CEmpleado:foto.html.twig', array(
                                'entity' => $entity,
                    ));
                }
            }
        }
        return $this->render('PlanillasCoreBundle:CEmpleado:foto.html.twig', array(
                    'entity' => $entity,
        ));
    }

    /**
     * Creates a new CEmpleado entity.
     *
     */
    public function createAction(Request $request) {
        $entity = new CEmpleado();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity->setActivo(true);
            $em->persist($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('info', 'Los datos han sido guardados correctamente');
            return $this->redirect($this->generateUrl('empleado_edit', array('id' => $entity->getId())));
        }
        $this->get('session')->getFlashBag()->add('danger', 'No se pudieron guardar los datos');
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
    private function createCreateForm(CEmpleado $entity) {
        $form = $this->createForm(new CEmpleadoType(true), $entity, array(
            'action' => $this->generateUrl('empleado_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Adicionar', 'attr' => array('class' => 'btn btn-primary')));

        return $form;
    }

    /**
     * Displays a form to create a new CEmpleado entity.
     *
     */
    public function newAction() {
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
    public function showAction($id) {
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
    public function editAction($id) {
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
    private function createEditForm(CEmpleado $entity) {
        $form = $this->createForm(new CEmpleadoType(false, $entity->getId()), $entity);
        return $form;
    }

    /**
     * Edits an existing CEmpleado entity.
     *
     */
    public function updateAction(Request $request, $id) {
        $manager = $this->getDoctrine()->getManager();
        $entity = $manager->getRepository('PlanillasCoreBundle:CEmpleado')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CEmpleado entity.');
        }

        //$deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);

        $editForm->handleRequest($request);
        if ($editForm->isValid()) {

            $this->get('core.empleado.manager')->save($entity);
            $this->get('session')->getFlashBag()->add('info', 'Se han actualizado los datos correctamente');
            return $this->redirect($this->generateUrl('empleado_index', array('id' => $id)));
        }
        //print_r($editForm->getErrors());
        //exit;



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
    public function deleteAction(Request $request, $id) {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('PlanillasCoreBundle:CEmpleado')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find CEmpleado entity.');
            }
            $entity->setActivo(false);
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
    private function createDeleteForm($id) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('empleado_delete', array('id' => $id)))
                        ->setMethod('DELETE')
                        ->add('submit', 'submit', array('label' => 'Delete'))
                        ->getForm();
    }

    public function obtenerHorarioAction($id_empleado) {
        $manager = $this->getDoctrine()->getManager();
        $entity = $manager->getRepository('PlanillasCoreBundle:CEmpleado')->find((int) $id_empleado);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CEmpleado entity.');
        }
        $horarios = $manager->getRepository('PlanillasCoreBundle:CHorario')->findAll();
        $html = array();
        if (count($horarios) > 0) {
            foreach ($horarios as $horario) {
                
                    if($entity->getHorario()==$horario)
                    {
                      $html[] = array('value'=>$horario->getId(),'text'=>$horario->getTitulo(),'selected'=>true);  
                    }
                    else
                    $html[] = array('value'=>$horario->getId(),'text'=>$horario->getTitulo(),'selected'=>false);
                
            }
            
        }
        return $this->render('PlanillasCoreBundle:CHorario:horarioempleado.html.twig', array(     
        'eEmpleado' => $entity,
        'html' => $html));
    }
    public function asignarHorarioAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        try{
        $parameters=$request->get('cempleado');
        
        $idEmpleado=isset($parameters['empleado_id'])?$parameters['empleado_id']:0;
        $idHorario = isset($parameters['horario_id']) ? $parameters['horario_id'] : 0;
        $entity = $manager->getRepository('PlanillasCoreBundle:CEmpleado')->find((int)$idEmpleado);
        if(!$entity)
        {
           throw $this->createNotFoundException('Unable to find CEmpleado entity.'); 
           
        }
        $entityHorario = $manager->getRepository('PlanillasCoreBundle:CHorario')->find((int)$idHorario);
        if(!$entityHorario)
        {
           throw $this->createNotFoundException('Unable to find CHorario entity.'); 
           
        }
        $entity->setHorario($entityHorario);
        $manager->persist($entity);
        $manager->flush();
        $this->get('session')->getFlashBag()->add('info', 'Se han actualizado los datos');
        }
        catch(Exception $e)
        {
            $this->get('session')->getFlashBag()->add('danger', 'No se pudieron acutalizar los datos.');
        }
        return $this->redirect($this->generateUrl('chorario_empleado',array('id_empleado'=>$entity->getId())));
        
    }

    /**
     * funcion que busca un determinado empleado
     */
    public function findAction(Request $request) {
        $form = $this->createForm(new CEmpleadoType(), new CEmpleado());
        $form->handleRequest($request);
        $data = $form->getData();

        //print_r($data);exit;
        return $form;
    }

    /* Filtros */

    protected function getFiltros() {
        return $this->get('session')->get('empleado.filtros', array());
        //return $this->getUser()->getAttribute('plan_de_estudio.filters', $this->configuration->getFilterDefaults(), 'admin_module');
    }

    protected function setFiltros($filtros) {
        $this->get('session')->get('empleado.filtros', $filtros);
    }

    protected function setPage($page) {
        $this->get('session')->set('empleado.page', $page);
    }

    protected function getPage() {
        $this->get('session')->set('empleado.page', 1);
    }

}
