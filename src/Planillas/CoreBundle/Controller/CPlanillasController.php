<?php

namespace Planillas\CoreBundle\Controller;

use Doctrine\ORM\EntityManager;
use Planillas\CoreBundle\Managers\CPlanillasManagers;
use Planillas\PaymentsBundle\Form\Models\PeriodoPlanillaModel;
use Planillas\PaymentsBundle\Form\Type\PeriodoPlanillaType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * CAusencias controller.
 *
 */
class CPlanillasController extends Controller 
{

    /**
     * Muestra los datos para una planilla
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function pagosAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $salarioManager = $this->get('payments.salario.manager');
        $planillaManager = $this->get('payments.planillas.manager');

        $periodoPlanillaModel = new PeriodoPlanillaModel();
        $search_form = $this->createForm(new PeriodoPlanillaType(), $periodoPlanillaModel);

        //Obtener el ultimo periodo de pago hecho como datos para mostrar
        $ultimoPeriodoPago = $planillaManager->getUltimoPeriodoPagado();

        $empleados = array();
        $params = array();

        $search_form->handleRequest($request);
        if ($search_form->isValid()) {
            $manager = $this->get('core.cplanillas.manager');
            $manager->setFechaInicio($periodoPlanillaModel->getFechaInicio());
            $manager->setFechaFin($periodoPlanillaModel->getFechaFin());

            // validar las fechas del período entrado
            $isValidPeriodoPago = $planillaManager
                ->validarPeriodoPago($periodoPlanillaModel->getFechaInicio(), $periodoPlanillaModel->getFechaFin()); //valida intervalo de dias

            if ($isValidPeriodoPago === true) {
                //valida existencia de la planilla en base de datos
                $existePeriodo = $planillaManager
                    ->existePlanillaInPeriodo($periodoPlanillaModel->getFechaInicio(), $periodoPlanillaModel->getFechaFin());

                if ($existePeriodo === false) {
                    $htmlData = $manager->resultHtmlPlanillas();
                    $empleados = $htmlData['empleados'];
                } else {
                    $this->get('session')->getFlashBag()->add('danger', $existePeriodo);
                }
            } else {
                $this->get('session')->getFlashBag()->add('danger', 'El periodo seleccionado es no es válido.');
            }

            if(count($empleados)) {
                $form = $this->createPlanillaForm($periodoPlanillaModel->getFechaInicio(),
                    $periodoPlanillaModel->getFechaFin());
                $params['form'] = $form->createView();
            }
        }

        $params['empleados'] = $empleados;
        $params['ultimoPeriodoPago'] = $ultimoPeriodoPago;
        $params['search_form'] = $search_form->createView();

        return $this->render('PlanillasCoreBundle:CPlanillas:index.html.twig', $params);
    }

    /**
     * Crea el formulario para guardar o exportar la planilla con las fechas registradas
     *
     * @param \DateTime $fecha_inicio
     * @param \DateTime $fecha_fin
     * @return \Symfony\Component\Form\Form
     */
    public function createPlanillaForm(\DateTime $fecha_inicio = null, \DateTime $fecha_fin  = null)
    {
        $data = array();
        if($fecha_inicio && $fecha_fin) {
            $data = array(
                'fecha_inicio'  => $fecha_inicio->format('Y-m-d'),
                'fecha_fin'     => $fecha_fin->format('Y-m-d'),
            );
        }

        $form = $this->createFormBuilder($data);
        $form
            ->add('fecha_inicio','hidden', array())
            ->add('fecha_fin','hidden',array())
            ->add('guardar', 'submit', array(
                'label' => 'Guardar',
                'icon' => 'save',
                'attr' => array(
                    'class' => 'btn btn-primary'
                )
            ))
            ->add('exportar_pdf', 'submit', array(
                'label' => 'Exportar PDF',
                'attr' => array(
                    'class' => 'btn btn-default'
                )
            ))
            ->add('exportar_excel', 'submit', array(
                'label' => 'Exportar Excel',
                'attr' => array(
                    'class' => 'btn btn-default'
                )
            ))
            ->setAction($this->generateUrl('cplanillas_create'))
            ->setMethod('POST');

        return $form->getForm();
    }

    /**
     * Crea el formulario para la vista de detalles de planilla
     *
     * @param $planillaId
     * @return \Symfony\Component\Form\Form
     */
    public function createDetallesPlanillaForm($planillaId)
    {
        $form = $this->createFormBuilder(array(
            'csrf_protection' => false,
        ));
        $form
            ->add('exportar_pdf', 'submit', array(
                'label' => 'Exportar PDF',
                'attr' => array(
                    'class' => 'btn btn-default'
                )
            ))
            ->add('exportar_billetes_pdf', 'submit', array(
                'label' => 'Exportar Billetes PDF',
                'attr' => array(
                    'class' => 'btn btn-default'
                )
            ))
            ->add('exportar_excel', 'submit', array(
                'label' => 'Exportar Excel',
                'attr' => array(
                    'class' => 'btn btn-default'
                )
            ))
            ->add('exportar_billetes_excel', 'submit', array(
                'label' => 'Exportar Billetes Excel',
                'attr' => array(
                    'class' => 'btn btn-default'
                )
            ))
            ->setAction($this->generateUrl('cplanillas_detalles', array('id' => $planillaId)))
            ->setMethod('GET');

        return $form->getForm();
    }

    /**
     * Crea una nueva planilla
     *
     * @param Request $request
     * @return Response | RedirectResponse
     */
    public function createPlanillaAction(Request $request)
    {
        $form = $this->createPlanillaForm();
        $form->handleRequest($request);

        $data = $form->getData();
        $fecha_inicio = date_create_from_format('Y-m-d', $data['fecha_inicio']);
        $fecha_fin = date_create_from_format('Y-m-d', $data['fecha_fin']);

        if ($form->isValid()) {
            if ($form->get('guardar')->isClicked()) { // guardar planilla
                $manager = $this->get('core.cplanillas.manager');
                $manager->setFechaInicio($fecha_inicio);
                $manager->setFechaFin($fecha_fin);

                if ($manager->savePlanilla()) {
                    $this->get('session')->getFlashBag()->add('info', 'La planilla de efectivo ha sido creada correctamente.');

                    return $this->redirect($this->generateUrl('cplanillas_listar'));
                } else {
                    $this->get('session')->getFlashBag()->add('danger', 'Error al guardar la planilla de efectivo.');
                }
            } elseif ($form->get('exportar_pdf')->isClicked()) { // exportar planilla pdf
                $manager = $this->get('core.cplanillas.manager');
                $manager->setFechaInicio($fecha_inicio);
                $manager->setFechaFin($fecha_fin);
                $manager->reportePrePagoPDF();
            } elseif ($form->get('exportar_excel')->isClicked()) { // exportar planilla excel
                $manager = $this->get('core.cplanillas.manager');
                $manager->setFechaInicio($fecha_inicio);
                $manager->setFechaFin($fecha_fin);
                $manager->reportePrePagoExcel();
            } else {
                throw new \Exception('Debe seleccionar una opción válida en el formulario.');
            }
        }

        $this->get('session')->getFlashBag()->add('danger', 'Ha ocurrido un error en la validación del formulario. Por favor vuelva a intentarlo.');

        return $this->redirect($this->generateUrl('cplanillas_listar'));
    }

    private function managePlanillaResult(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $salarioManager = $this->get('payments.salario.manager');
        $planillaManager = $this->get('payments.planillas.manager');

        $periodoPlanillaModel = new PeriodoPlanillaModel();
        $search_form = $this->createForm(new PeriodoPlanillaType(), $periodoPlanillaModel);

        //Obtener el ultimo periodo de pago hecho como datos para mostrar
        $ultimoPeriodoPago = $planillaManager->getUltimoPeriodoPagado();

        $empleados = array();
        $params = array();

        $manager = $this->get('core.cplanillas.manager');
        $manager->setFechaInicio($periodoPlanillaModel->getFechaInicio());
        $manager->setFechaFin($periodoPlanillaModel->getFechaFin());

        // validar las fechas del período entrado
        $isValidPeriodoPago = $planillaManager
            ->validarPeriodoPago($periodoPlanillaModel->getFechaInicio(), $periodoPlanillaModel->getFechaFin()); //valida intervalo de dias

        if ($isValidPeriodoPago === true) {
            //valida existencia de la planilla en base de datos
            $existePeriodo = $planillaManager
                ->existePlanillaInPeriodo($periodoPlanillaModel->getFechaInicio(), $periodoPlanillaModel->getFechaFin());

            if ($existePeriodo === false) {
                $htmlData = $manager->resultHtmlPlanillas();
                $empleados = $htmlData['empleados'];
            } else {
                $this->get('session')->getFlashBag()->add('danger', $existePeriodo);
            }
        } else {
            $this->get('session')->getFlashBag()->add('danger', 'El periodo seleccionado es no es válido.');
        }

        if(count($empleados)) {
            $form = $this->createPlanillaForm($periodoPlanillaModel->getFechaInicio(),
                $periodoPlanillaModel->getFechaFin());
            $params['form'] = $form->createView();
        }

        $params['empleados'] = $empleados;
        $params['ultimoPeriodoPago'] = $ultimoPeriodoPago;
        $params['search_form'] = $search_form->createView();
    }

    /**
     * Acción de buscar planilla
     *
     * @param Request $request
     */
    public function searchPlanillaAction(Request $request)
    {
//        if ($bExistePeriodo == false /*|| is_array($bExistePeriodo)*/) {
//
//            $this->get('session')->getFlashBag()->add('danger', 'Existen coincidencias en las fechas con la planilla del período ' . $bExistePeriodo[1]);
//            $entities = array('id_planilla' => 0);
//            $entities['periodo']['inicio'] = "";
//            $entities['periodo']['fin'] = "";
//            $entities['empleados'] = array();
//        } else {
//            $entities = $manager->resultHtmlPlanillas();
//        }
//
//        return $this->render('PlanillasCoreBundle:CPlanillas:index.html.twig', array(
//            'entities' => $entities,
//            'ultimoPeriodoPago'=>$ultimoPeriodoPago,//CPlanilla
//            'periodo' => $oPeriodoPagoActivo
//        ));
    }

    /**
     * Lista las planillas generadas en el sistema
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listarAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $paymentManager = $this->get('payments.salario.manager');

        $manager = $this->get('core.cplanillas.manager');
        $entities = $manager->getPlanillas();
        $fechaInicio=false;
        $fechaFin=false;
        if(count($entities)==0)
        {
            if($request->getMethod()=="GET")
                $this->get('session')->getFlashBag()->add('info', 'Seleccione el período deseado');
            else
            $this->get('session')->getFlashBag()->add('danger', 'No existen planillas para el período deseado');
        }
        if($manager->getFechaInicio()!=null)
        {
            $fechaInicio=$manager->getFechaInicio()->format('Y-m-d');
            //print_r($fechaInicio);exit;
        }
        if($manager->getFechaFin()!=null)
        {
            $fechaFin=$manager->getFechaFin()->format('Y-m-d');
            //print_r($fechaInicio);exit;
        }
        $page=$this->get('request')->query->get('page', 1);
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $entities, $page, 20
        );

        return $this->render('PlanillasCoreBundle:CPlanillas:planillas.html.twig', array(
                    'entities' => $pagination,
                    'fechaInicio'=>$fechaInicio,
                    'fechaFin'=>$fechaInicio

        ));
    }

    /**
     * Muestra una planilla y sus detalles
     *
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function detallesAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('PlanillasCoreBundle:CPlanillas')->find($id);

        if (!$entity) {
            $this->createNotFoundException(sprintf('No se encuentra la planilla con id: %s', $id));
        }

        $manager = $this->get('core.cplanillas.manager');
        $manager->setIdplanilla($id);
        $manager->setFechaInicio($entity->getFechaInicio());
        $manager->setFechaFin($entity->getFechaFin());

        $form = $this->createDetallesPlanillaForm($id);

        $form->handleRequest($request);
        if ($form->isValid()) {
            if ($form->get('exportar_pdf')->isClicked()) {
                $manager->reportePagoPDF();
            } elseif ($form->get('exportar_excel')->isClicked()) {
                $manager->reportePagoExcel();
            } elseif ($form->get('exportar_billetes_pdf')->isClicked()) {
                $manager->reportePagoBilletesPDF();
            } elseif ($form->get('exportar_billetes_excel')->isClicked()) {
                $manager->reportePagoBilletesExcel();
            } else {
                throw new \Exception('Debe seleccionar una opción válida en el formulario.');
            }
        }

        $entities = $manager->resultHtmlPlanillas();
        $empleados = $entities['empleados'];

        return $this->render('PlanillasCoreBundle:CPlanillas:detalles_planilla.html.twig', array(
            'empleados' => $empleados,
            'form' => $form->createView(),
        ));
    }

    /**
     * Procesa los datos de planilla e imprime reporte pdf
     *
     * @param Request $request
     */
    public function reporteAction(Request $request)
    {
        $manager = $this->get('core.cplanillas.manager');
        $manager->reportePagoPDF();
    }

}
