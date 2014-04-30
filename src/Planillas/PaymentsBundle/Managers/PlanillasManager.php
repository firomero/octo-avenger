<?php

namespace Planillas\PaymentsBundle\Managers;

use Doctrine\ORM\EntityManager;
use Planillas\CoreBundle\Entity\CPlanillas;
use Planillas\CoreBundle\Entity\CPlanillasEmpleado;
use Symfony\Bridge\Monolog\Logger;

class PlanillasManager
{

    /**
     * @var  \Doctrine\ORM\EntityManager $em
     */
    private $em;

    /**
     * @var  \Symfony\Bridge\Monolog\Logger $logger
     */
    private $logger;

    /**
     * @var  PlanillaEmpleadoManager $planillaEmpleadoManager
     */
    private $planillaEmpleadoManager;

    /**
     * @param  \Doctrine\ORM\EntityManager $em
     * @param  \Symfony\Bridge\Monolog\Logger $logger
     */
    public function __construct(EntityManager $em, PlanillaEmpleadoManager $planillaEmpleadoManager, Logger $logger)
    {
        $this->em                       = $em;
        $this->planillaEmpleadoManager  = $planillaEmpleadoManager;
        $this->logger                   = $logger;
    }

    /**
     * Comprueba si existe o se encuentra incluido el preríodo de pago en alguna planilla existente
     * @param $fechaInicio
     * @param $fechaFin
     * @throws \Exception
     * @return boolean | string
     */
    public function existePlanillaInPeriodo($fechaInicio, $fechaFin)
    {
        $valid = $this->em->getRepository('PlanillasCoreBundle:CPlanillas')
            ->isPeriodClean($fechaInicio, $fechaFin);

        if ($valid === true) {
            return false;
        } elseif (is_object($valid) && $valid instanceof CPlanillas) {
            return sprintf('Ya existe una planilla con fechas %s hasta %s,'.
                'por lo cual el periodo entrado no es válido',
                $valid->getFechaInicio()->format('d/m/Y'),
                $valid->getFechaFin()->format('d/m/Y'));
        } elseif (is_array($valid)) {
            return sprintf('Ya existen varias planillas que concuerdan con el periodo entrado.');
        } else {
            throw new \Exception("Ha ocurrido un error inesperado buscando los periodos pagados");
        }
    }

    /**
     * Valida si un período de pago es válido teniendo en cuenta las fechas proporcionadas
     *
     * @param \DateTime $fechaInicio
     * @param \DateTime $fechaFin
     * @return array|bool
     */
    public function validarPeriodoPago(\DateTime $fechaInicio, \DateTime $fechaFin)
    {
        if ($fechaInicio === null || $fechaFin === null) {
            return false;
        }

        $periodo_activo = $this->getPeriodoPagoActivo();

        $iCantDias = $periodo_activo->getCantDias();
        $diff = date_diff($fechaInicio, $fechaFin);
        if ($diff->days > 0 && ($iCantDias == ($diff->days + 1))) {
            return true;
        }
        //vamos validar que las fechas entradas no esten dentro de otro periodo pago

        return false;
    }

    /**
     * Obtiene la última planilla generada en el sistema
     *
     * @return string
     */
    public function getUltimoPeriodoPagado()
    {
        $lastPlanilla = $this->em->getRepository('PlanillasCoreBundle:CPlanillas')
            ->getLastPlanilla();

        if ($lastPlanilla !== false && $lastPlanilla instanceof CPlanillas) {
            return sprintf('%s al %s',
                $lastPlanilla->getFechaInicio()->format('d-m-Y'),
                $lastPlanilla->getFechaFin()->format('d-m-Y')
            );
        }

        return "No existen pagos anteriores";
    }

    /**
     * Persiste la planilla para el perído pasado por parámetros
     */
    public function savePlanilla(\DateTime $fecha_inicio, \DateTime $fecha_fin)
    {
        $periodoPago = $this->getPeriodoPagoActivo();

        if ($periodoPago === false) {
            return false;
        }

        $oPlanillas = new CPlanillas();
        $oPlanillas->setFechaInicio($fecha_inicio);
        $oPlanillas->setFechaFin($fecha_fin);

        $oPlanillas->setPeriodo($periodoPago);
        $oPlanillas->setCreatedAt(new \DateTime());
        try {
            $this->em->beginTransaction();

            $this->em->persist($oPlanillas);

            $this->savePlanillasIntoDependencias($oPlanillas);

            $this->em->commit();

            return true;
        } catch (\Exception $e) {
            $this->logger->addError(sprintf('Ha ocurrido un error creando planilla para el periodo del %s al %s.',
                $fecha_inicio->format('d/m/Y'), $fecha_fin->format('d/m/Y')));
            $this->logger->addCritical(sprintf('Detalles del error: %s',$e->getMessage()));

            $this->em->rollback();

            return false;
        }
    }

    public function savePlanillasIntoDependencias(CPlanillas $planillas) {
        if ($planillas instanceof CPlanillas) {
            //$this->planilla = $object;

            // encontrar todos los empleados para los cuales efectuar el pago
            $oEmpleados = $this->findAllEmployee();

            if ($oEmpleados && count($oEmpleados)) {
                foreach ($oEmpleados as $oEmpleado) {

                    // TODO: Terminar la implementación del salvar planilla
                    /*$empleadoplanillas = $this->planillaEmpleadoManager->createPlanillaEmpleado(
                        $oEmpleado, $planillas,
                    );

                    $empleadoplanillas->setSalarioPeriodo($this->getSalarioPeriodoByEmpleado($oEmpleado->getId()));
                    $empleadoplanillas->setSalarioTotal($this->getSalarioBaseByEmpleado($oEmpleado->getId())); //por ahora no funciona
                    $empleadoplanillas->setPlanilla($planillas);
                    $empleadoplanillas->setEmpleado($oEmpleado);
                    $this->em->persist($empleadoplanillas);
                    $this->em->flush();


                    $aBonificionesTotalTemp = $this->findBonificacionesByEmpleado($oEmpleado->getId(), true); //update not show
                    $aDeudasTotalTemp = $this->findDeudasByEmpleado($oEmpleado->getId(), true);
                    $aDiasExtraTemp = $this->findDiasExtrasByEmpleado($oEmpleado->getId(), true);
                    $aHorasExtrasTemp = $this->findHorasExtrasByEmpleado($oEmpleado->getId(), true);
                    $aDiasMenosTemp = $this->findDiasMenosByEmpleado($oEmpleado->getId(), true);
                    $aIncapacidades = $this->findIncapacidadesByEmpleado($oEmpleado->getId(), true);
                    */
                }
            }
        }
    }

    /**
     * Obtiene el periodo de pago activo
     * @throws \Exception
     * @return \Planillas\NomencladorBundle\NPeriodoPago
     */
    public function getPeriodoPagoActivo()
    {
        $periodo_activo = $this->em->getRepository('PlanillasNomencladorBundle:NPeriodoPago')
            ->findOneBy(array(
                'activo' => true
            ));

        if (!$periodo_activo) {
            throw new \Exception('No se existe un periodo de pago activo en el sistema, debe registrar al menos uno.');
        }

        return $periodo_activo;
    }

} 