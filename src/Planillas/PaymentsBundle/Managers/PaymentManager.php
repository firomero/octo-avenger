<?php
/**
 * Created by PhpStorm.
 * User: cinfante
 * Date: 05/04/14
 * Time: 07:17 AM
 */

namespace Planillas\PaymentsBundle\Managers;


use Doctrine\ORM\EntityManager;
use Planillas\CoreBundle\Entity\CSalarioBase;
use Symfony\Component\DependencyInjection\Container;

class PaymentManager
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * @var string
     */
    private $daysInMonth;

    /**
     * @var string
     */
    private $insurance;

    function __construct(Container $container)
    {
        $this->em = $container->get('doctrine.orm.entity_manager');
        $this->daysInMonth = $container->getParameter('payments.days_in_month');
        $this->insurance = $container->getParameter('payments.insurance');
    }

    /**
     * Devuelve el salario del empleado con
     * @param $empleadoId
     * @return int
     */
    public function getSalarioEmpleado($empleadoId)
    {
        /** @var CSalarioBase $salarioBase */
        $salarioBase = $this->em->getRepository('PlanillasCoreBundle:CSalarioBase')
            ->getSalarioBaseByEmpleado($empleadoId);

        if($salarioBase == null)
            return 0;

        $salario = $salarioBase->getSalarioBase();
        if ($salarioBase->getSeguro()) {
            $salarioRebajo = $salario * $this->insurance;
            return $salario - $salarioRebajo;
        } else {
            return $salario;
        }
    }

} 