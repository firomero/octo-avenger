<?php

namespace Planillas\PaymentsBundle\Managers;

use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Monolog\Logger;

/**
 * Class HorasExtrasManager
 * @package Planillas\PaymentsBundle\Managers
 */
class HorasExtrasManager
{
    /**
     * @var $indice_horas_extras int
     */
    private $indice_horas_extras;

    /**
     * @var $em \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * @var \Symfony\Bridge\Monolog\Logger $logger
     */
    private $logger;

    /**
     * @param EntityManager $em
     * @param $indice_horas_extras
     * @param \Symfony\Bridge\Monolog\Logger $logger
     * @internal param \Planillas\PaymentsBundle\Managers\PaymentManager $paymentManager
     */
    public function __construct(EntityManager $em, $indice_horas_extras, Logger $logger)
    {
        $this->em = $em;
        $this->indice_horas_extras = $indice_horas_extras;
        $this->logger = $logger;
    }

    /**
     * Devuelve el plus por hora equivalente al salario por hora entrado por parámetros
     *
     * @param $salario_hora
     * @return mixed
     */
    public function getExtraPorHoraExtra($salario_hora)
    {
        return $salario_hora * $this->indice_horas_extras;
    }

    /**
     * Devuelve el plus equivalente al salario por hora entrado por parámetros y la cantidad de horas extras
     *
     * @param $salario_hora
     * @param $horas
     *
     * @return int
     */
    public function getSalarioHorasExtras($salario_hora, $horas)
    {
        return $this->getExtraPorHoraExtra($salario_hora) * $horas;
    }

    /**
     * Obtiene las horas extras para un empleado en el período
     *
     * @param $idEmpleado
     * @param  \DateTime $fechaInicio
     * @param  \DateTime $fechaFin
     * @return array
     */
    public function getHorasExtrasEmpleadoInPeriod($idEmpleado, \DateTime $fechaInicio, \DateTime $fechaFin)
    {
        $oHorasExtras = $this->em->getRepository('PlanillasCoreBundle:CHorasExtras')
            ->getEmpleadoHorasExtrasEnPeriodo($idEmpleado, $fechaInicio, $fechaFin);

        return $oHorasExtras;
    }

    /**
     * Obtiene las horas extras registradas para un empleado en la planilla pasada por parámetros
     *
     * @param $idEmpleado
     * @param $idPlanilla
     * @return array|\Planillas\CoreBundle\Entity\CHorasExtras[]
     */
    public function getHorasExtrasEmpleadoInPlanilla($idEmpleado, $idPlanilla)
    {
        //$horasExtras = $this->em->getRepository('PlanillasCoreBundle:CHorasExtras')->findBy(array(
        //    'empleado' => $idEmpleado,
        //    'planilla' => $idPlanilla
        //));
        $query = $this->em->createQueryBuilder()
            ->select('h')
            ->from('PlanillasCoreBundle:CHorasExtras','h')
            ->innerJoin('h.planillaEmpleado', 'pe')
            ->innerJoin('pe.planilla', 'p')
            ->where('p = :planilla AND h.empleado = :empleado')
            ->setParameters(array(
                'planilla' => $idPlanilla,
                'empleado' => $idEmpleado,
            ))
            ->getQuery();

        return $query->getResult();
    }
}
