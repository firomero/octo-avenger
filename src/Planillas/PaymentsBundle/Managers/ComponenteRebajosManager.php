<?php

namespace Planillas\PaymentsBundle\Managers;


use Planillas\EntidadesBundle\Entity\EComponentesSalariales;
use Symfony\Bridge\Monolog\Logger;
use Doctrine\ORM\EntityManager;

class ComponenteRebajosManager
{
    /**
     * @var $em \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * @var $logger \Symfony\Bridge\Monolog\Logger
     */
    private $logger;

    /**
     * @var $periodoPago \Planillas\NomencladorBundle\Entity\NPeriodoPago
     */
    private $periodoPago;

    public function __construct(EntityManager $em, Logger $logger)
    {
        $this->em = $em;
        $this->logger = $logger;
        $this->periodoPago = $this->em->getRepository('PlanillasNomencladorBundle:NPeriodoPago')
            ->findOneBy(array(
                'activo' => true,
            ));
    }

    /**
     * @param $data
     * @throws \Exception
     */
    public function createRebajos($data)
    {
        /** @var $lastPlanilla \Planillas\CoreBundle\Entity\CPlanillas */
        $lastPlanilla = $this->em->getRepository('PlanillasCoreBundle:CPlanillas')
            ->getLastPlanilla();

        $lastDatesPeriodoPago = array();
        if ($lastPlanilla) {
            $lastDatesPeriodoPago = array(
                'start' => $lastPlanilla->getFechaInicio(),
                'end'   => $lastPlanilla->getFechaFin(),
            );
        }

        //check if single or multiple
        if (isset($data['numeroCuotas']) && $data['numeroCuotas']) {

            if (!$lastPlanilla)
                throw new \Exception('No es posible generar rebajos divididos por cuotas. Debe crearse al menos una'.
                    'planilla para poder estimar los periodos de pago.');

            $numeroCuotas       = $data['numeroCuotas'];
            $montoForInstance   = $data['montoTotal'] / $numeroCuotas;
            $montoResto         = $data['montoTotal'] % $numeroCuotas;

            $dataClone = $data;
            $dataClone['montoTotal'] = $montoForInstance;
            $lastDate = $lastDatesPeriodoPago['end'];
            $fechaInicio = $dataClone['fechaInicio'];
            while ($numeroCuotas > 0) {
                $datesPeriodoPago = $this->selectPeriodoPagoForDate($fechaInicio, $lastDate);

                $dataClone['fechaInicio'] = $datesPeriodoPago['start']->modify('+ 1 day');
                $lastDate = $datesPeriodoPago['end'];
                $fechaInicio = clone($datesPeriodoPago['end']);
                $fechaInicio = $fechaInicio->modify('+ 2 days');

                if($numeroCuotas == 1)
                    $dataClone['montoTotal'] += $montoResto;

                $this->createInstance($dataClone);
                $numeroCuotas--;
            }

        } else {
            $this->createInstance($data);
        }
    }

    public function createInstance($data)
    {
        try {
            $myentity = new EComponentesSalariales();
            $myentity->setEmpleado($data['empleado']);
            $myentity->setComponente(0);
            $myentity->setTipoDeuda($data['tipoDeuda']);
            $myentity->setMontoTotal($data['montoTotal']);
            $myentity->setNumeroCuotas(0);
            $myentity->setCantidad(null);
            $myentity->setPagado(false);
            $myentity->setPermanente($data['permanente']);
            //$myentity->setFechaInicio(null);
            //$myentity->setFechaVencimiento(null);
            $myentity->setFecha($data['fechaInicio']);
            //$inicio_formated = date('Y-m-d', strtotime($inicio_formated . ' + ' . $iCantDias . ' days'));
            $myentity->setMontoRestante(0);

            $this->em->persist($myentity);
            $this->em->flush();

            return true;
        } catch (\Exception $e) {
            $this->logger->addCritical(sprintf('Ha ocurrido un error persistiendo rebajo. Detalles: %s',$e->getMessage()));
            return false;
        }
    }

    /**
     * Selecciona el siguiente periodo de pago correspondiente que coincida luego de la fecha final pasada por parámetros
     * @param \DateTime $lastDate
     * @return array
     */
    public function getNextPeriodoPago(\DateTime $lastDate)
    {
        $init_date = clone($lastDate);
        $init_date = $init_date->modify('+ 1 day');
        $end_date = clone($lastDate);
        $end_date  = $end_date->modify(sprintf('+ %d days', $this->periodoPago->getCantdias()));

        return array(
            'start' => $init_date,
            'end'   => $end_date,
        );
    }

    /**
     * Dada una fecha y la última fecha de un periodo devuelve el periodo de pago correspondiente para la fecha entrada
     * @param \DateTime $date
     * @param \DateTime $lastDate
     * @return array
     */
    public function selectPeriodoPagoForDate(\DateTime $date, \DateTime $lastDate)
    {
        if ($date->getTimestamp() < $lastDate->getTimestamp()) {
            $date = clone($lastDate);
            return $this->selectPeriodoPagoForDate($date->modify('+ 1 day'), $lastDate);
        } else {
            $periodo = $this->getNextPeriodoPago($lastDate);
            if ($date->getTimestamp() >= $periodo['start']->getTimestamp()
                && $date->getTimestamp() <= $periodo['end']->getTimestamp()) {
                return $periodo;
            } else {
                return $this->selectPeriodoPagoForDate($date, $periodo['end']);
            }
        }
    }
} 