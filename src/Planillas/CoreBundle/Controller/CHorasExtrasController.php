<?php

namespace Planillas\CoreBundle\Controller;

use Planillas\CoreBundle\Form\Type\Filters\BuscarHorasExtrasType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Planillas\CoreBundle\Entity\CHorasExtras;
use Planillas\CoreBundle\Form\Type\CHorasExtrasType;

/**
 * CHorasExtras controller.
 *
 */
class CHorasExtrasController extends Controller
{
    /**
     * Lists all CHorasExtras entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $aDatos = array();
        $form = $this->createForm(new BuscarHorasExtrasType());
        $form->handleRequest($request);
        if ($form->isValid()) {

            $aDatos = $form->getData(); //filter data
        }
        //print_r($form->getErrors());exit;
        $result = $em->getRepository('PlanillasCoreBundle:CHorasExtras')->filterHorasExtras($aDatos);
        $paginator = $this->get('knp_paginator');
        $session = $this->get('session')->set('filtros', array()); //hay que meter la busqueda en la sesion
        $pagination = $paginator->paginate(
                $result, $this->get('request')->query->get('page', 1), 10
        );
        $entity = new CHorasExtras();
        $form_new = $this->createCreateForm($entity);

        return $this->render('PlanillasCoreBundle:CHorasExtras:index.html.twig', array(
                    'pagination' => $pagination,
                    'form_buscar' => $form->createView(),
                    'form' => $form_new->createView()
        ));
    }

    /**
     * Creates a new CHorasExtras entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new CHorasExtras();
        $em = $this->getDoctrine()->getManager();
        $form = new CHorasExtrasType();

        $data = $request->get('planillas_id');

        if (isset($data['id']) && !empty($data['id'])) {
            $entity = $em->getRepository('PlanillasCoreBundle:CHorasExtras')->find($data['id']);
            if (!$entity) {
                throw $this->createNotFoundException('Unable to find CHorasExtras entity.');
            } else {
                $form = $this->createEditForm($entity);
            }
        } else {
            $form = $this->createCreateForm($entity);
        }
        $form->handleRequest($request);
        if ($form->isValid()) {

            $em->persist($entity);
            $em->flush();
            //poner un mensaje flash
            $this->get('session')->getFlashBag()->add('info', 'Los datos han sido salvados correctamente');

            return $this->redirect($this->generateUrl('chorasextras'));
        }

        $this->get('session')->getFlashBag()->add('danger', 'No se pudieron guardar los datos');

        return $this->redirect($this->generateUrl('chorasextras'));
    }

    /**
     * Creates a form to create a CHorasExtras entity.
     *
     * @param CHorasExtras $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(CHorasExtras $entity)
    {
        $form = $this->createForm(new CHorasExtrasType(), $entity, array(
            'action' => $this->generateUrl('chorasextras_create'),
            'method' => 'POST',
        ));

        //$form->add('submit', 'submit', array('label' => 'Create'));
        return $form;
    }

    /**
     * Displays a form to create a new CHorasExtras entity.
     *
     */
    public function newAction()
    {
        $entity = new CHorasExtras();
        $form = $this->createCreateForm($entity);

        return $this->render('PlanillasCoreBundle:CHorasExtras:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a CHorasExtras entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PlanillasCoreBundle:CHorasExtras')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CHorasExtras entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('PlanillasCoreBundle:CHorasExtras:show.html.twig', array(
                    'entity' => $entity,
                    'delete_form' => $deleteForm->createView(),));
    }

    /**
     * Displays a form to edit an existing CHorasExtras entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PlanillasCoreBundle:CHorasExtras')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CHorasExtras entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('PlanillasCoreBundle:CHorasExtras:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a CHorasExtras entity.
     *
     * @param CHorasExtras $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(CHorasExtras $entity)
    {
        $form = $this->createForm(new CHorasExtrasType(), $entity, array(
            'action' => $this->generateUrl('chorasextras_update', array('id' => $entity->getId())),
            'method' => 'post',
        ));

        //$form->add('submit', 'submit', array('label' => 'Update'));
        return $form;
    }

    /**
     * Edits an existing CHorasExtras entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PlanillasCoreBundle:CHorasExtras')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CHorasExtras entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('chorasextras_edit', array('id' => $id)));
        }

        return $this->render('PlanillasCoreBundle:CHorasExtras:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a CHorasExtras entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('PlanillasCoreBundle:CHorasExtras')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CHorasExtras entity.');
        }
        if ($entity->getPlanilla() != null) {
            $this->get('session')->getFlashBag()->add('danger', 'No puede eliminar la entidad porque ya que está asociada a una planilla de efectivo.');

            return $this->redirect($this->generateUrl('chorasextras'));
        }
        $em->remove($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('chorasextras'));
    }

    /**
     * Creates a form to delete a CHorasExtras entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('chorasextras_delete', array('id' => $id)))
                        ->setMethod('DELETE')
                        // ->add('submit', 'submit', array('label' => 'Delete'))
                        ->getForm()
        ;
    }

    public function editajaxAction(Request $request)
    {
        $id = $request->get('id');
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('PlanillasCoreBundle:CHorasExtras')->find($id);
        $response = array("success" => true);
        if (!$entity) {
            $response['success'] = false;

            return new \Symfony\Component\HttpFoundation\Response(json_encode($response));
        }
        if ($entity->getPlanilla() != null) {
            $response['success'] = false;
            $response['mensaje'] = 'No puede editar la entidad ya que está asociada a una planilla de efectivo.';

            return new \Symfony\Component\HttpFoundation\Response(json_encode($response));
        }
        $response['data'] = $entity->getJson();

        return new \Symfony\Component\HttpFoundation\Response(json_encode($response));
    }

}
