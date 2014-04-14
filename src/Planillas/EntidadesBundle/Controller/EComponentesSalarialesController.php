<?php

namespace Planillas\EntidadesBundle\Controller;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Planillas\EntidadesBundle\Entity\EComponentesSalariales;
use Planillas\EntidadesBundle\Form\EComponentesSalarialesType;

/**
 * EComponentesSalariales controller.
 *
 */
class EComponentesSalarialesController extends Controller
{
    /**
     * Lists all EComponentesSalariales entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('PlanillasEntidadesBundle:EComponentesSalariales')->findAll();

        return $this->render('PlanillasEntidadesBundle:EComponentesSalariales:index.html.twig', array(
                    'entities' => $entities,
        ));
    }

    public function componentesByIdEmpleadoAction($id_empleado)
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('PlanillasEntidadesBundle:EComponentesSalariales')
            ->findBy(array('empleado' => $id_empleado,  'planillaEmpleado' => null, 'deleted_at' => null));

        $aDeleteForm = array();
        foreach ($entities as $entity) {
            $aDeleteForm[$entity->getId()] = $this->createDeleteForm($entity->getId())->createView();
        }

        return $this->render('PlanillasEntidadesBundle:EComponentesSalariales:componentes.html.twig', array(
                    'entities' => $entities,
                    'aDeleteForm' => $aDeleteForm,
        ));
    }

    public function salarioTotalByIdEmpleadoAction($id_empleado)
    {
        $em = $this->getDoctrine()->getManager();
        $eEmpleado = $em->getRepository('PlanillasCoreBundle:CEmpleado')->find($id_empleado);
        if (!$eEmpleado) {
            throw $this->createNotFoundException('Unable to find CEmpleado entity.');
        }
        $salarioBase = ($eEmpleado->getSalarioBase() != null) ? $eEmpleado->getSalarioBase()->getSalarioBase() : 0;

        $componentesSalariales = $eEmpleado->getComponentesSalariales();
        if (count($componentesSalariales) > 0) {
            foreach ($componentesSalariales as $componenteSalarial) {

                if ($componenteSalarial->getComponente() == 0) {
                    $salarioBase -= $componenteSalarial->getMontoTotal();
                } else
                    $salarioBase += $componenteSalarial->getCantidad();
            }
        }

        return $this->render('PlanillasEntidadesBundle:EComponentesSalariales:salariototal.html.twig', array(
                    'salarioTotal' => $salarioBase,
        ));
    }

    /**
     * Creates a new EComponentesSalariales entity.
     *
     */
    public function createAction(Request $request, $id_empleado)
    {
        $em = $this->getDoctrine()->getManager();

        $eEmpleado = $em->getRepository('PlanillasCoreBundle:CEmpleado')->find($id_empleado);
        if (!$eEmpleado) {
            throw $this->createNotFoundException('Unable to find CEmpleado entity.');
        }

        $entity = new EComponentesSalariales();
        $entity->setEmpleado($eEmpleado);
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $result = self::validate($entity);

            if ($result === true && self::persistEntity($entity, $em, true)) { //the form contains errors
                $this->get('session')->getFlashBag()->add('info', 'Los datos han sido adicionados correctamente.');

                return $this->redirect($this->generateUrl('salariobase_new', array('id_empleado' => $entity->getEmpleado()->getId())));
            }
        }

        $this->get('session')->getFlashBag()->add('danger', 'Se detectaron errores al guardar los datos.');

        return $this->render('PlanillasEntidadesBundle:EComponentesSalariales:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
                    'eEmpleado' => $eEmpleado
        ));
    }

    /**
     * Creates a form to create a EComponentesSalariales entity.
     *
     * @param EComponentesSalariales $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(EComponentesSalariales $entity)
    {
        $form = $this->createForm(new EComponentesSalarialesType(), $entity, array(
            'action' => $this->generateUrl('ecomponentessalariales_create', array('id_empleado' => $entity->getEmpleado()->getId())),
            'method' => 'POST',
            'attr' => array('class' => "form-horizontal", 'id' => 'componente_salarial')
        ));

        //$form->add('submit', 'submit', array('label' => 'Agregar','attr'=>array('class'=>"btn btn-primary")));
        return $form;
    }

    /**
     * Displays a form to create a new EComponentesSalariales entity.
     *
     */
    public function newAction($id_empleado)
    {
        $em = $this->getDoctrine()->getManager();
        $eEmpleado = $em->getRepository('PlanillasCoreBundle:CEmpleado')->find((int) $id_empleado);
        if (!$eEmpleado) {
            throw $this->createNotFoundException('Unable to find CEmpleado entity.');
        }
        $entity = new EComponentesSalariales();
        $entity->setEmpleado($eEmpleado);
        $form = $this->createCreateForm($entity);
        //print_r($entity->getEmpleado());exit;
        return $this->render('PlanillasEntidadesBundle:EComponentesSalariales:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
                    'eEmpleado' => $eEmpleado
        ));
    }

    /**
     * Finds and displays a EComponentesSalariales entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PlanillasEntidadesBundle:EComponentesSalariales')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find EComponentesSalariales entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('PlanillasEntidadesBundle:EComponentesSalariales:show.html.twig', array(
                    'entity' => $entity,
                    'delete_form' => $deleteForm->createView(),));
    }

    /**
     * Displays a form to edit an existing EComponentesSalariales entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PlanillasEntidadesBundle:EComponentesSalariales')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find EComponentesSalariales entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('PlanillasEntidadesBundle:EComponentesSalariales:edit.html.twig', array(
                    'entity' => $entity,
                    'form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a EComponentesSalariales entity.
     *
     * @param EComponentesSalariales $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(EComponentesSalariales $entity)
    {
        $form = $this->createForm(new EComponentesSalarialesType(true), $entity, array(
            'action' => $this->generateUrl('ecomponentessalariales_update', array('id' => $entity->getId())),
            'method' => 'POST',
            'attr' => array('id' => 'editcomponente', 'class' => "form-horizontal")
        ));

        //$form->add('submit', 'submit', array('label' => 'Actualizar','attr'=>array('class'=>'btn btn-success')));
        //$form->add('button', 'button', array('label' => 'Volver','attr'=>array('class'=>'btn btn-success')));
        return $form;
    }

    /**
     * Edits an existing EComponentesSalariales entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PlanillasEntidadesBundle:EComponentesSalariales')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find EComponentesSalariales entity.');
        }
        /* Validando que no se haya pagado antes */
        //$planillaComponente=$em->getRepository('PlanillasCoreBundle:CPlanillasComponentesPermanentes')->findOneBy(array())
        if ($entity->getPlanilla() != null || $entity->getPlanilla() != "") {
            $this->get('session')->getFlashBag()->add('danger', 'No se pueden actualizar datos asociados a una planilla de pago.');

            return $this->redirect($this->generateUrl('salariobase_new', array('id_empleado' => $entity->getEmpleado()->getId())));
        }
        $planillaComponente = $em->getRepository('PlanillasCoreBundle:CPlanillasComponentesPermanentes')->findOneBy(array('componentePermanente' => $entity->getId()));
        if ($planillaComponente) {
            $this->get('session')->getFlashBag()->add('danger', 'No se pueden actualizar datos asociados a una planilla de pago.');

            return $this->redirect($this->generateUrl('salariobase_new', array('id_empleado' => $entity->getEmpleado()->getId())));
        }
        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            /* EComponentesSalarialesType $obj= new EComponentesSalarialesType();
              $obj->getName() */
            $result = self::validate($entity);

            if ($result === true) { //the form contains errors
                $entity = self::persistEntity($entity, $em);

                $this->get('session')->getFlashBag()->add('info', 'Los datos han sido modificados correctamente.');

                return $this->redirect($this->generateUrl('salariobase_new', array('id_empleado' => $entity->getEmpleado()->getId())));
            }
        }
        $this->get('session')->getFlashBag()->add('danger', 'Se detectaron errores al guardar los datos.');

        return $this->render('PlanillasEntidadesBundle:EComponentesSalariales:edit.html.twig', array(
                    'entity' => $entity,
                    'form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a EComponentesSalariales entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('PlanillasEntidadesBundle:EComponentesSalariales')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find EComponentesSalariales entity.');
        }
        $eEmpleado = $entity->getEmpleado();

        //if ($entity->getPermanente() == true) {//si  es permanente no se elimina se pone delete_at con la fecha
            $entity->setDeletedAt(new \DateTime(date('Y-m-d', time())));
            $em->persist($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('info', 'Se ha eliminado la entidad correctamente.');
        //} else {
            /*//if ($entity->getPlanilla() == null) {
                $entity->setDeletedAt()
                //$em->remove($entity);
                $em->flush();
                $this->get('session')->getFlashBag()->add('info', 'Se ha eliminado la entidad correctamente.');
            } else {
                $this->get('session')->getFlashBag()->add('info', 'No se puede eliminar una componente asociada a un planilla de pago.');
            }*/
        //}
        return $this->redirect($this->generateUrl('salariobase_new', array('id_empleado' => $eEmpleado->getId())));
    }

    /**
     * Creates a form to delete a EComponentesSalariales entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        //echo $id;exit;
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('ecomponentessalariales_delete', array('id' => $id)))
                        ->setMethod('DELETE')
                        ->add('submit', 'submit', array('label' => 'Borrar', 'attr' => array('class' => 'btn btn-primary')))
                        ->getForm();
    }

    public static function validate(EComponentesSalariales $entity)
    {
        if ($entity->getComponente() == 0) { //rebajo
            if ((int) $entity->getMontoTotal() <= 0 || $entity->getMontoTotal() == "") {
                return "invalidmontotal";
            }
            if ($entity->getNumeroCuotas() == "" && $entity->getPermanente() == false) {
                return "invalidmontotal";
            }

            return true;
        } else {

            if ((int) $entity->getCantidad() <= 0 || $entity->getCantidad() == "") {
                return "invalidcantidad";
            }
            if ($entity->getFechaVencimiento() == "" && $entity->getPermanente() == false) {
                return "invalidfechavencimiento";
            }

            return true;
        }
    }

    /** funcion que persiste una entidad componente salarial porque
     * hay  que limpiar algunos datos
     * @param  EComponentesSalariales      $entity
     * @param  EntityManager               $manager
     * @return bool|EComponentesSalariales
     */
    public static function persistEntity(EComponentesSalariales $entity, EntityManager $manager, $isnew = false)
    {
        try {

            if ($entity->getComponente() == 0) { //rebajos
                if ($isnew === true) {//si es nuevo porque es el unico caso donde es necesario pikar en trozos los plazos
                    // datos relacionados con las fechas
                    $i = 1;
                    $iCantDias = self::getPeriodoPagoConfig($manager);
                    if ($iCantDias < 1)
                        throw new \Exception("La cantidad de dias del periodo activo no puede ser cero");

                    if ($entity->getPermanente() == true) {
                        $myentity = new EComponentesSalariales();
                        $myentity->setEmpleado($entity->getEmpleado());
                        $myentity->setComponente($entity->getComponente());
                        $myentity->setTipoDeuda($entity->getTipoDeuda());
                        $myentity->setMontoTotal($entity->getMontoTotal());
                        $myentity->setNumeroCuotas(0);
                        $myentity->setCantidad(null);
                        $myentity->setPagado($entity->getPagado());
                        $myentity->setPermanente(true);
                        $myentity->setFechaInicio(null);
                        $myentity->setFechaVencimiento(null);
                        //$inicio_formated = date('Y-m-d', strtotime($inicio_formated . ' + ' . $iCantDias . ' days'));

                        $myentity->setMontoRestante(0);
                        $manager->persist($myentity);
                    } else {
                        $fechaInicio = $entity->getFechaInicio();
                        $inicio_formated = date_format($fechaInicio, 'Y-m-d');
                        $total = 0;
                        $total = $entity->getMontoTotal() / $entity->getNumeroCuotas(); //monto para cada plazo
                        $resto = $entity->getMontoTotal() % $entity->getNumeroCuotas(); //resto a sumar aun plazo
                        while ($i <= $entity->getNumeroCuotas()) {

                            if ($i == $entity->getNumeroCuotas()) {
                                $total+=$resto;
                            }
                            $myentity = new EComponentesSalariales();
                            $myentity->setEmpleado($entity->getEmpleado());
                            $myentity->setComponente($entity->getComponente());
                            $myentity->setTipoDeuda($entity->getTipoDeuda());
                            $myentity->setMontoTotal($total);
                            $myentity->setNumeroCuotas(1);
                            $myentity->setCantidad(null);
                            $myentity->setPermanente(false);
                            $myentity->setPagado($entity->getPagado());

                            $myentity->setFechaInicio(new \DateTime($inicio_formated));
                            $myentity->setFechaVencimiento(new \DateTime(date('Y-m-d', strtotime($inicio_formated . ' + ' . $iCantDias . ' days'))));
                            $inicio_formated = date('Y-m-d', strtotime($inicio_formated . ' + ' . $iCantDias . ' days'));

                            $myentity->setMontoRestante($total);
                            $manager->persist($myentity);
                            $i++;
                        }
                    }

                    $manager->flush();

                    return $myentity;
                } else {//es solo modificar una sola
                    if ($entity->getPermanente()) {
                        $entity->setFechaInicio(null);
                        $entity->setFechaVencimiento(null);
                        $entity->setNumeroCuotas(0);
                        $entity->setPermanente(true);
                    }
                    $entity->setCantidad(null);
                    $manager->persist($entity);
                    $manager->flush($entity);
                    //$entity->setFechaVencimiento(null);
                    //$entity->setMontoRestante($entity->getMontoTotal());
                }
            } else { //bonificacion
                if ($entity->getPermanente()) {
                    $entity->setPermanente(true);
                    $entity->setFechaVencimiento(null);
                }

                $entity->setFechaInicio(null);
                $entity->setMoneda(0);
                $entity->setMontoReducir(null);
                $entity->setPeriodoPagoDeuda(null);
                $entity->setMontoTotal(null);
                $entity->setMontoRestante(null);
                $entity->setPagado(0);
                $manager->persist($entity);
                $manager->flush($entity);
            }

            return $entity;
        } catch (\Exception $e) {
            return false;
        }
    }

    public static function getPeriodoPagoConfig(EntityManager $manager)
    {
        $periodo_activo = $manager->getRepository('PlanillasNomencladorBundle:NPeriodoPago')->findOneBy(array('activo' => true));
        if (!$periodo_activo) {
            throw new \Exception('Unable to find EComponentesSalariales entity.');
        }

        return $periodo_activo->getCantDias();
    }

}
