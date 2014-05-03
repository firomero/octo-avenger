<?php

namespace Planillas\CoreBundle\Controller;

use Planillas\CoreBundle\Form\Models\IncapacidadesModel;
use Planillas\CoreBundle\Form\Type\Filters\BuscarIncapacidadesType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Planillas\CoreBundle\Entity\CIncapacidades;
use Planillas\CoreBundle\Form\Type\CIncapacidadesType;

/**
 * CIncapacidades controller.
 *
 */
class CIncapacidadesController extends Controller
{

    /**
     * Lists all CIncapacidades entities.
     *
     */
    public function indexAction()
    {

        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $aDatos = array();
        $form = $this->createForm(new BuscarIncapacidadesType());
        $form->handleRequest($request);
        if ($form->isValid()) {
            $aDatos = $form->getData(); //filter data
            $this->get('session')->set('incapacidades.filtros', $aDatos);
        }

        $paginator = $this->get('knp_paginator');
        if ($request->get('query')) {
            $filtros = array();
            $this->get('session')->set('incapacidades.filtros', $filtros);
        }

        $filtros = $this->get('session')->get('incapacidades.filtros');
        $this->get('session')->set('incapacidades.page', $this->get('request')->query->get('page', 1));
        $page = (int) $this->get('session')->get('incapacidades.page', $this->get('request')->query->get('page', 1));
        $result = $em->getRepository('PlanillasCoreBundle:CIncapacidades')->filterIncapacidades($filtros);

        $pagination = $paginator->paginate(
            $result, $page, 10
        );

        $entity = new IncapacidadesModel();
        $form_new = $this->createFormNew($entity);

        return $this->render('PlanillasCoreBundle:CIncapacidades:index.html.twig', array(
            'pagination' => $pagination,
            'form_buscar' => $form->createView(),
            'form' => $form_new->createView()
        ));

    }

    /**
     * Creates a new CIncapacidades entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new IncapacidadesModel();
        $em = $this->getDoctrine()->getManager();

        $data = $request->get('planillas_id'); //solo valido para la forma de tab

        if (isset($data['id']) && !empty($data['id'])) {
            $entity = $em->getRepository('PlanillasCoreBundle:CIncapacidades')->find($data['id']);
            if (!$entity) {
                throw $this->createNotFoundException('Unable to find CIncapacidades entity.');
            } else {
                $form = $this->createEditForm($entity);
            }
        } else {
            $form = $this->createFormNew($entity);
        }

        $form->handleRequest($request);
        if ($form->isValid()) {
            $incapacidadesManager = $this->get('payments.incapacidades.manager');

            /** @var \Planillas\CoreBundle\Form\Models\IncapacidadesModel $model */
            $model = $form->getData();
            if ($incapacidadesManager->createIncapacidad($model)) {
                //poner un mensaje flash
                $this->get('session')->getFlashBag()->add('info', 'Los datos se han guardado correctamente');

                return $this->redirect($this->generateUrl('cincapacidades'));
            }
        }

        $this->get('session')->getFlashBag()->add('danger', 'No se pudieron guardados los datos');

        return $this->redirect($this->generateUrl('cincapacidades'));
    }

    /**
     * Creates a form to create a CIncapacidades entity.
     *
     * @param CIncapacidades $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createFormNew(IncapacidadesModel $entity)
    {
        $form = $this->createForm(new CIncapacidadesType(), $entity, array(
            'action' => $this->generateUrl('cincapacidades_create'),
            'method' => 'POST',
        ));

        //$form->add('submit', 'submit', array('label' => 'Create'));
        return $form;
    }

    /**
     * Displays a form to create a new CIncapacidades entity.
     *
     */
    public function newAction()
    {
        $entity = new IncapacidadesModel();
        $form = $this->createFormNew($entity);

        return $this->render('PlanillasCoreBundle:CIncapacidades:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a CIncapacidades entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PlanillasCoreBundle:CIncapacidades')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CIncapacidades entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('PlanillasCoreBundle:CIncapacidades:show.html.twig', array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),));
    }

    /**
     * Displays a form to edit an existing CIncapacidades entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PlanillasCoreBundle:CIncapacidades')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CIncapacidades entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('PlanillasCoreBundle:CIncapacidades:edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a CIncapacidades entity.
     *
     * @param CIncapacidades $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(CIncapacidades $entity)
    {
        $form = $this->createForm(new CIncapacidadesType(), $entity, array(
            'action' => $this->generateUrl('cincapacidades_update', array('id' => $entity->getId())),
            'method' => 'POST',
        ));

        //$form->add('submit', 'submit', array('label' => 'Update'));
        return $form;
    }

    /**
     * Edits an existing CIncapacidades entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PlanillasCoreBundle:CIncapacidades')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CIncapacidades entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('cincapacidades_edit', array('id' => $id)));
        }

        return $this->render('PlanillasCoreBundle:CIncapacidades:edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a CIncapacidades entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        try {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('PlanillasCoreBundle:CIncapacidades')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find CIncapacidades entity.');
            }
            if ($entity->getPlanillaEmpleado()!=null) {
                $this->get('session')->getFlashBag()->add('danger', 'No puede eliminar la entidad porque ya que está asociada a una planilla de efectivo');

                return $this->redirect($this->generateUrl('cincapacidades'));
            }
            $em->remove($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('info', 'Los datos han sido eliminados correctamente');

        } catch (\Exception $e) {
            $this->get('session')->getFlashBag()->add('danger', 'No se pudieron eliminar los datos');
        }

        return $this->redirect($this->generateUrl('cincapacidades'));
    }

    /**
     * Creates a form to delete a CIncapacidades entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('cincapacidades_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm();
    }

    public function editajaxAction(Request $request)
    {
        $id = $request->get('id');
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('PlanillasCoreBundle:CIncapacidades')->find($id);
        $response = array("success" => true);
        if (!$entity) {
            $response['success'] = false;
            $response['mensaje']='No existe la entidad.';

            return new \Symfony\Component\HttpFoundation\Response(json_encode($response));
        }
        if($entity->getPlanillaEmpleado()!=null)
        {
            $response['success'] = false;
            $response['mensaje']='No puede editar la entidad ya que está asociada a una planilla de efectivo.';

            return new \Symfony\Component\HttpFoundation\Response(json_encode($response));
        }

        $response['data'] = $entity->getJson();

        return new \Symfony\Component\HttpFoundation\Response(json_encode($response));
    }

    /*Filtros*/
    protected function getFiltros()
    {
        return $this->get('session')->get('incapacidades.filtros', array());

    }

    protected function setFiltros($filtros)
    {
        $this->get('session')->get('incapacidades.filtros', $filtros);
    }

    protected function setPage($page)
    {
        $this->getUser()->setAttribute('incapacidades.page', $page);
    }

    protected function getPage()
    {
        return $this->getUser()->getAttribute('incapacidades.page', 1);
    }

}
