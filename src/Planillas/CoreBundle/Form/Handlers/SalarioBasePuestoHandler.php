<?php

namespace Planillas\CoreBundle\Form\Handlers;

use Doctrine\ORM\EntityManager;
use Planillas\CoreBundle\Entity\CEmpleado;
use Planillas\CoreBundle\Entity\CPuestoEmpleado;
use Planillas\CoreBundle\Form\Model\SalarioBasePuesto;
use Planillas\CoreBundle\Form\Type\SalarioBasePuestoType;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
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

        $model = new SalarioBasePuesto();

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
                    }

                    $this->em->persist($puesto);
                } else {
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
    public function createNewForm($id_empleado)
    {
        $form = $this->formFactory->create(new SalarioBasePuestoType(), null, array(
            'action' => $this->router->generate('csalariobase_create', array('id_empleado' => $id_empleado)),
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
        $form = $this->formFactory->create(new SalarioBasePuestoType(true), $entity, array(
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
