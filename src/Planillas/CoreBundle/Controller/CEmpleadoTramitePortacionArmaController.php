<?php

namespace Planillas\CoreBundle\Controller;

use Planillas\CoreBundle\Entity\CEmpleadoTramitePortacionArma;
use Planillas\CoreBundle\Form\Type\CEmpleadoTramitePortacionArmaType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CEmpleadoTramitePortacionArmaController extends Controller
{
    public function indexAction($id_empleado)
    {
        $em = $this->get('doctrine.orm.entity_manager');

        $eEmpleado = $em->getRepository('PlanillasCoreBundle:CEmpleado')->find($id_empleado);
        if(!$eEmpleado)
            throw $this->createNotFoundException('No existe empleado con id: '. $id_empleado);

        $tramitesportacion = $em->getRepository('PlanillasCoreBundle:CEmpleadoTramitePortacionArma')->findBy(array('empleado' => $eEmpleado));

        return $this->render('PlanillasCoreBundle:CEmpleadoTramitePortacionArma:index.html.twig',array(
            'tramitesportacion' => $tramitesportacion,
            'eEmpleado' => $eEmpleado,
        ));
    }

    public function newAction($id_empleado, Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');

        $eEmpleado = $em->getRepository('PlanillasCoreBundle:CEmpleado')->find($id_empleado);
        if(!$eEmpleado)
            throw $this->createNotFoundException('No existe empleado con id: '. $id_empleado);

        $entity = new CEmpleadoTramitePortacionArma();
        $form = $this->createForm(new CEmpleadoTramitePortacionArmaType(), $entity, array(
            'method' => 'POST',
            'action' => $this->generateUrl('empleado_tramite_portacion_arma_new', array('id_empleado' => $id_empleado)),
        ));

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            if ($form->get('cancelar')->isClicked()) {
                return $this->redirect($this->generateUrl('empleado_tramite_portacion_arma', array(
                    'id_empleado' => $id_empleado,
                )));
            }

            try {
                $entity->setEmpleado($eEmpleado);

                $em->persist($entity);
                $em->flush();

                $this->get('session')->getFlashBag()->add('success', 'Se ha creado un nuevo trámite satisfactoriamente.');

                return $this->redirect($this->generateUrl('empleado_tramite_portacion_arma', array(
                    'id_empleado' => $id_empleado,
                )));
            } catch (\Exception $e) {
                $this->get('session')
                    ->getFlashBag()->add('error', 'Ha ocurrido un error inesperado persistiendo un nuevo trámite de portación de armas.');
                $this->get('logger')
                    ->addCritical(
                        sprintf('Ha ocurrido un error inesperado persistiendo un nuevo trámite de portación de armas. Detalles: %s',
                            $e->getMessage())
                    );
            }
        }

        return $this->render('PlanillasCoreBundle:CEmpleadoTramitePortacionArma:new.html.twig', array(
            'form' => $form->createView(),
            'eEmpleado' => $eEmpleado,
        ));
    }

    public function editAction()
    {
    }

    public function deleteAction($id_empleado, Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');

        $empleado = $em->find('PlanillasCoreBundle:CEmpleado', $id_empleado);
        if(!$empleado)
            throw $this->createNotFoundException('No existe empleado con id: '. $id_empleado);

        $form = $this->createDeleteForm($id_empleado);

        $form->handleRequest($request);
        if($form->isValid()) {
            $data = $form->getData();

            $tramiteportacion = $em->getRepository('PlanillasCoreBundle:CEmpleadoTramitePortacionArma')->find($data['id']);
            if ($tramiteportacion) {
                try {
                    $em->remove($tramiteportacion);
                    $em->flush();

                    $this->get('session')->getFlashBag()->add('success', 'Se ha eliminado el trámite de forma satisfactoria.');
                } catch (\Exception $e) {
                    $this->get('session')
                        ->getFlashBag()->add('error', 'Ha ocurrido un error intentando eliminar un trámite de portación de armas.');
                    $this->get('logger')
                        ->addCritical(
                            sprintf('Ha ocurrido un error intentando eliminar un trámite de portación de armas en empleado. Detalles: %s',
                                $e->getMessage())
                        );
                }
            }
        }

        return $this->redirect($this->generateUrl('empleado_tramite_portacion_arma', array('id_empleado' => $id_empleado)));
    }


    /**
     * @param $id_empleado
     * @param null $id
     * @param bool $csrf
     * @return \Symfony\Component\Form\Form
     */
    private function createDeleteForm($id_empleado, $id=null, $csrf=false)
    {
        $form = $this->get('form.factory')->createNamedBuilder('delete_form', 'form', array('id' => $id), array(
            'csrf_protection' => $csrf,
        ))
            ->add('id','hidden',array())
            ->setMethod('DELETE')
            ->setAction($this->generateUrl('empleado_tramite_portacion_arma_delete', array('id_empleado' => $id_empleado)))
            ->getForm();

        return $form;
    }

}
