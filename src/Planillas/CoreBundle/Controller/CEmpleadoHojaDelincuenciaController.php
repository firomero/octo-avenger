<?php

namespace Planillas\CoreBundle\Controller;

use Planillas\CoreBundle\Entity\CEmpleadoHojaDelincuencia;
use Planillas\CoreBundle\Form\Type\CEmpleadoHojaDelincuenciaType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CEmpleadoHojaDelincuenciaController extends Controller
{
    public function indexAction($id_empleado)
    {
        $em = $this->get('doctrine.orm.entity_manager');

        $eEmpleado = $em->getRepository('PlanillasCoreBundle:CEmpleado')->find($id_empleado);
        if(!$eEmpleado)
            throw $this->createNotFoundException('No existe empleado con id: '. $id_empleado);

        $bddigital = $em->getRepository('PlanillasCoreBundle:CEmpleadoHojaDelincuencia')->findAll();

        return $this->render('PlanillasCoreBundle:CEmpleadoHojaDelincuencia:index.html.twig',array(
            'hojasdelincuencia' => $bddigital,
            'eEmpleado' => $eEmpleado,
        ));
    }

    public function newAction($id_empleado, Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');

        $eEmpleado = $em->getRepository('PlanillasCoreBundle:CEmpleado')->find($id_empleado);
        if(!$eEmpleado)
            throw $this->createNotFoundException('No existe empleado con id: '. $id_empleado);

        $entity = new CEmpleadoHojaDelincuencia();
        $form = $this->createForm(new CEmpleadoHojaDelincuenciaType(), $entity, array(
            'method' => 'POST',
            'action' => $this->generateUrl('empleado_hojadelincuencia_new', array('id_empleado' => $id_empleado)),
        ));

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            if ($form->get('cancelar')->isClicked()) {
                return $this->redirect($this->generateUrl('empleado_hojadelincuencia', array(
                    'id_empleado' => $id_empleado,
                )));
            }

            try {
                $em->persist($entity);
                $em->flush();

                $this->get('session')->getFlashBag()->add('success', 'Se ha adicionado el archivo satisfactoriamente.');

                return $this->redirect($this->generateUrl('empleado_hojadelincuencia', array(
                    'id_empleado' => $id_empleado,
                )));
            } catch (\Exception $e) {
                $this->get('session')
                    ->getFlashBag()->add('error', 'Ha ocurrido un error inesperado persistiendo el archivo.');
                $this->get('logger')
                    ->addCritical(
                        sprintf('Ha ocurrido un error inesperado persistiendo el archivo. Detalles: %s',
                            $e->getMessage())
                    );
            }
        }

        return $this->render('PlanillasCoreBundle:CEmpleadoHojaDelincuencia:new.html.twig', array(
            'form' => $form->createView(),
            'eEmpleado' => $eEmpleado,
        ));
    }

    public function deleteAction($id_empleado, Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');

        $empleado = $em->find('PlanillasCoreBundle:CEmpleado', $id_empleado);
        if(!$empleado)
            throw $this->createNotFoundException('No existe empleado con id: '. $id_empleado);

        $form = $this->createDeleteFormReferencias($id_empleado);

        $form->handleRequest($request);
        if($form->isValid()) {
            $data = $form->getData();

            $archivo_bddigital = $em->getRepository('PlanillasCoreBundle:CEmpleadoHojaDelincuencia')->find($data['id']);
            if ($archivo_bddigital) {
                try {
                    $em->remove($archivo_bddigital);
                    $em->flush();

                    $this->get('session')->getFlashBag()->add('success', 'Se ha eliminado el archivo de forma satisfactoria.');
                } catch (\Exception $e) {
                    $this->get('session')
                        ->getFlashBag()->add('error', 'Ha ocurrido un error intentando eliminar la referencia.');
                    $this->get('logger')
                        ->addCritical(
                            sprintf('Ha ocurrido un error intentando eliminar la referencia. Detalles: %s',
                                $e->getMessage())
                        );
                }
            }
        }

        return $this->redirect($this->generateUrl('empleado_hojadelincuencia', array('id_empleado' => $id_empleado)));
    }


    /**
     * @param $id_empleado
     * @param null $id
     * @param bool $csrf
     * @return \Symfony\Component\Form\Form
     */
    private function createDeleteFormReferencias($id_empleado, $id=null, $csrf=false)
    {
        $form = $this->get('form.factory')->createNamedBuilder('delete_form', 'form', array('id' => $id), array(
            'csrf_protection' => $csrf,
        ))
            ->add('id','hidden',array())
            ->setMethod('DELETE')
            ->setAction($this->generateUrl('empleado_hojadelincuencia_delete', array('id_empleado' => $id_empleado)))
            ->getForm();

        return $form;
    }
}
