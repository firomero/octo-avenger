<?php

namespace Planillas\CoreBundle\Controller;

use Doctrine\ORM\EntityManager;
use Planillas\CoreBundle\Entity\CEmpleadoReferenciaLaboral;
use Planillas\CoreBundle\Entity\CEmpleadoReferenciaPersonal;
use Planillas\CoreBundle\Entity\CEmpleadoReferencias;
use Planillas\CoreBundle\Form\Type\CEmpleadoReferenciaLaboralType;
use Planillas\CoreBundle\Form\Type\CEmpleadoReferenciaPersonalType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

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

        $referenciasLaboral = $em->getRepository('PlanillasCoreBundle:CEmpleadoReferenciaLaboral')
            ->findByEmpleado($empleado);
        $referenciasPersonal = $em->getRepository('PlanillasCoreBundle:CEmpleadoReferenciaPersonal')
            ->findByEmpleado($empleado);

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
        $refpersonal_form = $this->createFormReferenciasPersonal($id_empleado);

        return $this->render('PlanillasCoreBundle:CEmpleadoReferencias:new.html.twig', array(
            'eEmpleado' => $empleado,
            'reflaboral_form' => $reflaboral_form->createView(),
            'refpersonal_form' => $refpersonal_form->createView(),
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

        $form->add('sumbit', 'submit', array(
            'label' => 'Enviar',
            'attr' => array(
                'class' => 'btn btn-primary'
            )
        ));

        return $form;
    }

    private function createFormReferenciasPersonal($id_empleado, $entity=null)
    {
        $form = $this->createForm(new CEmpleadoReferenciaPersonalType(), $entity, array(
            'method' => 'POST',
            'action' => $this->generateUrl('empleado_referencias_create', array('id_empleado' => $id_empleado)),
        ));

        $form->add('sumbit', 'submit', array(
            'label' => 'Enviar',
            'attr' => array(
                'class' => 'btn btn-primary'
            )
        ));

        return $form;
    }

    public function createAction($id_empleado, Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');

        $empleado = $em->find('PlanillasCoreBundle:CEmpleado', $id_empleado);
        if(!$empleado)
            $this->createNotFoundException('No existe empleado con id: '. $id_empleado);

        $reflaboral = new CEmpleadoReferenciaLaboral();
        $reflaboral->setEmpleado($empleado);
        $reflaboral_form = $this->createFormReferenciasLaboral($id_empleado, $reflaboral);

        $refpersonal = new CEmpleadoReferenciaPersonal();
        $refpersonal->setEmpleado($empleado);
        $refpersonal_form = $this->createFormReferenciasPersonal($id_empleado, $refpersonal);

        // referencias laborales
        $reflaboral_form->handleRequest($request);
        if ($reflaboral_form->isSubmitted() && $reflaboral_form->isValid()) {
            if ($this->persistEmpleadoReferencia($reflaboral, $em))
                return $this->redirect($this->generateUrl('empleado_referencias', array('id_empleado' => $id_empleado)));
        }

        // referencias personales
        $refpersonal_form->handleRequest($request);
        if ($refpersonal_form->isSubmitted() && $refpersonal_form->isValid()) {
            if ($this->persistEmpleadoReferencia($refpersonal, $em))
                return $this->redirect($this->generateUrl('empleado_referencias', array('id_empleado' => $id_empleado)));
        }

        $activetab = $reflaboral_form->isSubmitted() ? 'laboral' : 'personal';

        return $this->render('PlanillasCoreBundle:CEmpleadoReferencias:new.html.twig', array(
            'eEmpleado' => $empleado,
            'reflaboral_form' => $reflaboral_form->createView(),
            'refpersonal_form' => $refpersonal_form->createView(),
            'activetab' => $activetab,
        ));
    }

    private function persistEmpleadoReferencia(CEmpleadoReferencias $referencia, EntityManager $em)
    {
        try {
            $em->persist($referencia);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'Se ha adicionado una nueva referencia correctamente.');

            return true;

        } catch (\Exception $e) {
            $this->get('session')
                ->getFlashBag()->add('error', 'Ha ocurrido un error adicionando una nueva referencia.');
            $this->get('logger')
                ->addCritical(sprintf('Ha ocurrido un error adicionando una nueva referencia. Detalles: %s',
                    $e->getMessage()));
            return false;
        }
    }
} 