<?php

namespace Planillas\EntidadesBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Planillas\EntidadesBundle\Entity\EPersonaDependen;
use Planillas\EntidadesBundle\Form\Type\EPersonaDependenType;

/**
 * EPersonaDependen controller.
 *
 */
class EPersonaDependenController extends Controller
{
    /**
     * Lists all EPersonaDependen entities.
     *
     */
    public function indexAction($id_empleado)
    {
        $em = $this->getDoctrine()->getManager();
        $eEmpleado = $em->getRepository('PlanillasCoreBundle:CEmpleado')->find($id_empleado);

        $entities = $em->createQuery('Select f from PlanillasEntidadesBundle:EPersonaDependen f where f.empleado=' . $id_empleado); //
        $entities = $entities->getResult();
        $aDeleteForm = array();
        foreach ($entities as $entity) {
            $aDeleteForm[$entity->getId()] = $this->createDeleteForm($entity->getId())->createView();
        }

        return $this->render('PlanillasEntidadesBundle:EPersonaDependen:index.html.twig', array(
                    'eEmpleado' => $eEmpleado,
                    'aDeleteForm' => $aDeleteForm,
                    'entities' => $entities,
        ));
    }

    /**
     * Creates a new EPersonaDependen entity.
     *
     */
    public function createAction(Request $request, $id_empleado)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = new EPersonaDependen();
        $eEmpleado = $em->getRepository('PlanillasCoreBundle:CEmpleado')->find($id_empleado);

        $entity->setEmpleado($eEmpleado);
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {

            $em->persist($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'Se han adicionado los datos correctamente');

            return $this->redirect($this->generateUrl('personadepende', array(
                'id_empleado' => $entity->getEmpleado()->getId()
            )));
        }

        $this->get('session')->getFlashBag()->add('danger', 'No se pudieron agregar los datos');

        return $this->render('PlanillasEntidadesBundle:EPersonaDependen:new.html.twig', array(
            'eEmpleado' => $eEmpleado,
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a EPersonaDependen entity.
     *
     * @param EPersonaDependen $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(EPersonaDependen $entity)
    {
        $form = $this->createForm(new EPersonaDependenType(), $entity, array(
            'action' => $this->generateUrl('personadepende_create', array('id_empleado' => $entity->getEmpleado()->getId())),
            'method' => 'POST',
        ));

        // $form->add('submit', 'submit', array('label' => 'Adicionar','attr'=>array('class'=>'btn btn-primary')));
        return $form;
    }

    /**
     * Displays a form to create a new EPersonaDependen entity.
     *
     */
    public function newAction($id_empleado)
    {
        $em = $this->getDoctrine()->getManager();
        $eEmpleado = $em->getRepository('PlanillasCoreBundle:CEmpleado')->find($id_empleado);

        $entity = new EPersonaDependen();
        $entity->setEmpleado($eEmpleado);
        $form = $this->createCreateForm($entity);

        return $this->render('PlanillasEntidadesBundle:EPersonaDependen:new.html.twig', array(
                    'eEmpleado' => $eEmpleado,
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a EPersonaDependen entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PlanillasEntidadesBundle:EPersonaDependen')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find EPersonaDependen entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('PlanillasEntidadesBundle:EPersonaDependen:show.html.twig', array(
                    'entity' => $entity,
                    'delete_form' => $deleteForm->createView(),));
    }

    /**
     * Displays a form to edit an existing EPersonaDependen entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PlanillasEntidadesBundle:EPersonaDependen')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find EPersonaDependen entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('PlanillasEntidadesBundle:EPersonaDependen:edit.html.twig', array(
                    'entity' => $entity,
                    'eEmpleado' => $entity->getEmpleado(),
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a EPersonaDependen entity.
     *
     * @param EPersonaDependen $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(EPersonaDependen $entity)
    {
        $form = $this->createForm(new EPersonaDependenType(true), $entity, array(
            'action' => $this->generateUrl('personadepende_update', array('id' => $entity->getId())),
            'method' => 'POST',
        ));

        //$form->add('submit', 'submit', array('label' => 'Actualizar'));
        return $form;
    }

    /**
     * Edits an existing EPersonaDependen entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PlanillasEntidadesBundle:EPersonaDependen')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find EPersonaDependen entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'Se han actualizado los datos correctamente.');

            return $this->redirect($this->generateUrl('personadepende_edit', array('id' => $id)));
        }
        $this->get('session')->getFlashBag()->add('danger', 'No se pudieron actualizar los datos.');

        return $this->render('PlanillasEntidadesBundle:EPersonaDependen:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a EPersonaDependen entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('PlanillasEntidadesBundle:EPersonaDependen')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find EPersonaDependen entity.');
            }
            $iIdEmpleado = $entity->getEmpleado()->getId();
            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('personadepende', array('id_empleado' => $iIdEmpleado)));
    }

    /**
     * Creates a form to delete a EPersonaDependen entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('personadepende_delete', array('id' => $id)))
                        ->setMethod('DELETE')
                        ->add('submit', 'submit', array('label' => 'Eliminar', 'attr' => array('class' => 'btn btn-primary')))
                        ->getForm()
        ;
    }

}
