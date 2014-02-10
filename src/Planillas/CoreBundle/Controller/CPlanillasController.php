<?php

namespace Planillas\CoreBundle\Controller;

use Doctrine\ORM\EntityManager;
use Planillas\CoreBundle\Managers\CPlanillasManagers;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * CAusencias controller.
 *
 */
class CPlanillasController extends Controller {

    public function pagosAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $manager = new CPlanillasManagers($em, $request);
        $idPlanilla = $request->request->get('id');
        
        $bValidaPeriodoPago = $manager->validarPeriodoPago(); //valida intervalo de dias
        if (isset($idPlanilla) && $idPlanilla>0) {
              $bValidaPeriodoPago=true;          
        }
        $oPeriodoPagoActivo = $manager->getPeriodoPagoActivo();
        $bExistePeriodo = $manager->existePeriodPagoenBasedeDatos(); //valida existencia de la planilla en base de datos

        if ($bValidaPeriodoPago === false) { //hay que buscar si el periodo existe ya para que no pueda insertar de nuevo
            $this->get('session')->getFlashBag()->add('danger', 'El periodo de seleccionado es invalido.');
            //$entities = $manager->resultHtmlPlanillas();
            $entities = array('id_planilla' => 0);
            $entities['periodo']['inicio'] = "";
            $entities['periodo']['fin'] = "";
            $entities['empleados'] = array();
            return $this->render('PlanillasCoreBundle:CPlanillas:index.html.twig', array(
                        'entities' => $entities,
                        'periodo' => $oPeriodoPagoActivo
            ));
        } else {

            $button = $request->request->get('btn-save');
            $bButton = $request->request->get('btn-exportar');
            //print_r(isset($button));exit;
            if (isset($button)) { //esta salvando la planilla en base de datos
                if ($bExistePeriodo === false || is_array($bExistePeriodo)) {

                    $this->get('session')->getFlashBag()->add('danger', 'Existen coincidencias en las fechas con la planilla del periodo ' . $bExistePeriodo[1]);
                    $entities = $manager->resultHtmlPlanillas();

                    return $this->render('PlanillasCoreBundle:CPlanillas:index.html.twig', array(
                                'entities' => $entities,
                                'periodo' => $oPeriodoPagoActivo
                    ));
                }
                if ($manager->savePlanilla()) {
                    $this->get('session')->getFlashBag()->add('info', 'La planilla ha sido creada correctamente.');
                    return $this->redirect($this->generateUrl('cplanillas_listar'));
                } else {
                    $this->get('session')->getFlashBag()->add('danger', 'Error al guardar la planilla de pago.');
                    return $this->redirect($this->generateUrl('cplanillas_listar'));
                }
            }
            if (isset($bButton)) {//esta generando un pdf
                $manager->reportePagoPDF();
            } else { //esta solo buscando
                if ($bExistePeriodo == false /*|| is_array($bExistePeriodo)*/) {
                    
                    $this->get('session')->getFlashBag()->add('danger', 'Existen coincidencias en las fechas con la planilla del periodo ' . $bExistePeriodo[1]);
                    $entities = array('id_planilla' => 0);
                    $entities['periodo']['inicio'] = "";
                    $entities['periodo']['fin'] = "";
                    $entities['empleados'] = array();
                } else {
                    $entities = $manager->resultHtmlPlanillas();
                }
                return $this->render('PlanillasCoreBundle:CPlanillas:index.html.twig', array(
                            'entities' => $entities,
                            'periodo' => $oPeriodoPagoActivo
                ));
            }
        }
    }

    /**
     * funcion  que lista las planillas existentes
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listarAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $manager = new CPlanillasManagers($em, $request);
        $entities = $manager->getPlanillas();
        return $this->render('PlanillasCoreBundle:CPlanillas:planillas.html.twig', array(
                    'entities' => $entities,
        ));
    }

    public function detallesAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        //echo $id;exit;
        $entity = $em->getRepository('PlanillasCoreBundle:CPlanillas')->find($id);

        $manager = new CPlanillasManagers($em, $request, $id);
        $manager->setFechaInicio($entity->getFechaInicio());
        $manager->setFechaFin($entity->getFechaFin());
        $entities = $manager->resultHtmlPlanillas();
        /* echo "<pre>";
          print_r($entities);
          echo "</pre>";
          exit; */
        return $this->render('PlanillasCoreBundle:CPlanillas:index.html.twig', array(
                    'entities' => $entities,
        ));
    }

    public function reporteAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $manager = new CPlanillasManagers($em, $request);
        $manager->reportePagoPDF();
    }

}
