<?php

namespace Planillas\CoreBundle\Form\Handlers;

use Doctrine\ORM\EntityManager;
use Planillas\CoreBundle\Entity\CEmpleado;
use Planillas\CoreBundle\Entity\CPuestoEmpleado;
use Planillas\CoreBundle\Entity\CSalarioBase;
use Planillas\CoreBundle\Form\Models\SalarioBasePuesto;
use Planillas\CoreBundle\Form\Type\SalarioBasePuestoType;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\RouterInterface;

class SalarioBasePuestoHandler
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var Logger
     */
    private $logger;

    private $form;

    public function __construct($em, $formFactory, $router, $logger)
    {
        $this->em = $em;
        $this->formFactory = $formFactory;
        $this->router = $router;
        $this->logger = $logger;
    }

    public function handle_update(Request $request, $id)
    {
        $entity = $this->em->getRepository('PlanillasCoreBundle:CEmpleado')->find($id);

        if (!$entity) {
            throw new NotFoundHttpException('Unable to find CEmpleado entity');
        }

        $form = $this->createEditForm($entity);

        $form->handleRequest($request);
        if ($form->isValid()) {
            try {
                $model = $form->getData();

                $salarioBase = $entity->getSalarioBase();
                $this->em->persist($salarioBase);
                $salarioBase->setSeguro($model->getSeguro());
                $salarioBase->setSalarioBase($model->getSalarioBase());
                //$salarioBase->setPeriodoPago()

                if ($model->getPuesto() !== null) {
                    $puesto = $entity->getPuesto();
                    if ($puesto === null) {
                        $puesto = new CPuestoEmpleado($entity);
                    }

                    if ($model->getPuesto() !== null) {
                        $puesto->setEmpresa($model->getEmpresa());
                        $puesto->setCliente($model->getCliente());
                        $puesto->setSucursal($model->getSucursal());
                        $puesto->setTurno($model->getTurno());
                        $puesto->setPuesto($model->getPuesto());
                        $puesto->setRol($model->getRol());
                        // asignando el horario establecido para el puesto al empleado
                        $entity->setHorario($model->getRol()->getRol());
                    }

                    $this->em->persist($puesto);
                } else {
                    // se elimina la asociación del puesto con el trabajador
                    $puesto = $entity->getPuesto();
                    if($puesto !== null)
                        $this->em->remove($puesto);
                }

                $this->em->flush();

                return true;
            } catch (\Exception $e) {
                $this->logger->addCritical($e->getMessage());

                return false;
            }
        }

        $this->form = $form;

        return false;
    }

    public function handle_create(Request $request, $id)
    {
        $entity = $this->em->getRepository('PlanillasCoreBundle:CEmpleado')->find($id);

        if (!$entity) {
            throw new NotFoundHttpException('Unable to find CEmpleado entity');
        }

        $form = $this->createNewForm($entity);

        $form->handleRequest($request);
        if ($form->isValid()) {
            try {
                $model = $form->getData();

                $salarioBase = $entity->getSalarioBase();
                if($salarioBase == null) {
                    $salarioBase = new CSalarioBase();
                    $salarioBase->setEmpleado($entity);
                }

                $this->em->persist($salarioBase);
                $salarioBase->setSeguro($model->getSeguro());
                $salarioBase->setSalarioBase($model->getSalarioBase());
                //$salarioBase->setPeriodoPago()

                if ($model->getPuesto() !== null) {
                    $puesto = $entity->getPuesto();
                    if ($puesto === null) {
                        $puesto = new CPuestoEmpleado($entity);
                    }

                    if ($model->getPuesto() !== null) {
                        $puesto->setEmpresa($model->getEmpresa());
                        $puesto->setCliente($model->getCliente());
                        $puesto->setSucursal($model->getSucursal());
                        $puesto->setTurno($model->getTurno());
                        $puesto->setPuesto($model->getPuesto());
                        $puesto->setRol($model->getRol());
                        // asignando el horario establecido para el puesto al empleado
                        $entity->setHorario($model->getRol()->getRol());
                    }

                    $this->em->persist($puesto);
                } else {
                    // se elimina la asociación del puesto con el trabajador
                    $puesto = $entity->getPuesto();
                    if($puesto !== null)
                        $this->em->remove($puesto);
                }

                $this->em->flush();

                return true;
            } catch (\Exception $e) {
                $this->logger->addCritical($e->getMessage());

                return false;
            }
        }

        $this->form = $form;

        return false;
    }

    /**
     * @param $id_empleado
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createNewForm(CEmpleado $empleado)
    {
        $entity = new SalarioBasePuesto($empleado);
        $form = $this->formFactory->create(new SalarioBasePuestoType(), $entity, array(
            'action' => $this->router->generate('csalariobase_create', array('id' => $empleado->getId())),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array(
            'label' => 'Guardar',
            'attr' => array(
                'class' => 'btn btn-primary'
            )));

        return $form;
    }

    /**
     * @param  CEmpleado                             $empleado
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createEditForm(CEmpleado $empleado)
    {
        $entity = new SalarioBasePuesto($empleado);
        $form = $this->formFactory->create(new SalarioBasePuestoType(), $entity, array(
            'action' => $this->router->generate('csalariobase_update', array('id' => $empleado->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array(
            'label' => 'Actualizar',
            'attr' => array(
                'class' => 'btn btn-primary'
            )));

        return $form;
    }

    /**
     * @param mixed $form
     */
    public function setForm($form)
    {
        $this->form = $form;
    }

    /**
     * @return mixed
     */
    public function getForm()
    {
        return $this->form;
    }
}
