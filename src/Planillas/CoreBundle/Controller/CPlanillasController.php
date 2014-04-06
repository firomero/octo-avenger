<?php

namespace Planillas\CoreBundle\Controller;

use Planillas\CoreBundle\Managers\CPlanillasManagers;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * CAusencias controller.
 *
 */
class CPlanillasController extends Controller
{
    public function pagosAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $paymentManager = $this->get('planillas_payments.payment.manager');

        $manager = new CPlanillasManagers($em, $request, $paymentManager);
        $idPlanilla = $request->request->get('id');

        $bValidaPeriodoPago = $manager->validarPeriodoPago(); //valida intervalo de dias
        if (isset($idPlanilla) && $idPlanilla>0) {
              $bValidaPeriodoPago=true;
        }
        /*Obtener el ultimo periodo de pago hecho como datos para mostrar*/
        $ultimoPeriodoPago=$manager->getUltimaPlanilla();

        /*Fin obtener ultimo periodo de pago*/
        $oPeriodoPagoActivo = $manager->getPeriodoPagoActivo();
        $bExistePeriodo = $manager->existePeriodPagoenBasedeDatos(); //valida existencia de la planilla en base de datos

        if ($bValidaPeriodoPago === false) { //hay que buscar si el periodo existe ya para que no pueda insertar de nuevo
            if ($request->getMethod()!="GET") {
              $this->get('session')->getFlashBag()->add('danger', 'El período seleccionado es inválido.');
            }
            $entities = array('id_planilla' => 0);
            $entities['periodo']['inicio'] = ($manager->getFechaInicio()!=null) ? $manager->getFechaInicio()->format('Y-m-d'):'';
            $entities['periodo']['fin'] =  ($manager->getFechaFin()!=null) ? $manager->getFechaFin()->format('Y-m-d'):'';
            $entities['empleados'] = array();

            return $this->render('PlanillasCoreBundle:CPlanillas:index.html.twig', array(
                        'entities'          => $entities,
                        'ultimoPeriodoPago' => $ultimoPeriodoPago,//CPlanilla
                        'periodo'           => $oPeriodoPagoActivo //periodo en NPeriodoPago
            ));
        } else {

            $button = $request->request->get('btn-save');
            $bButton = $request->request->get('btn-exportar');
            //print_r(isset($button));exit;
            if (isset($button)) { //esta salvando la planilla en base de datos
                if ($bExistePeriodo === false || is_array($bExistePeriodo)) {

                    $this->get('session')->getFlashBag()->add('danger', 'Existen coincidencias en las fechas con la planilla del período ' . $bExistePeriodo[1]);
                    $entities = $manager->resultHtmlPlanillas();

                    return $this->render('PlanillasCoreBundle:CPlanillas:index.html.twig', array(
                                'entities' => $entities,
                                'ultimoPeriodoPago'=>$ultimoPeriodoPago,//CPlanilla
                                'periodo' => $oPeriodoPagoActivo
                    ));
                }
                if ($manager->savePlanilla()) {
                    $this->get('session')->getFlashBag()->add('info', 'La planilla de efectivo ha sido creada correctamente.');

                    return $this->redirect($this->generateUrl('cplanillas_listar'));
                } else {
                    $this->get('session')->getFlashBag()->add('danger', 'Error al guardar la planilla de efectivo.');

                    return $this->redirect($this->generateUrl('cplanillas_listar'));
                }
            }
            if (isset($bButton)) {//esta generando un pdf
                $manager->reportePagoPDF();
            } else { //esta solo buscando
                if ($bExistePeriodo == false /*|| is_array($bExistePeriodo)*/) {

                    $this->get('session')->getFlashBag()->add('danger', 'Existen coincidencias en las fechas con la planilla del período ' . $bExistePeriodo[1]);
                    $entities = array('id_planilla' => 0);
                    $entities['periodo']['inicio'] = "";
                    $entities['periodo']['fin'] = "";
                    $entities['empleados'] = array();
                } else {
                    $entities = $manager->resultHtmlPlanillas();
                }

                return $this->render('PlanillasCoreBundle:CPlanillas:index.html.twig', array(
                            'entities' => $entities,
                            'ultimoPeriodoPago'=>$ultimoPeriodoPago,//CPlanilla
                            'periodo' => $oPeriodoPagoActivo
                ));
            }
        }
    }

    /**
     * funcion  que lista las planillas existentes
     * @param  Request                                    $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listarAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $paymentManager = $this->get('planillas_payments.payment.manager');

        $manager = new CPlanillasManagers($em, $request, $paymentManager);
        $entities = $manager->getPlanillas();
        $fechaInicio=false;
        $fechaFin=false;
        if (count($entities)==0) {
            if($request->getMethod()=="GET")
                $this->get('session')->getFlashBag()->add('info', 'Seleccione el período deseado');
            else
            $this->get('session')->getFlashBag()->add('danger', 'No existen planillas para el período deseado');
        }
        if ($manager->getFechaInicio()!=null) {
            $fechaInicio=$manager->getFechaInicio()->format('Y-m-d');
            //print_r($fechaInicio);exit;
        }
        if ($manager->getFechaFin()!=null) {
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

    public function detallesAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('PlanillasCoreBundle:CPlanillas')->find($id);
        $paymentManager = $this->get('planillas_payments.payment.manager');

        $manager = new CPlanillasManagers($em, $request, $paymentManager, $id);
        $manager->setFechaInicio($entity->getFechaInicio());
        $manager->setFechaFin($entity->getFechaFin());
        $entities = $manager->resultHtmlPlanillas();
        /* echo "<pre>";
          print_r($entities);
          echo "</pre>";
          exit; */
       /*Obtener el ultimo periodo de pago hecho como datos para mostrar*/
        $ultimoPeriodoPago = $manager->getUltimaPlanilla();

        /*Fin obtener ultimo periodo de pago*/

        return $this->render('PlanillasCoreBundle:CPlanillas:index.html.twig', array(
                    'entities' => $entities,
                    'ultimoPeriodoPago'=>$ultimoPeriodoPago
        ));
    }

    public function reporteAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $paymentManager = $this->get('planillas_payments.payment.manager');

        $manager = new CPlanillasManagers($em, $request, $paymentManager);
        $manager->reportePagoPDF();
    }

}
