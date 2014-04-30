<?php

namespace Planillas\PaymentsBundle\Managers;


use Planillas\CoreBundle\Entity\CEmpleado;
use Planillas\CoreBundle\Entity\CPlanillas;
use Planillas\CoreBundle\Entity\CPlanillasEmpleado;
use Symfony\Bridge\Monolog\Logger;

class PlanillaEmpleadoManager
{
    /**
     * @var  \Symfony\Bridge\Monolog\Logger $logger
     */
    private $logger;

    /**
     * @param Logger $logger
     */
    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param CEmpleado $empleado
     * @param CPlanillas $planillas
     * @param $salario_neto
     * @param $salario_total
     * @return CPlanillasEmpleado
     */
    public function createPlanillaEmpleado(CEmpleado $empleado, CPlanillas $planillas, $salario_neto, $salario_total)
    {
        $empleadoplanilla = new CPlanillasEmpleado();

        $empleadoplanilla->setEmpleado($empleado);
        $empleadoplanilla->setPlanilla($planillas);
        $empleadoplanilla->setSalarioPeriodo($salario_neto);
        $empleadoplanilla->setSalarioTotal($salario_total);

        return $empleadoplanilla;
    }
} 