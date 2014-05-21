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
            throw $this->createNotFoundException('No existe empleado con id: '. $id_empleado);

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
            throw $this->createNotFoundException('No existe empleado con id: '. $id_empleado);

        $reflaboral_form = $this->createFormReferenciasLaboral($id_empleado);
        $refpersonal_form = $this->createFormReferenciasPersonal($id_empleado);

        return $this->render('PlanillasCoreBundle:CEmpleadoReferencias:new.html.twig', array(
            'eEmpleado' => $empleado,
            'reflaboral_form' => $reflaboral_form->createView(),
            'refpersonal_form' => $refpersonal_form->createView(),
        ));
    }

    public function createAction($id_empleado, Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');

        $empleado = $em->find('PlanillasCoreBundle:CEmpleado', $id_empleado);
        if(!$empleado)
            throw $this->createNotFoundException('No existe empleado con id: '. $id_empleado);

        $reflaboral = new CEmpleadoReferenciaLaboral();
        $reflaboral->setEmpleado($empleado);
        $reflaboral_form = $this->createFormReferenciasLaboral($id_empleado, $reflaboral);

        $refpersonal = new CEmpleadoReferenciaPersonal();
        $refpersonal->setEmpleado($empleado);
        $refpersonal_form = $this->createFormReferenciasPersonal($id_empleado, $refpersonal);

        // referencias laborales
        $reflaboral_form->handleRequest($request);
        if ($reflaboral_form->isSubmitted() && $reflaboral_form->isValid()) {
            if ($this->persistEmpleadoReferencia($reflaboral, $em)) {
                return $this->redirect($this->generateUrl('empleado_referencias', array('id_empleado' => $id_empleado)));
            }
        }

        // referencias personales
        $refpersonal_form->handleRequest($request);
        if ($refpersonal_form->isSubmitted() && $refpersonal_form->isValid()) {
            if ($this->persistEmpleadoReferencia($refpersonal, $em)) {
                return $this->redirect($this->generateUrl('empleado_referencias', array('id_empleado' => $id_empleado)));
            }
        }

        $activetab = $reflaboral_form->isSubmitted() ? 'laboral' : 'personal';

        return $this->render('PlanillasCoreBundle:CEmpleadoReferencias:new.html.twig', array(
            'eEmpleado' => $empleado,
            'reflaboral_form' => $reflaboral_form->createView(),
            'refpersonal_form' => $refpersonal_form->createView(),
            'activetab' => $activetab,
        ));
    }

    public function editAction($id_empleado, $id)
    {
        $em = $this->get('doctrine.orm.entity_manager');

        $empleado = $em->find('PlanillasCoreBundle:CEmpleado', $id_empleado);
        if(!$empleado)
            throw $this->createNotFoundException('No existe empleado con id: '. $id_empleado);

        $referencia = $em->getRepository('PlanillasCoreBundle:CEmpleadoReferencias')->find($id);
        if(!$referencia)
            throw $this->createNotFoundException('No existe referencia con id: '. $id);

        if ($referencia instanceof CEmpleadoReferenciaLaboral) {
            $form = $this->createFormReferenciasLaboral($id_empleado, $referencia, true);
            $type = 'laboral';
        } else {
            $form = $this->createFormReferenciasPersonal($id_empleado, $referencia, true);
            $type = 'personal';
        }

        return $this->render('PlanillasCoreBundle:CEmpleadoReferencias:edit.html.twig', array(
            'form' => $form->createView(),
            'type' => $type,
            'eEmpleado' => $empleado,
        ));
    }

    public function updateAction($id_empleado, $id, Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');

        $empleado = $em->find('PlanillasCoreBundle:CEmpleado', $id_empleado);
        if(!$empleado)
            throw $this->createNotFoundException('No existe empleado con id: '. $id_empleado);

        $referencia = $em->getRepository('PlanillasCoreBundle:CEmpleadoReferencias')->find($id);
        if(!$referencia)
            throw $this->createNotFoundException('No existe referencia con id: '. $id);

        if ($referencia instanceof CEmpleadoReferenciaLaboral) {
            $form = $this->createFormReferenciasLaboral($id_empleado, $referencia, true);
            $type = 'laboral';

            // referencias laborales
            $form->handleRequest($request);
            if ($form->isValid()) {
                if ($this->updateEmpleadoReferencia($referencia, $em)) {
                    return $this->redirect($this->generateUrl('empleado_referencias', array('id_empleado' => $id_empleado)));
                }
            }
        } else {
            $form = $this->createFormReferenciasPersonal($id_empleado, $referencia, true);
            $type = 'personal';

            // referencias personales
            $form->handleRequest($request);
            if ($form->isValid()) {
                if ($this->updateEmpleadoReferencia($referencia, $em)) {
                    return $this->redirect($this->generateUrl('empleado_referencias', array('id_empleado' => $id_empleado)));
                }
            }
        }

        return $this->render('PlanillasCoreBundle:CEmpleadoReferencias:edit.html.twig', array(
            'form' => $form->createView(),
            'type' => $type,
            'eEmpleado' => $empleado,
        ));
    }

    public function showAction($id_empleado, $id)
    {
        $em = $this->get('doctrine.orm.entity_manager');

        $empleado = $em->find('PlanillasCoreBundle:CEmpleado', $id_empleado);
        if(!$empleado)
            throw $this->createNotFoundException('No existe empleado con id: '. $id_empleado);

        $referencia = $em->getRepository('PlanillasCoreBundle:CEmpleadoReferencias')->find($id);
        if(!$referencia)
            throw $this->createNotFoundException('No existe referencia con id: '. $id);

        if ($referencia instanceof CEmpleadoReferenciaLaboral) {
            $type = 'laboral';
        } else {
            $type = 'personal';
        }

        $delete_form = $this->createDeleteFormReferencias($id_empleado, $id, true);

        return $this->render('PlanillasCoreBundle:CEmpleadoReferencias:show.html.twig', array(
            'referencia' => $referencia,
            'eEmpleado' => $empleado,
            'delete_form' => $delete_form->createView(),
            'type' => $type,
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

            if ($em->getRepository('PlanillasCoreBundle:CEmpleadoReferencias')->deleteReferenciaById($data['id'])) {
                $this->get('session')->getFlashBag()->add('success', 'Se ha eliminado la referencia de forma satisfactoria.');
            } else {
                $this->get('session')->getFlashBag()->add('error', 'Ha ocurrido un error intentando eliminar la referencia.');
            }
        }

        return $this->redirect($this->generateUrl('empleado_referencias', array('id_empleado' => $id_empleado)));
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
            ->setAction($this->generateUrl('empleado_referencias_delete', array('id_empleado' => $id_empleado)))
            ->getForm();

        return $form;
    }

    /**
     * @param $id_empleado
     * @param null $entity
     * @return \Symfony\Component\Form\Form
     */
    private function createFormReferenciasLaboral($id_empleado, CEmpleadoReferencias $entity=null, $edit=false)
    {
        if ($edit) {
            $data = array(
                'method' => 'PUT',
                'action' => $this->generateUrl('empleado_referencias_update', array(
                        'id_empleado' => $id_empleado,
                        'id' => $entity->getId(),
                    )),
            );
        } else {
            $data = array(
                'method' => 'POST',
                'action' => $this->generateUrl('empleado_referencias_create', array('id_empleado' => $id_empleado)),
            );
        }

        $form = $this->createForm(new CEmpleadoReferenciaLaboralType(), $entity, $data);

        $form->add('sumbit', 'submit', array(
            'label' => $edit ? 'Actualizar': 'Enviar',
            'attr' => array(
                'class' => 'btn btn-primary'
            )
        ));

        return $form;
    }

    private function createFormReferenciasPersonal($id_empleado, CEmpleadoReferencias $entity=null, $edit=false)
    {
        if ($edit) {
            $data = array(
                'method' => 'PUT',
                'action' => $this->generateUrl('empleado_referencias_update', array(
                        'id_empleado' => $id_empleado,
                        'id' => $entity->getId(),
                    )),
            );
        } else {
            $data = array(
                'method' => 'POST',
                'action' => $this->generateUrl('empleado_referencias_create', array('id_empleado' => $id_empleado)),
            );
        }

        $form = $this->createForm(new CEmpleadoReferenciaPersonalType(), $entity, $data);

        $form->add('sumbit', 'submit', array(
            'label' => $edit ? 'Actualizar': 'Enviar',
            'attr' => array(
                'class' => 'btn btn-primary'
            )
        ));

        return $form;
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

    private function updateEmpleadoReferencia(CEmpleadoReferencias $referencia, EntityManager $em)
    {
        try {
            $em->persist($referencia);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'Se han actualizado los datos de la referencia correctamente.');

            return true;

        } catch (\Exception $e) {
            $this->get('session')
                ->getFlashBag()->add('error', 'Ha ocurrido un error actualizando referencia.');
            $this->get('logger')
                ->addCritical(sprintf('Ha ocurrido un error actualizando referencia. Detalles: %s',
                    $e->getMessage()));
            return false;
        }
    }
} 