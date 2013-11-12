<?php

namespace Planillas\CoreBundle\Controller;

use Planillas\CoreBundle\Entity\CHorarioDias;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Planillas\CoreBundle\Entity\CHorario;
use Planillas\CoreBundle\Form\Type\CHorarioType;

/**
 * CHorario controller.
 *
 */
class CHorarioController extends Controller {
    
    /**
     * Lists all CHorario entities.
     *
     */
    public function indexAction($id_empleado) {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('PlanillasCoreBundle:CHorario')->findAll();

        return $this->render('PlanillasCoreBundle:CHorario:index.html.twig', array(
                    'entities' => $entities,
        ));
    }

    /**
     * Creates a new CHorario entity.
     *
     */
    public function createAction(Request $request,$id_empleado) {
        $em = $this->getDoctrine()->getManager();
        $eEmpleado = $em->getRepository('PlanillasCoreBundle:CEmpleado')->find($id_empleado);
        if (!$eEmpleado) {
            throw $this->createNotFoundException('Unable to find CEmpleado entity.');
        }
        $c=$request->get('planillas_corebundle_chorario');

        $entity = new CHorario();
        $entity->setEmpleado($eEmpleado);
        $form = $this->createCreateForm($entity);
        //$dias=$entity->getHorarioDias();


        $form->handleRequest($request);

        if ($form->isValid()) {

            try{
            print_r($form->isValid());
            $em->persist($entity);
            $em->flush();
            return $this->redirect($this->generateUrl('chorario_new', array('id_empleado' => $entity->getEmpleado()->getId())));
            }
            catch(Exception $e)
            {
                echo $e->getMessage();exit;
            }
        }
        else
        {
            print_r($form->getErrors());exit;
        }

        return $this->render('PlanillasCoreBundle:CHorario:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
                    'eEmpleado'=>$eEmpleado
        ));
    }

    /**
     * Creates a form to create a CHorario entity.
     *
     * @param CHorario $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(CHorario $entity) {
        $form = $this->createForm(new CHorarioType(), $entity, array(
            'action' => $this->generateUrl('chorario_create',array('id_empleado'=>$entity->getEmpleado()->getId())),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Crear','attr'=>array('class'=>'btn btn-primary')));

        return $form;
    }

    /**
     * Displays a form to create a new CHorario entity.
     *
     */
    public function newAction($id_empleado) {
        $em = $this->getDoctrine()->getManager();
        $eEmpleado = $em->getRepository('PlanillasCoreBundle:CEmpleado')->find($id_empleado);
        $entity = $em->getRepository('PlanillasCoreBundle:CHorario')->findOneByEmpleado($eEmpleado->getId());
        if (!$entity) {
            //es horario nuevo entonces creamos todo desde cero
            $entity = new CHorario();
            $lunes = new CHorarioDias();
            $lunes->setDia("Lunes");
            $martes = new CHorarioDias();
            $martes->setDia("Martes");
            $miercoles = new CHorarioDias();
            $miercoles->setDia("Miercoles");
            $jueves = new CHorarioDias();
            $jueves->setDia("Jueves");
            $viernes = new CHorarioDias();
            $viernes->setDia("Viernes");
            $sabado = new CHorarioDias();
            $sabado->setDia("Sabado");
            $entity->addHorarioDia($lunes);
            $entity->addHorarioDia($martes);
            $entity->addHorarioDia($miercoles);
            $entity->addHorarioDia($jueves);
            $entity->addHorarioDia($viernes);
            $entity->addHorarioDia($sabado);
            $entity->setEmpleado($eEmpleado);
            $form = $this->createCreateForm($entity);
        } else {
            
            //$entity->setEmpleado($eEmpleado);
            $form = $this->createEditForm($entity);
        }
        return $this->render('PlanillasCoreBundle:CHorario:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
                    'eEmpleado' => $eEmpleado,
        ));
    }

    /**
     * Finds and displays a CHorario entity.
     *
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PlanillasCoreBundle:CHorario')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CHorario entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('PlanillasCoreBundle:CHorario:show.html.twig', array(
                    'entity' => $entity,
                    'delete_form' => $deleteForm->createView(),));
    }

    /**
     * Displays a form to edit an existing CHorario entity.
     *
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PlanillasCoreBundle:CHorario')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CHorario entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('PlanillasCoreBundle:CHorario:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a CHorario entity.
     *
     * @param CHorario $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(CHorario $entity) {
        $form = $this->createForm(new CHorarioType(true), $entity, array(
            'action' => $this->generateUrl('chorario_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Actualizar','attr'=>array('class'=>'btn btn-primary')));

        return $form;
    }

    /**
     * Edits an existing CHorario entity.
     *
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PlanillasCoreBundle:CHorario')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CHorario entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

           
        }
        else
        {
           // print_r($editForm->getErrors());exit;
        }

        return $this->redirect($this->generateUrl('chorario_new', array('id_empleado' => $entity->getEmpleado()->getId())));
    }

    /**
     * Deletes a CHorario entity.
     *
     */
    public function deleteAction(Request $request, $id) {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('PlanillasCoreBundle:CHorario')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find CHorario entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('yes'));
    }

    /**
     * Creates a form to delete a CHorario entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('chorario_delete', array('id' => $id)))
                        ->setMethod('DELETE')
                        ->add('submit', 'submit', array('label' => 'Delete'))
                        ->getForm()
        ;
    }
    
}
