<?php

namespace Planillas\EntidadesBundle\Controller;

use Planillas\EntidadesBundle\Entity\ECuentaBanco;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Planillas\CoreBundle\Entity\CEmpleado;
use Planillas\NomencladorBundle\Entity\NBanco;
use Planillas\EntidadesBundle\Form\Type\NBancoType;

/**
 * ECuentaBanco controller.
 *
 */
class ECuentaBancoController extends Controller
{
    /**
     * Lists all ECuentaBanco entities.
     *
     */
    public function indexAction($id_empleado)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var \Planillas\CoreBundle\Entity\CEmpleado $eEmpleado */
        $eEmpleado = $em->getRepository('PlanillasCoreBundle:CEmpleado')->find($id_empleado);

        $entities = $eEmpleado->getCuentasBancos();

        //$entities = $em->createQuery('Select f from PlanillasEntidadesBundle:ECursos f where f.empleado='.$id_empleado);
        //$entities=$entities->getResult();
        $aDeleteForm = array();
        foreach ($entities as $entity) {
            $aDeleteForm[$entity->getId()] = $this->createDeleteForm($entity->getId(), $eEmpleado->getId())->createView();
        }

        return $this->render('PlanillasEntidadesBundle:ECuentaBanco:index.html.twig', array(
                    'entities' => $entities,
                    'eEmpleado' => $eEmpleado,
                    'aDeleteForm' => $aDeleteForm,
                ));
    }

    /**
     * Creates a new ECuentaBanco entity.
     *
     */
    public function createAction(Request $request, $id_empleado)
    {
        $em = $this->getDoctrine()->getManager();
        $eEmpleado = $em->getRepository('PlanillasCoreBundle:CEmpleado')->find($id_empleado);

        if (!$eEmpleado) {
            throw $this->createNotFoundException('Unable to find CEmpleado entity.');
        }

        $entity = new ECuentaBanco();
        $entity->setEmpleado($eEmpleado);

        $form = $this->createNewForm($entity);
        $form->handleRequest($request);

        if($form->get('cancel')->isClicked())
            return $this->redirect($this->generateUrl('cuentasbancos', array('id_empleado' => $eEmpleado->getId())));

        if ($form->isValid()) {
            $em->persist($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'Se han creado los datos para la cuenta del empleado de forma satisfactoria.');

            return $this->redirect($this->generateUrl('cuentasbancos', array('id_empleado' => $eEmpleado->getId())));
        }

        return $this->render('PlanillasEntidadesBundle:ECuentaBanco:new.html.twig', array(
                    'entity' => $eEmpleado,
                    'form' => $form->createView(),
                ));
    }

    /**
     * Creates a form to create a ECursos entity.
     *
     * @param ECursos $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createNewForm(ECuentaBanco $entity)
    {
        //$form = $this->createForm(new NBancoType(), $entity, array(
        //    'action' => $this->generateUrl('cuentasbancos_create', array('id_empleado' => $oEmpleado->getId())),
        //    'method' => 'POST',
        //        ));

        $form = $this->createFormBuilder($entity, array(
            'action' => $this->generateUrl('cuentasbancos_create', array('id_empleado' => $entity->getEmpleado()->getId())),
            'method' => 'POST',
        ))
            ->add('tipo','choice', array(
                'choices' => array(
                    'efectivo'  => 'Efectivo',
                    'banco'     => 'Banco',
                )
            ))
            ->add('banco', null, array(
                'required' => false,
            ))
            ->add('nrocuenta', 'text', array(
                'required' => false,
            ))

            ->getForm();

        $form->add('submit', 'submit', array(
            'label' => 'Crear',
            'icon' => 'save',
            'attr'=>array(
                'class'=>'btn btn-primary'
            )))
            ->add('cancel', 'submit', array(
                'label' => 'Cancelar',
                'icon' => 'remove',
                'validation_groups' => false,
                'attr'=>array(
                    'class'=>'btn btn-default'
                )
            ))
        ;

        return $form;
    }

    /**
     * Displays a form to create a new ECursos entity.
     *
     */
    public function newAction($id_empleado)
    {
        $em = $this->getDoctrine()->getManager();
        $eEmpleado = $em->getRepository('PlanillasCoreBundle:CEmpleado')->find($id_empleado);

        $entity = new ECuentaBanco();
        $entity->setEmpleado($eEmpleado);
        $form = $this->createNewForm($entity);

        return $this->render('PlanillasEntidadesBundle:ECuentaBanco:new.html.twig', array(
                    'entity' => $eEmpleado,
                    'form' => $form->createView(),
                ));
    }

    /**
     * Finds and displays a ECursos entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PlanillasEntidadesBundle:ECursos')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ECursos entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('PlanillasEntidadesBundle:ECursos:show.html.twig', array(
                    'entity' => $entity,
                    'delete_form' => $deleteForm->createView(),));
    }

    /**
     * Displays a form to edit an existing ECursos entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PlanillasCoreBundle:CEmpleado')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CEmpleado entity.');
        }

        $editForm = $this->createEditForm($entity);
        //$deleteForm = $this->createDeleteForm($id);
        return $this->render('PlanillasEntidadesBundle:ECuentaBanco:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                        //'delete_form' => $deleteForm->createView(),
                ));
    }

    /**
     * Creates a form to edit a ECursos entity.
     *
     * @param ECursos $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(CEmpleado $entity)
    {
        $form = $this->createForm(new NBancoType(), $entity, array(
            'action' => $this->generateUrl('cuentasbancos_update', array('id' => $entity->getId())),
            'method' => 'post',
                ));

        //$form->add('submit', 'submit', array('label' => 'Actualizar', 'attr'=>array('class'=>'btn btn-success')));
        return $form;
    }

    /**
     * Edits an existing ECuentas entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('PlanillasCoreBundle:CEmpleado')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CEmpleado entity.');
        }
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('cuentasbancos', array('id_empleado' => $entity->getId())));
        }

        return $this->render('PlanillasEntidadesBundle:ECuentaBanco:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                        //'delete_form' => $deleteForm->createView(),
                ));
    }

    /**
     * Deletes a ECursos entity.
     *
     */
    public function deleteAction(Request $request, $id_cuenta_banco, $id_empleado)
    {
        $form = $this->createDeleteForm($id_cuenta_banco, $id_empleado);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $eCuentaBanco = $em->getRepository('PlanillasEntidadesBundle:ECuentaBanco')->find($id_cuenta_banco);
            /** @var  \Planillas\CoreBundle\Entity\CEmpleado $eEmpleado */
            $eEmpleado = $em->getRepository('PlanillasCoreBundle:CEmpleado')->find($id_empleado);

            if (!$eCuentaBanco || !$eEmpleado) {
                throw $this->createNotFoundException('Unable to find CEmpleado or ECuentaBanco entity!!');
            }

            $eEmpleado->removeCuentasBanco($eCuentaBanco);
            $em->remove($eCuentaBanco);
            //$em->persist($eEmpleado);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('cuentasbancos', array('id_empleado' => $eEmpleado->getId())));
    }

    /**
     * Creates a form to delete a ECursos entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id_cuenta_banco, $id_empleado)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('cuentasbancos_delete', array(
                'id_cuenta_banco' => $id_cuenta_banco,
                'id_empleado' => $id_empleado
            )))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array(
                'label' => 'Eliminar',
                'icon' => 'trash',
                'attr' => array(
                    'class' => 'btn btn-default'
                )))
            ->getForm()
        ;
    }

}
