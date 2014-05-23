<?php

namespace Planillas\CoreBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Planillas\CoreBundle\Entity\CEmpleadoRegistroLaboral;
use Planillas\CoreBundle\Form\Type\CEmpleadoRegistroLaboralType;

/**
 * CEmpleadoRegistroLaboral controller.
 *
 */
class CEmpleadoRegistroLaboralController extends Controller
{

    /**
     * Lists all CEmpleadoRegistroLaboral entities.
     *
     */
    public function indexAction($id_empleado)
    {
        $em = $this->get('doctrine.orm.entity_manager');

        $eEmpleado = $em->getRepository('PlanillasCoreBundle:CEmpleado')->find($id_empleado);
        if(!$eEmpleado)
            throw $this->createNotFoundException('No existe empleado con id: '. $id_empleado);

        $entities = $em->getRepository('PlanillasCoreBundle:CEmpleadoRegistroLaboral')->findBy(array('empleado' => $eEmpleado));

        return $this->render('PlanillasCoreBundle:CEmpleadoRegistroLaboral:index.html.twig', array(
            'entities' => $entities,
            'eEmpleado' => $eEmpleado,
        ));
    }

    /**
    * Creates a form to create a CEmpleadoRegistroLaboral entity.
    *
    * @param CEmpleadoRegistroLaboral $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(CEmpleadoRegistroLaboral $entity, $id_empleado)
    {
        $form = $this->createForm(new CEmpleadoRegistroLaboralType(), $entity, array(
            'action' => $this->generateUrl('empleado_registrolaboral_new', array('id_empleado' => $id_empleado)),
            'method' => 'POST',
        ));

        return $form;
    }

    /**
     * Displays a form to create a new CEmpleadoRegistroLaboral entity.
     *
     */
    public function newAction($id_empleado, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $eEmpleado = $em->getRepository('PlanillasCoreBundle:CEmpleado')->find($id_empleado);
        if(!$eEmpleado)
            throw $this->createNotFoundException('No existe empleado con id: '. $id_empleado);

        $entity = new CEmpleadoRegistroLaboral();
        $form   = $this->createForm(new CEmpleadoRegistroLaboralType(), $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('cancelar')->isClicked()) {
                return $this->redirect($this->generateUrl('empleado_registrolaboral', array(
                    'id_empleado' => $id_empleado,
                )));
            }
            try {
                $entity->setEmpleado($eEmpleado);

                $em->persist($entity);
                $em->flush();

                $this->get('session')->getFlashBag()->add('success', 'Se ha adicionado el archivo satisfactoriamente.');

                return $this->redirect($this->generateUrl('empleado_registrolaboral', array('id_empleado' => $id_empleado)));
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

        return $this->render('PlanillasCoreBundle:CEmpleadoRegistroLaboral:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'eEmpleado' => $eEmpleado,
        ));
    }

    /**
     * Deletes a CEmpleadoRegistroLaboral entity.
     *
     */
    public function deleteAction($id_empleado, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $eEmpleado = $em->getRepository('PlanillasCoreBundle:CEmpleado')->find($id_empleado);
        if(!$eEmpleado)
            throw $this->createNotFoundException('No existe empleado con id: '. $id_empleado);

        $form = $this->createDeleteForm($id_empleado);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();
            $entity = $em->getRepository('PlanillasCoreBundle:CEmpleadoRegistroLaboral')->find($data['id']);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find CEmpleadoRegistroLaboral entity.');
            }

            try {
                $em->remove($entity);
                $em->flush();

                $this->get('session')->getFlashBag()->add('success', 'Se ha eliminado el archivo de forma satisfactoria.');
            } catch (\Exception $e) {
                $this->get('session')
                    ->getFlashBag()->add('error', 'Ha ocurrido un error intentando eliminar el registro laboral.');
                $this->get('logger')
                    ->addCritical(
                        sprintf('Ha ocurrido un error intentando eliminar el registro laboral en empleado. Detalles: %s',
                            $e->getMessage())
                    );
            }
        }

        return $this->redirect($this->generateUrl('empleado_registrolaboral', array('id_empleado' => $id_empleado)));
    }

    /**
     * Creates a form to delete a CEmpleadoRegistroLaboral entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id_empleado, $id=null, $csrf=false)
    {
        $form = $this->get('form.factory')->createNamedBuilder('delete_form', 'form', array('id' => $id), array(
            'csrf_protection' => $csrf,
        ))
            ->add('id','hidden',array())
            ->setMethod('DELETE')
            ->setAction($this->generateUrl('empleado_registrolaboral', array('id_empleado' => $id_empleado)))
            ->getForm();

        return $form;
    }
}
