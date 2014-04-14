<?php

namespace Planillas\PaymentsBundle\Managers;

use Planillas\CoreBundle\Entity\CEmpleado;
use Planillas\CoreBundle\Entity\CPlanillas;
use Planillas\CoreBundle\Entity\CPlanillasEmpleado;
use Planillas\CoreBundle\Entity\CSalarioBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class SalarioManager
{
    /**
     * Días en el mes
     *
     * @var int
     */
    private $dias_por_mes;

    /**
     * Factor Multiplicador del seguro
     *
     * @var float
     */
    private $factor_seguro;

    /**
     * Cantidad de horas de trabajo en turno diurno
     *
     * @var int
     */
    private $horas_diurno;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * @var HorasExtrasManager
     */
    private $horasExtrasManager;

    /**
     * @var  \Symfony\Bridge\Monolog\Logger $logger
     */
    private $logger;

    /**
     *
     */
    public function __construct(ContainerInterface $container)
    {
        $this->em                   = $container->get('doctrine.orm.entity_manager');
        $this->horasExtrasManager   = $container->get('payments.horas_extras.manager');
        $this->dias_por_mes         = $container->getParameter('payments.dias_por_mes');
        $this->factor_seguro        = $container->getParameter('payments.factor_seguro');
        $this->horas_diurno         = $container->getParameter('payments.horas_diurno');
        $this->logger               = $container->get('logger');
    }

    /**
     * Devuelve el salario neto del empleado calculando si seguro
     * @param $empleadoId
     * @return int
     */
    public function getSalarioEmpleado($empleadoId)
    {
        /** @var CSalarioBase $salarioBase */
        $salarioBase = $this->em->getRepository('PlanillasCoreBundle:CSalarioBase')
            ->getSalarioBaseByEmpleado($empleadoId);

        if ($salarioBase == null) {
            return 0;
        }

        $salario = $salarioBase->getSalarioBase();
        if ($salarioBase->getSeguro()) {
            $salarioRebajo = $salario * $this->factor_seguro;

            return $salario - $salarioRebajo;
        } else {
            return $salario;
        }
    }

    /**
     * Calcula el salario por hora dado su salario base
     *
     * @param $salario_base
     * @return float
     */
    public function getSalarioPorHora($salario_base)
    {
        return $this->getSalarioPorDia($salario_base) / $this->horas_diurno;
    }

    /**
     * Calcula el salario diario dado el salario base
     *
     * @param $salario_base
     * @return float
     */
    public function getSalarioPorDia($salario_base)
    {
        return $salario_base / $this->dias_por_mes;
    }

    /**
     * Obtiene dado el id del empleado la cantidad de horas extras
     *
     * @param int|\Planillas\CoreBundle\Entity\CEmpleado $empleado
     * @param  \DateTime $fechaInicio
     * @param  \DateTime $fechaFin
     * @param null|\Planillas\CoreBundle\Entity\CPlanillas $planilla
     * @param  bool $update
     * @param \Planillas\CoreBundle\Entity\CPlanillasEmpleado $planillaEmpleado
     * @throws \Exception
     * @internal param int $oPlanilla
     * @return array
     */
    public function findHorasExtrasByEmpleado(
        CEmpleado $empleado, \DateTime $fechaInicio = null, \DateTime $fechaFin = null, CPlanillas $planilla = null,
        $update = false, CPlanillasEmpleado $planillaEmpleado = null)
    {
        $aSalida = array();
        $aSalida['total'] = 0;

        if ($planilla !== null && is_object($planilla)) {
            $oHorasExtras = $this->horasExtrasManager
                ->getHorasExtrasEmpleadoInPlanilla($empleado->getId(), $planilla->getId());
        } elseif ($fechaInicio !== null && $fechaFin !== null) {
            $oHorasExtras = $this->horasExtrasManager
                ->getHorasExtrasEmpleadoInPeriod($empleado->getId(), $fechaInicio, $fechaFin);
        } else {
            throw new \Exception('Debe especificar los datos de la planilla a buscar o el período que desea para obtener las horas extras del empleado.');
        }

        //return $oHorasExtras;

        if (count($oHorasExtras)) {
            foreach ($oHorasExtras as $oHoraExtra) {
                if ($update === true && $planillaEmpleado) {
                    $planillaEmpleado->addHorasExtra($oHoraExtra);
                    //$oHoraExtra->setPlanilla($planilla);
                    //$this->em->persist($oHoraExtra);
                    //$this->em->flush();
                } else {
                    $salarioNeto = $this->getSalarioEmpleado($empleado->getId());
                    $salarioHora = $this->getSalarioPorHora($salarioNeto);

                    $aSalida['dias_extras'][] = array(
                        'id' => $oHoraExtra->getId(),
                        'fecha' => $oHoraExtra->getFechaHorasExtras()->format('Y-m-d'),
                        'cantidad' => $oHoraExtra->getCantidadHoras(),
                        'monto_total' => $this->horasExtrasManager
                                ->getSalarioHorasExtras($salarioHora, $oHoraExtra->getCantidadHoras()),
                    );

                    $aSalida['total'] += $this->horasExtrasManager
                        ->getSalarioHorasExtras($salarioHora, $oHoraExtra->getCantidadHoras());
                }
            }
        }

        return $aSalida;

    }
}
