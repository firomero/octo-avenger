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
        $manager= new CPlanillasManagers($em,$request);
        $bValidaPeriodoPago=$manager->validarPeriodoPago();
        if($bValidaPeriodoPago===false)
        {
            $this->get('session')->getFlashBag()->add('danger', 'El periodo de seleccionado es invalido.');
            $entities=array();
        }
        else
        $entities=$manager->resultHtmlPlanillas("hola","mundo");
        //echo "<pre>";print_r($entities);echo "</pre>";exit;
        return $this->render('PlanillasCoreBundle:CPlanillas:index.html.twig', array(
            'entities' => $entities,

        ));
    }


}
