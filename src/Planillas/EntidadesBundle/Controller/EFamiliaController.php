<?php

namespace Planillas\EntidadesBundle\Controller;

use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Planillas\EntidadesBundle\Entity\EFamilia;
use Planillas\EntidadesBundle\Form\Type\EFamiliaType;

/**
 * EFamilia controller.
 *
 */
class EFamiliaController extends Controller {

    /**
     * Lists all EFamilia entities.
     *
     */
    public function indexAction($id) {
        $em = $this->getDoctrine()->getManager();
        $eEmpleado = $em->getRepository('PlanillasCoreBundle:CEmpleado')->find($id);

        $entities = $em->createQuery('Select f from PlanillasEntidadesBundle:EFamilia f where f.empleado=' . $id); // 
        $entities = $entities->getResult();
        $aDeleteForm = array();
        foreach ($entities as $entity) {
            $aDeleteForm[$entity->getId()] = $this->createDeleteForm($entity->getId())->createView();
        }
        return $this->render('PlanillasEntidadesBundle:EFamilia:index.html.twig', array(
                    'eEmpleado' => $eEmpleado,
                    'id_empleado' => $id,
                    'entities' => $entities,
                    'aDeleteForm' => $aDeleteForm,
        ));
    }

    /**
     * Creates a new EFamilia entity.
     *
     */
    public function createAction(Request $request) {
        $entity = new EFamilia();

        $idempleadoParameter = $request->request->get('empleadoid', 0);
        $session = $this->getRequest()->getSession();
        $idempleado = $session->get('empleadoid');
        if ($idempleadoParameter != $idempleado) {
            throw new EntityNotFoundException();
        }
        $form = $this->createCreateForm($entity);
        
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();
        $eEmpleado = $em->getRepository('PlanillasCoreBundle:CEmpleado')->find($idempleado);
        if ($form->isValid()) {

            $entity->setEmpleado($eEmpleado);
            
            //print_r($request->get('planillas_entidadesbundle_efamilia'));exit;
            $em->persist($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'Se han adicionado los datos correctamente');
            return $this->redirect($this->generateUrl('familia', array('id' => $eEmpleado->getId())));
        }
        $this->get('session')->getFlashBag()->add('danger', 'No se pudieron agregar los datos');
        return $this->render('PlanillasEntidadesBundle:EFamilia:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
                    'eEmpleado' => $eEmpleado
        ));
    }

    /**
     * Creates a form to create a EFamilia entity.
     *
     * @param EFamilia $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(EFamilia $entity) {
        $form = $this->createForm(new EFamiliaType(), $entity, array(
            'action' => $this->generateUrl('familia_create'),
            'method' => 'POST',
        ));

        //$form->add('submit', 'submit', array('label' => 'Crear','attr'=>array('class'=>'btn btn-success')));

        return $form;
    }

    /**
     * Displays a form to create a new EFamilia entity.
     *
     */
    public function newAction($id_empleado) {

        $em = $this->getDoctrine()->getManager();
        $eEmpleado = $em->getRepository('PlanillasCoreBundle:CEmpleado')->find($id_empleado);
        $session = $this->getRequest()->getSession();
        $session->set('empleadoid', $eEmpleado->getId());
        $entity = new EFamilia();
        $entity->setEmpleado($eEmpleado);
        $form = $this->createCreateForm($entity);

        return $this->render('PlanillasEntidadesBundle:EFamilia:new.html.twig', array(
                    'eEmpleado' => $eEmpleado,
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a EFamilia entity.
     *
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PlanillasEntidadesBundle:EFamilia')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find EFamilia entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('PlanillasEntidadesBundle:EFamilia:show.html.twig', array(
                    'entity' => $entity,
                    'delete_form' => $deleteForm->createView(),));
    }

    /**
     * Displays a form to edit an existing EFamilia entity.
     *
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PlanillasEntidadesBundle:EFamilia')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find EFamilia entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('PlanillasEntidadesBundle:EFamilia:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
                    'eEmpleado' => $entity->getEmpleado()
        ));
    }

    /**
     * Creates a form to edit a EFamilia entity.
     *
     * @param EFamilia $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(EFamilia $entity) {
        $form = $this->createForm(new EFamiliaType(), $entity, array(
            'action' => $this->generateUrl('familia_update', array('id' => $entity->getId())),
            'method' => 'post',
        ));

        return $form;
    }

    /**
     * Edits an existing EFamilia entity.
     *
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PlanillasEntidadesBundle:EFamilia')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find EFamilia entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'Se han actualizado los datos correctamente.');
            return $this->redirect($this->generateUrl('familia_edit', array('id' => $id)));
        }
        $this->get('session')->getFlashBag()->add('danger', 'No se pudieron actualizar los datos.');
        return $this->render('PlanillasEntidadesBundle:EFamilia:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a EFamilia entity.
     *
     */
    public function deleteAction(Request $request, $id) {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('PlanillasEntidadesBundle:EFamilia')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find EFamilia entity.');
            }
            $iIdempleado = $entity->getEmpleado()->getId();
            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('familia', array('id' => $iIdempleado)));
    }

    /**
     * Creates a form to delete a EFamilia entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('familia_delete', array('id' => $id)))
                        ->setMethod('DELETE')
                        ->add('submit', 'submit', array('label' => 'Eliminar', 'attr' => array('class' => 'btn btn-primary')))
                        ->getForm()
        ;
    }

}
