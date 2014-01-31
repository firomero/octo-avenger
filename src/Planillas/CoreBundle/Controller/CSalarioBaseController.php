<?php

namespace Planillas\CoreBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Planillas\CoreBundle\Entity\CSalarioBase;
use Planillas\CoreBundle\Form\Type\CSalarioBaseType;

/**
 * CSalarioBase controller.
 *
 */
class CSalarioBaseController extends Controller {

    /**
     * Lists all CSalarioBase entities.
     *
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('PlanillasCoreBundle:CSalarioBase')->findByPagado(array('pagado'=>0));//los no pagados

        return $this->render('PlanillasCoreBundle:CSalarioBase:index.html.twig', array(
                    'entities' => $entities,
        ));
    }

    /**
     * Creates a new CSalarioBase entity.
     *
     */
    public function createAction(Request $request, $id_empleado) {
        $em = $this->getDoctrine()->getManager();
        $eEmpleado = $em->getRepository('PlanillasCoreBundle:CEmpleado')->find($id_empleado);
        if (!$eEmpleado) {
            throw $this->createNotFoundException('Unable to find CEmpleado entity.');
        }
        $entity = new CSalarioBase();
        $entity->setEmpleado($eEmpleado);

        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {

            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('salariobase_new', array('id_empleado' => $entity->getEmpleado()->getId())));
        }


        return $this->render('PlanillasCoreBundle:CSalarioBase:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a CSalarioBase entity.
     *
     * @param CSalarioBase $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(CSalarioBase $entity) {
        $form = $this->createForm(new CSalarioBaseType(), $entity, array(
            'action' => $this->generateUrl('csalariobase_create', array('id_empleado' => $entity->getEmpleado()->getId())),
            'method' => 'POST',
        ));
        //
        $form->add('submit', 'submit', array('label' => 'Guardar', 'attr' => array('class' => 'btn btn-primary')));

        return $form;
    }

    /**
     * Displays a form to create a new CSalarioBase entity.
     *
     */
    public function newAction($id_empleado) {

        $em = $this->getDoctrine()->getManager();
        $eEmpleado = $em->getRepository('PlanillasCoreBundle:CEmpleado')->find((int) $id_empleado);
        if (!$eEmpleado) {
            throw $this->createNotFoundException('Unable to find CEmpleado entity.');
        }
        $entity = $em->getRepository('PlanillasCoreBundle:CSalarioBase')->findOneByEmpleado($eEmpleado->getId());
        if (!$entity) {
            $entity = new CSalarioBase();
            $entity->setEmpleado($eEmpleado);
            $form = $this->createCreateForm($entity);
        } else {
            $form = $this->createEditForm($entity);
        }
        $entities = $em->getRepository('PlanillasEntidadesBundle:EComponentesSalariales')->findBy(array('empleado' => $id_empleado, 'pagado' => 1));
        return $this->render('PlanillasCoreBundle:CSalarioBase:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
                    'eEmpleado' => $eEmpleado,
                    'entities' => $entities,
        ));
    }

    /**
     * Finds and displays a CSalarioBase entity.
     *
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PlanillasCoreBundle:CSalarioBase')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CSalarioBase entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('PlanillasCoreBundle:CSalarioBase:show.html.twig', array(
                    'entity' => $entity,
                    'delete_form' => $deleteForm->createView(),));
    }

    /**
     * Displays a form to edit an existing CSalarioBase entity.
     *
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PlanillasCoreBundle:CSalarioBase')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CSalarioBase entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);
        $entities = $em->getRepository('PlanillasEntidadesBundle:EComponentesSalariales')->findBy(array('empleado' => $entity->getEmpleado()->getId(), 'pagado' => 1));
        return $this->render('PlanillasCoreBundle:CSalarioBase:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
                    'eEmpleado' => $entity->getEmpleado(),
                    'entities'=>$entities
        ));
    }

    /**
     * Creates a form to edit a CSalarioBase entity.
     *
     * @param CSalarioBase $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(CSalarioBase $entity) {
        $form = $this->createForm(new CSalarioBaseType(true), $entity, array(
            'action' => $this->generateUrl('csalariobase_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Actualizar', 'attr' => array('class' => 'btn btn-primary')));

        return $form;
    }

    /**
     * Edits an existing CSalarioBase entity.
     *
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PlanillasCoreBundle:CSalarioBase')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CSalarioBase entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('csalariobase_edit', array('id' => $id)));
        }

        return $this->render('PlanillasCoreBundle:CSalarioBase:new.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
                    'eEmpleado' => $entity->getEmpleado(),
        ));
    }

    /**
     * Deletes a CSalarioBase entity.
     *
     */
    public function deleteAction(Request $request, $id) {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('PlanillasCoreBundle:CSalarioBase')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find CSalarioBase entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('csalariobase'));
    }

    /**
     * Creates a form to delete a CSalarioBase entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('csalariobase_delete', array('id' => $id)))
                        ->setMethod('DELETE')
                        ->add('submit', 'submit', array('label' => 'Delete'))
                        ->getForm();
    }

}
