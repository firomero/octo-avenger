<?php

namespace Planillas\CoreBundle\Controller;

use Doctrine\ORM\EntityManager;
use Planillas\CoreBundle\Form\Type\Filters\BuscarAusenciaType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Planillas\CoreBundle\Entity\CAusencias;
use Planillas\CoreBundle\Form\Type\CAusenciasType;

/**
 * CAusencias controller.
 *
 */
class CAusenciasController extends Controller
{
    /**
     * Lists all CAusencias entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $parameters = $this->createHistorialAusencias($request, $em);

        $entity = new CAusencias();
        $form = $this->createCreateForm($entity);

        return $this->render('PlanillasCoreBundle:CAusencias:index.html.twig', array(
            'pagination' => $parameters['pagination'],
            'form_buscar' => $parameters['search_form']->createView(),
            'form' => $form->createView()
        ));
    }

    /**
     * Creates a new CAusencias entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new CAusencias();
        $em = $this->getDoctrine()->getManager();

        $data = $request->get('planillas_id');
        if (isset($data['id']) && !empty($data['id'])) {
            $entity = $em->getRepository('PlanillasCoreBundle:CAusencias')->find($data['id']);
            if (!$entity) {
                throw $this->createNotFoundException('Unable to find CAusencias entity.');
            } else {
                $form = $this->createEditForm($entity);
            }
        } else {
            $form = $this->createCreateForm($entity);
        }

        $form->handleRequest($request);
        if ($form->isValid()) {
            //validacion manual por lo que veo esta basura no valida bien
            if (!self::isValidaDataRange($entity)) {
                $this->get('session')->getFlashBag()->add('danger', 'No se pudieron salvar los datos ya que existen errores en las fechas.');

                return $this->redirect($this->generateUrl('causencias'));
            }

            if ($entity->getPlanilla() != null || $entity->getPlanilla() != 0) {
                $this->get('session')->getFlashBag()->add('danger', 'No se puede modificar ya que está asociada a un planilla de pago');

                return $this->redirect($this->generateUrl('causencias'));
            }
            $em->persist($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add('info', 'Los datos han sido guardados correctamente');

            return $this->redirect($this->generateUrl('causencias'));
        }

        $this->get('session')->getFlashBag()->add('danger', 'No se pudieron guardar los datos');

        $parameters = $this->createHistorialAusencias($request, $em);

        return $this->render('PlanillasCoreBundle:CAusencias:index.html.twig', array(
            'pagination' => $parameters['pagination'],
            'form_buscar' => $parameters['search_form']->createView(),
            'form' => $form->createView(),
            'formactive' => true,
        ));
    }

    private function createHistorialAusencias(Request $request, EntityManager $em)
    {
        $aDatos = array();
        $form = $this->createForm(new BuscarAusenciaType());

        $form->handleRequest($request);
        if ($form->isValid()) {
            $aDatos = $form->getData(); //filter data
        }

        $result = $em->getRepository('PlanillasCoreBundle:CAusencias')->filterAusencias($aDatos);
        $paginator = $this->get('knp_paginator');
        $session = $this->get('session')->set('filtros', array()); //hay que meter la busqueda en la sesion

        $pagination = $paginator->paginate(
            $result,
            $request->query->get('page', 1),
            10
        );

        return array(
            'pagination'  => $pagination,
            'search_form' => $form,
        );
    }

    /**
     * Creates a form to create a CAusencias entity.
     *
     * @param CAusencias $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(CAusencias $entity)
    {
        $form = $this->createForm(new CAusenciasType(), $entity, array(
            'action' => $this->generateUrl('causencias_create'),
            'method' => 'POST',
        ));

        return $form;
    }

    /**
     * Displays a form to create a new CAusencias entity.
     *
     */
    public function newAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entity = new CAusencias();
        $form = $this->createCreateForm($entity);
        $empleados = $em->getRepository('PlanillasCoreBundle:CEmpleado')->findAll();

        return $this->render('PlanillasCoreBundle:CAusencias:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
                    'empleados' => $empleados
        ));
    }

    /**
     * Finds and displays a CAusencias entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PlanillasCoreBundle:CAusencias')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CAusencias entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('PlanillasCoreBundle:CAusencias:show.html.twig', array(
                    'entity' => $entity,
                    'delete_form' => $deleteForm->createView(),));
    }

    /**
     * Displays a form to edit an existing CAusencias entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PlanillasCoreBundle:CAusencias')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CAusencias entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('PlanillasCoreBundle:CAusencias:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a CAusencias entity.
     *
     * @param CAusencias $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(CAusencias $entity)
    {
        $form = $this->createForm(new CAusenciasType(), $entity /* array(
                  'action' => $this->generateUrl('causencias_update', array('id' => $entity->getId())),
                  'method' => 'post',
                  ) */);

        // $form->add('submit', 'submit', array('label' => 'Update'));
        return $form;
    }

    /**
     * Edits an existing CAusencias entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PlanillasCoreBundle:CAusencias')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CAusencias entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('causencias_edit', array('id' => $id)));
        }

        return $this->render('PlanillasCoreBundle:CAusencias:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a CAusencias entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('PlanillasCoreBundle:CAusencias')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CAusencias entity.');
        }
        if ($entity->getPlanilla() != null || $entity->getPlanilla() != 0) {
            $this->get('session')->getFlashBag()->add('danger', 'No se puede eliminar ya que está asociada a un planilla de pago');

            return $this->redirect($this->generateUrl('causencias'));
        }
        $em->remove($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('causencias'));
    }

    /**
     * Creates a form to delete a CAusencias entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('causencias_delete', array('id' => $id)))
                        ->setMethod('DELETE')
                        ->add('submit', 'submit', array('label' => 'Delete'))
                        ->getForm();
    }

    /* Helper Functions */

    public function autocompletarAction(Request $request)
    {
        $id = $request->get('id');
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('PlanillasCoreBundle:CAusencias')->find($id);
        $response = array("success" => true);
        if (!$entity) {
            $response['success'] = false;

            return new \Symfony\Component\HttpFoundation\Response(json_encode($response));
        }
        $response['data'] = $entity->getJson();

        return new \Symfony\Component\HttpFoundation\Response(json_encode($response));
    }
    /*Helper functions*/
    public static function isValidaDataRange(CAusencias $entity)
    {
        if ($entity->getFechaInicio()==null || $entity->getFechaFin()==null) {
            return false;
        }
        if($entity->getFechaFin()<$entity->getFechaInicio())

           return false;
        return true;
    }

}
