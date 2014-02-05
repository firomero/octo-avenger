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
class CPlanillasController extends Controller
{


    public function pagosAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $manager = new CPlanillasManagers($em, $request);
        
       /* if (($manager->getFechaInicio() == null && $manager->getFechaFin() == null) && $request->getMethod() == "post") {


            $entities = $manager->getPlanillas();
            return $this->render('PlanillasCoreBundle:CPlanillas:planillas.html.twig', array(
                'entities' => $entities,
            ));
        }*/
        $bValidaPeriodoPago = $manager->validarPeriodoPago();

        if ($bValidaPeriodoPago === false) //hay que buscar si el periodo existe ya para que no pueda insertar de nuevo
        {

            $this->get('session')->getFlashBag()->add('danger', 'El periodo de seleccionado es invalido.');
            $entities = $manager->resultHtmlPlanillas();
          
            return $this->render('PlanillasCoreBundle:CPlanillas:index.html.twig', array(
                'entities' => $entities,
            ));
        } else {
           
            $button = $request->request->get('btn-save');

            if (isset($button)) //esta salvando la planilla en base de datos
            {
                if ($manager->existePeriodPagoenBasedeDatos() === false) {
                    
                    $this->get('session')->getFlashBag()->add('danger', 'El periodo de seleccionado es invalido.');
                    $entities = $manager->resultHtmlPlanillas();

                    return $this->render('PlanillasCoreBundle:CPlanillas:index.html.twig', array(
                        'entities' => $entities,
                    ));
                }
                if ($manager->savePlanilla()) {
                    //echo "holaaa";exit;
                    $this->get('session')->getFlashBag()->add('info', 'La planilla ha sido creada correctamente.');
                    return $this->redirect($this->generateUrl('cplanillas_listar'));


                } else {
                    $this->get('session')->getFlashBag()->add('danger', 'Error al guardar la planilla de pago.');
                    return $this->redirect($this->generateUrl('cplanillas_listar'));
                }


            } else { //esta solo buscando
              
                //echo "holaaa";exit;
                $entities = $manager->resultHtmlPlanillas();
                //echo "<pre>";print_r($entities);echo "</pre>";exit;
                return $this->render('PlanillasCoreBundle:CPlanillas:index.html.twig', array(
                    'entities' => $entities,
                ));
            }
        }


    }
    

    /**
     * funcion  que lista las planillas existentes
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function  listarAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $manager = new CPlanillasManagers($em, $request);
        $entities = $manager->getPlanillas();
        return $this->render('PlanillasCoreBundle:CPlanillas:planillas.html.twig', array(
            'entities' => $entities,
        ));
    }

    public function detallesAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        //echo $id;exit;
        $entity = $em->getRepository('PlanillasCoreBundle:CPlanillas')->find($id);

        $manager = new CPlanillasManagers($em, $request);
        $manager->setFechaInicio($entity->getFechaInicio());
        $manager->setFechaFin($entity->getFechaFin());
        $entities = $manager->resultHtmlPlanillas();
        return $this->render('PlanillasCoreBundle:CPlanillas:index.html.twig', array(
            'entities' => $entities,
        ));

    }


}
