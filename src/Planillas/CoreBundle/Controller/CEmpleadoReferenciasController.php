<?php

namespace Planillas\CoreBundle\Controller;

use Planillas\CoreBundle\Form\Type\CEmpleadoReferenciaLaboralType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class CEmpleadoReferenciasController
 * @package Planillas\CoreBundle\Controller
 */
class CEmpleadoReferenciasController extends Controller
{
    public function indexAction($id_empleado)
    {
        $em = $this->get('doctrine.orm.entity_manager');

        $empleado = $em->find('PlanillasCoreBundle:CEmpleado', $id_empleado);
        if(!$empleado)
            $this->createNotFoundException('No existe empleado con id: '. $id_empleado);

        $referenciasLaboral = $em->getRepository('PlanillasCoreBundle:CEmpleadoReferenciaLaboral')->findAll();
        $referenciasPersonal = $em->getRepository('PlanillasCoreBundle:CEmpleadoReferenciaPersonal')->findAll();

        $referencias = array_merge($referenciasLaboral, $referenciasPersonal);

        return $this->render('PlanillasCoreBundle:CEmpleadoReferencias:index.html.twig', array(
            'referencias' => $referencias,
            'eEmpleado' => $empleado,
        ));
    }

    public function newAction($id_empleado)
    {
        $em = $this->get('doctrine.orm.entity_manager');

        $empleado = $em->find('PlanillasCoreBundle:CEmpleado', $id_empleado);
        if(!$empleado)
            $this->createNotFoundException('No existe empleado con id: '. $id_empleado);

        $reflaboral_form = $this->createFormReferenciasLaboral($id_empleado);

        return $this->render('PlanillasCoreBundle:CEmpleadoReferencias:new.html.twig', array(
            'eEmpleado' => $empleado,
            'reflaboral_form' => $reflaboral_form->createView(),
        ));
    }

    /**
     * @param $id_empleado
     * @param null $entity
     * @return \Symfony\Component\Form\Form
     */
    private function createFormReferenciasLaboral($id_empleado, $entity=null)
    {
        $form = $this->createForm(new CEmpleadoReferenciaLaboralType(), $entity, array(
            'method' => 'POST',
            'action' => $this->generateUrl('empleado_referencias_create', array('id_empleado' => $id_empleado)),
        ));

        return $form;
    }

    public function createAction()
    {

    }
} 