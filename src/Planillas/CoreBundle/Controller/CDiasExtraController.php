<?php

namespace Planillas\CoreBundle\Controller;

use Planillas\CoreBundle\Form\Type\BuscarDiasExtraType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Planillas\CoreBundle\Entity\CDiasExtra;
use Planillas\CoreBundle\Form\Type\CDiasExtraType;

/**
 * CDiasExtra controller.
 *
 */
class CDiasExtraController extends Controller
{
    /**
     * Lists all CDiasExtra entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $aDatos = array();
        $form = $this->createForm(new BuscarDiasExtraType());
        $form->handleRequest($request);
        if ($form->isValid()) {

            $aDatos = $form->getData(); //filter data
            //print_r($aDatos);
            $this->setFiltros($aDatos);
        }

        $paginator = $this->get('knp_paginator');
        if ($request->get('query')) {
            $filtros = array();
            $this->setFiltros($filtros);
        }
        $filtros = $this->getFiltros();
        $this->setPage($this->get('request')->query->get('page', 1));
        $page = (int) $this->getPage(); //$this->get('session')->get('diasextra.page', $this->get('request')->query->get('page', 1));
        $result = $em->getRepository('PlanillasCoreBundle:CDiasExtra')->filterDiasExtra($filtros);
        $pagination = $paginator->paginate(
                $result, $page, $this->getLimite()
        );
        $entity = new CDiasExtra();
        $form_new = $this->createCreateForm($entity);

        return $this->render('PlanillasCoreBundle:CDiasExtra:index.html.twig', array(
                    'pagination' => $pagination,
                    'form_buscar' => $form->createView(),
                    'form' => $form_new->createView()
        ));
    }

    /**
     * Creates a new CDiasExtra entity.
     *
     */
    public function createAction(Request $request)
    {
        /* $entity = new CDiasExtra();
          $form = $this->createCreateForm($entity);
          $form->handleRequest($request);

          if ($form->isValid()) {
          $em = $this->getDoctrine()->getManager();
          $em->persist($entity);
          $em->flush();

          return $this->redirect($this->generateUrl('cdiasextra_show', array('id' => $entity->getId())));
          }

          return $this->render('PlanillasCoreBundle:CDiasExtra:new.html.twig', array(
          'entity' => $entity,
          'form'   => $form->createView(),
          )); */
        $entity = new CDiasExtra();
        $em = $this->getDoctrine()->getManager();
        $form = new CDiasExtraType();

        $data = $request->get('planillas_id'); //solo valido para la forma de tab

        if (isset($data['id']) && !empty($data['id'])) {
            $entity = $em->getRepository('PlanillasCoreBundle:CDiasExtra')->find($data['id']);
            if (!$entity) {
                throw $this->createNotFoundException('Unable to find CDiasExtra entity.');
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
            $this->get('session')->getFlashBag()->add('info', 'Los datos han sido guardados correctamente');

            return $this->redirect($this->generateUrl('cdiasextra'));
        }

        $this->get('session')->getFlashBag()->add('danger', 'No se pudieron guardados los datos');

        return $this->redirect($this->generateUrl('cdiasextra'));
    }

    /**
     * Creates a form to create a CDiasExtra entity.
     *
     * @param CDiasExtra $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(CDiasExtra $entity)
    {
        $form = $this->createForm(new CDiasExtraType(), $entity, array(
            'action' => $this->generateUrl('cdiasextra_create'),
            'method' => 'POST',
        ));

        //$form->add('submit', 'submit', array('label' => 'Create'));
        return $form;
    }

    /**
     * Displays a form to create a new CDiasExtra entity.
     *
     */
    public function newAction()
    {
        $entity = new CDiasExtra();
        $form = $this->createCreateForm($entity);

        return $this->render('PlanillasCoreBundle:CDiasExtra:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a CDiasExtra entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PlanillasCoreBundle:CDiasExtra')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CDiasExtra entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('PlanillasCoreBundle:CDiasExtra:show.html.twig', array(
                    'entity' => $entity,
                    'delete_form' => $deleteForm->createView(),));
    }

    /**
     * Displays a form to edit an existing CDiasExtra entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PlanillasCoreBundle:CDiasExtra')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CDiasExtra entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('PlanillasCoreBundle:CDiasExtra:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a CDiasExtra entity.
     *
     * @param CDiasExtra $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(CDiasExtra $entity)
    {
        $form = $this->createForm(new CDiasExtraType(), $entity, array(
            'action' => $this->generateUrl('cdiasextra_update', array('id' => $entity->getId())),
            'method' => 'POST',
        ));

        //$form->add('submit', 'submit', array('label' => 'Update'));
        return $form;
    }

    /**
     * Edits an existing CDiasExtra entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PlanillasCoreBundle:CDiasExtra')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CDiasExtra entity.');
        }
        if ($entity->getPlanilla() != null || $entity->getPlanilla() != 0) {
            $this->get('session')->getFlashBag()->add('danger', 'No se puede modificar ya que está asociada a un planilla de pago');

            return $this->redirect($this->generateUrl('cdiasextra'));
        }
        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('cdiasextra_edit', array('id' => $id)));
        }

        return $this->render('PlanillasCoreBundle:CDiasExtra:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a CDiasExtra entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        try {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('PlanillasCoreBundle:CDiasExtra')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find CDiasExtra entity.');
            }
            if ($entity->getPlanilla() != null || $entity->getPlanilla() != 0) {
                $this->get('session')->getFlashBag()->add('danger', 'No se puede eliminar ya que está asociada a un planilla de pago');

                return $this->redirect($this->generateUrl('cdiasextra'));
            }
            $em->remove($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('info', 'Los datos han sido eliminados correctamente');
        } catch (Exception $e) {
            $this->get('session')->getFlashBag()->add('info', 'No se pudieron eliminar los datos');
        }

        return $this->redirect($this->generateUrl('cdiasextra'));
    }

    public function editajaxAction(Request $request)
    {
        $id = $request->get('id');
        //print_r($id);exit;
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('PlanillasCoreBundle:CDiasExtra')->find($id);
        $response = array("success" => true);
        if (!$entity) {
            $response['success'] = false;

            return new \Symfony\Component\HttpFoundation\Response(json_encode($response));
        }
        $response['data'] = $entity->getJson();

        return new \Symfony\Component\HttpFoundation\Response(json_encode($response));
    }

    /**
     * Creates a form to delete a CDiasExtra entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('cdiasextra_delete', array('id' => $id)))
                        ->setMethod('DELETE')
                        ->add('submit', 'submit', array('label' => 'Delete'))
                        ->getForm()
        ;
    }

    /* Filtros */

    protected function getFiltros()
    {
        return $this->get('session')->get('cdiasextra.filtros', array());
    }

    protected function setFiltros($filtros)
    {
        $this->get('session')->set('cdiasextra.filtros', $filtros);
    }

    protected function setPage($page)
    {
        $this->get('session')->set('cdiasextra.page', $page);
    }

    protected function getPage()
    {
        return $this->get('session')->get('cdiasextra.page', 1);
    }

    protected function getLimite()
    {
        return $this->get('session')->get('cdiasextra.limit', 10);
    }

    protected function setLimite($limite)
    {
        $this->get('session')->set('cdiasextra.limit', $limite);
    }

}
