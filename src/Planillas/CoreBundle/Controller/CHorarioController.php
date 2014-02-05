<?php

namespace Planillas\CoreBundle\Controller;

use Planillas\CoreBundle\Entity\CHorarioDias;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Planillas\CoreBundle\Entity\CHorario;
use Planillas\CoreBundle\Entity\CFechaExcepcional;
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
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();

        //$entities = $em->getRepository('PlanillasCoreBundle:CHorario')->findAll();
        //horarios
        $entity = new CHorario();
        $lunes = new CHorarioDias();
        $lunes->setDia("Lunes");
        $lunes->setActivo(true);
        $martes = new CHorarioDias();
        $martes->setDia("Martes");
        $martes->setActivo(true);
        $miercoles = new CHorarioDias();
        $miercoles->setDia("Miércoles");
        $miercoles->setActivo(true);
        $jueves = new CHorarioDias();
        $jueves->setDia("Jueves");
        $jueves->setActivo(true);
        $viernes = new CHorarioDias();
        $viernes->setDia("Viernes");
        $viernes->setActivo(true);
        $sabado = new CHorarioDias();
        $sabado->setDia("Sábado");
        $sabado->setActivo(true);
        $domingo = new CHorarioDias();
        $domingo->setDia("Domingo");
        $domingo->setActivo(true);
        $entity->addHorarioDia($lunes);
        $entity->addHorarioDia($martes);
        $entity->addHorarioDia($miercoles);
        $entity->addHorarioDia($jueves);
        $entity->addHorarioDia($viernes);
        $entity->addHorarioDia($sabado);
        $entity->addHorarioDia($domingo);
        /* Filtros para horario */
        $result = $em->getRepository('PlanillasCoreBundle:CHorario')->filterHorario(array());
        $paginator = $this->get('knp_paginator');
        //$session = $this->get('session')->set('filtros', array()); //hay que meter la busqueda en la sesion
        $pagination = $paginator->paginate(
                $result, $this->get('request')->query->get('page', 1), 10
        );
        $form = $this->createCreateForm($entity);
        return $this->render('PlanillasCoreBundle:CHorario:index.html.twig', array(
                    'pagination' => $pagination,
                    'form' => $form->createView()
        ));
    }

    /**
     * Creates a new CHorario entity.
     *
     */
    public function createAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $data = $request->get('planillas_id');

        $entity = new CHorario();
        if (isset($data['id']) && !empty($data['id'])) {
            $entity = $em->getRepository('PlanillasCoreBundle:CHorario')->find((int) $data['id']);
            if (!$entity) {
                throw $this->createNotFoundException('Unable to find CHorario entity.');
            } else {

                //unset($request->get('id'));
                $form = $this->createEditForm($entity);
            }
        } else {
            //$entity->setMontoRestante(0);
            $form = $this->createCreateForm($entity);
        }
        $form = $this->createCreateForm($entity);


        $form->handleRequest($request);

        if ($form->isValid()) {

            try {

                $em->persist($entity);
                $em->flush();
                $this->get('session')->getFlashBag()->add('info', 'Los datos han sido guardados correctamente');
                return $this->redirect($this->generateUrl('chorario'));
            } catch (Exception $e) {

                $this->get('session')->getFlashBag()->add('danger', $e->getMessage());
                return $this->redirect($this->generateUrl('chorario'));
                //exit;
            }
        }
        $this->get('session')->getFlashBag()->add('danger', 'No se pudieron guardar los datos');
        return $this->redirect($this->generateUrl('chorario'));
        /* return $this->render('PlanillasCoreBundle:CHorario:new.html.twig', array(
          'entity' => $entity,
          'form' => $form->createView(),

          )); */
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
            'action' => $this->generateUrl('chorario_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Crear', 'attr' => array('class' => 'btn btn-primary')));

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

        $form->add('submit', 'submit', array('label' => 'Actualizar', 'attr' => array('class' => 'btn btn-primary')));

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

        return $this->redirect($this->generateUrl('chorario_new', array('id_empleado' => $entity->getEmpleado()->getId())));
    }

    /**
     * Deletes a CHorario entity.
     *
     */
    public function deleteAction(Request $request, $id) {
        try {

            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('PlanillasCoreBundle:CHorario')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find CHorario entity.');
            }

            $em->remove($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('info', 'Los datos han sido eliminados correctamente');
        } catch (Exception $e) {
            $this->get('session')->getFlashBag()->add('info', 'No se pudieron eliminar los datos');
        }
        return $this->redirect($this->generateUrl('chorario'));
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

    public function editajaxAction(Request $request)
    {
        $id = $request->get('id');
        $em = $this->getDoctrine()->getManager();

        /* @var $entity \Planillas\CoreBundle\Entity\CHorario */
        $entity = $em->getRepository('PlanillasCoreBundle:CHorario')->find($id);

        if (!$entity)
        {
            throw $this->createNotFoundException('No se encuentra un horario con id: '.$id);
        }

        $diashorario = $entity->getHorarioDias();
        $cantidaddias = $diashorario->count();

        $response = array();
        $response['success'] = false;
        if ($cantidaddias > 0)
        {
            foreach ($diashorario as $dia)
            {
                $response['dias'][$dia->getDia()]['inicio'] = $dia->getHoraInicio()->format('H:i');
                $response['dias'][$dia->getDia()]['fin'] = $dia->getHoraFin()->format('H:i');
                $response['dias'][$dia->getDia()]['activo'] = $dia->getActivo();
            }
        }

        $response['success'] = true;
        $response['data'] = array('id' => $entity->getId(), 'titulo' => $entity->getTitulo());
        //print_r($response);exit;
        return new \Symfony\Component\HttpFoundation\Response(json_encode($response));
    }

    /**
     * funcion que agrega una fecha excepcional a un determinado horario
     * @param $id id del horario en cuestion
     */
    public function fechaexcepcionalAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('PlanillasCoreBundle:CHorario')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CHorario entity.');
        }


        $fechas = $em->getRepository('PlanillasCoreBundle:CFechaExcepcional')->findByHorario($entity->getId());
        return $this->render('PlanillasCoreBundle:CHorario:fechaexcepcional.html.twig', array(
                    //'pagination' => $pagination,
                    'horario' => $id,
                    'horarioNombre' => $entity->getTitulo(),
                    'entities' => $fechas
                        //'form' => $form->createView()
        ));
    }

    public function empleadosAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('PlanillasCoreBundle:CHorario')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CHorario entity.');
        }

        $empleados = $em->getRepository('PlanillasCoreBundle:CEmpleado')->findByHorario($entity->getId());
        return $this->render('PlanillasCoreBundle:CHorario:empleados.html.twig', array(
                    //'pagination' => $pagination,
                    'horario' => $id,
                    'horarioNombre' => $entity->getTitulo(),
                    'entities' => $empleados
                        //'form' => $form->createView()
        ));

        //echo count($empleados);exit;
    }

    /**
     * funcion  que busca un empleado para asignarle un horario
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function findEmpleadoAction(Request $request) {
        $id = $request->get('id'); //representa el valor entrado en el textbox
        $em = $this->getDoctrine()->getManager();
        $result = $em->getRepository('PlanillasCoreBundle:CEmpleado')->filterEmpleadoAjax(array('data' => $id));
        $data = array();
        $data['success'] = false;
        $data['data'] = array();
        if (count($result)) {
            foreach ($result as $r) {
                $data['data'][] = array(
                    'id' => $r->getId(), 'nombre' => $r->getNombre(),
                    'primerApellido' => $r->getPrimerApellido(),
                    'segundoApellido' => $r->getSegundoApellido());
            }
        }
        $data['success'] = true;
        return new \Symfony\Component\HttpFoundation\Response(json_encode($data));
    }

    /**
     *
     */
    public function addEmpleadoAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        try {
            $listEmpleados = $request->get('empleados');
            $idHorario = $request->get('horario');
            $horario = $em->getRepository('PlanillasCoreBundle:CHorario')->find((int) $idHorario);
            if (!$horario) {
                throwException("Horario no encontrado");
            }

            if (count($listEmpleados) > 0) {
                foreach ($listEmpleados as $key => $value) {

                    $result = $em->getRepository('PlanillasCoreBundle:CEmpleado')->find((int) $value);

                    if ($result) {
                        $result->setHorario($horario);
                        $em->persist($result);
                        $em->flush();
                    }
                }
            }
            $msg = (count($listEmpleados > 0)) ? "Los empleados han sido asignados" : "El empleado ha sido asignado";
            $this->get('session')->getFlashBag()->add('info', $msg . '  al horario correctamente.');
            return $this->redirect($this->generateUrl('chorario_empleados', array('id' => $idHorario)));
        } catch (Exception $e) {
            $this->get('session')->getFlashBag()->add('danger', 'Error al asignar el horario al empleado.');
            return $this->redirect($this->generateUrl('chorario_empleados', array('id' => $idHorario)));
        }
    }

    /*
     * funcion que elimina un empleado de un determnado horario es decir le asigna null
     *
     * */

    public function deleteHorarioEmpleadoAction(Request $request, $idhorario, $idempleado) {
        //$idEmpleado = $request->get('idempleado');
        //$idHorario = $request->get('idhorario');
        try {
            $em = $this->getDoctrine()->getManager();
            $empleado = $em->getRepository('PlanillasCoreBundle:CEmpleado')->find((int) $idempleado);
            if (!$empleado) {
                throw $this->createNotFoundException('Unable to find CEmpleado entity.');
            }
            $empleado->setHorario(null);
            $em->persist($empleado);
            $em->flush();
            $this->get('session')->getFlashBag()->add('info', 'El empleado ha sido eliminado del horario seleccionado.');
            return $this->redirect($this->generateUrl('chorario_empleados', array('id' => $idhorario)));
        } catch (Exception $e) {
            $this->get('session')->getFlashBag()->add('danger', 'Error al intentar eliminar el empleado del horario.');
            return $this->redirect($this->generateUrl('chorario_empleados', array('id' => $idhorario)));
        }
    }

    /**
     * funcion que inserta una fecha excepcional en un horario
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return type retorn un listado de fechas
     * @throws type
     * @throws \Exception
     */
    public function addFechaExcepcionalAction(Request $request) {

        $data = $request->get('planillas_fechaexcepcional');
        try {
            $em = $this->getDoctrine()->getManager();

            $oHorario = $em->getRepository('PlanillasCoreBundle:CHorario')->find((int) $data['horario_id']);
            if (!$oHorario) {
                throw $this->createNotFoundException('Unable to find CHorario entity.');
            }
            if (isset($data['id'])) {
                $id = $data['id'];
                if (!empty($id)) {
                    if (is_numeric($id)) {//estamos editando
                        if (isset($data['fecha']) || $data['fecha'] != "") {
                            $oFecha = $em->getRepository('PlanillasCoreBundle:CFechaExcepcional')->find((int) $data['id']);
                            if (!$oFecha) {
                                throw $this->createNotFoundException('Unable to find CFechaExcepcional entity.');
                            }
                            $oFecha->setFecha($this->convertoMysql($data['fecha']));
                            $oFecha->setObservacion($data['observaciones']);
                            $oFecha->setHorario($oHorario);
                            $em->persist($oFecha);
                        } else {
                            throwException("Invalid Date");
                        }
                    } else {
                        throwException("Invalid number");
                    }
                } else {
                    if (isset($data['fecha']) || $data['fecha'] != "") {//no es tiempo de arreglar esto
                        $oFecha = new CFechaExcepcional();
                        $oFecha->setFecha($this->convertoMysql($data['fecha']));
                        $oFecha->setObservacion($data['observaciones']);
                        $oFecha->setHorario($oHorario);
                        $em->persist($oFecha);
                    } else {
                        throwException("Invalid Date");
                    }
                }
                $em->flush();
                $this->get('session')->getFlashBag()->add('info', 'La fecha ha sido guardada correctamente.');
                return $this->redirect($this->generateUrl('chorario_fecha', array('id' => $oHorario->getId())));
            } else {
                throw new \Exception("No llegan los datos al servidor");
            }
        } catch (Exception $e) {
            $this->get('session')->getFlashBag()->add('danger', 'Error al crear la fecha excepcional.');
            return $this->redirect($this->generateUrl('chorario_fecha', array('id' => $data['horario_id'])));
        }
    }

    public function obtenerFechaAjaxAction(Request $request) {
        $id = $request->get('id');
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('PlanillasCoreBundle:CFechaExcepcional')->find($id);
        $response = array();
        $response['success'] = false;
        if (!$entity) {
            return new \Symfony\Component\HttpFoundation\Response(json_encode($response));
        }
        $response['success'] = true;
        $response['data'] = array(
            'id' => $entity->getId(),
            'fecha'=>$entity->getFecha()->format('Y-m-d'),
            'horario_id'=>$entity->getHorario()->getId(),
            'observaciones' => $entity->getObservacion());
        return new \Symfony\Component\HttpFoundation\Response(json_encode($response));
    }
    /**
     * funncion que borra un fecha excepcional de un horario
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param type $id
     * @return type
     * @throws type
     */
    public function deleteFechaAction(Request $request, $id) {
        try {

            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('PlanillasCoreBundle:CFechaExcepcional')->find($id);
            
            if (!$entity) {
                throw $this->createNotFoundException('Unable to find CFechaExcepcional entity.');
            }
            //es porque me quiere borrar una fecha de un empleado y no de un horario
            if(!$entity->getEmpleado()==null && $entity->getHorario()==null )
            {
              $this->get('session')->getFlashBag()->add('info', 'No se pudieron eliminar los datos. Integridad de los datos afectada');
              return $this->redirect($this->generateUrl('chorario_fecha',array('id'=>$horario)));
            }
           
            if($entity->getHorario()==null)
            {
              //return $this->redirect($this->generateUrl('chorario_fecha',array('id'=>$id)));  
            }
            $horario=$entity->getHorario()->getId();
            $em->remove($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('info', 'Los datos han sido eliminados correctamente.');
        } catch (Exception $e) {
            $this->get('session')->getFlashBag()->add('danger', 'No se pudieron eliminar los datos.');
        }
        return $this->redirect($this->generateUrl('chorario_fecha',array('id'=>$horario)));
    }
    

    public function convertoMysql($fecha_withot_format) {
        //list($year, $month, $day) = $fecha_withot_format;
        $formato = 'Y-m-d';
        $fecha = \DateTime::createFromFormat($formato, $fecha_withot_format);
        return $fecha;
    }

}
