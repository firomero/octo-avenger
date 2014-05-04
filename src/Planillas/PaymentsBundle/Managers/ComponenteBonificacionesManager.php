<?php
/**
 * Created by PhpStorm.
 * User: cinfante
 * Date: 30/04/14
 * Time: 03:55 AM
 */

namespace Planillas\PaymentsBundle\Managers;


use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NoResultException;
use Planillas\CoreBundle\Entity\CEmpleado;
use Planillas\CoreBundle\Entity\CPlanillas;
use Planillas\CoreBundle\Entity\CPlanillasBonificacionesPuesto;
use Planillas\CoreBundle\Entity\CPlanillasComponentesPermanentes;
use Planillas\CoreBundle\Entity\CPlanillasEmpleado;
use Planillas\EntidadesBundle\Entity\EComponentesSalariales;
use Symfony\Bridge\Monolog\Logger;

class ComponenteBonificacionesManager
{
    /**
     * @var $em \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * @var $logger \Symfony\Bridge\Monolog\Logger
     */
    private $logger;

    public function __construct(EntityManager $em, Logger $logger)
    {
        $this->em = $em;
        $this->logger = $logger;
    }

    /**
     * Persiste una instancia para componente salarial bonificaciones
     *
     * @param $data
     * @return bool
     */
    public function createBonificacion($data)
    {
        $entity = new EComponentesSalariales();
        $entity->setFecha($data['fechaVencimiento']);
        $entity->setEmpleado($data['empleado']);
        $entity->setMontoTotal($data['monto']);
        $entity->setPermanente($data['permanente']);
        $entity->setDescripcion($data['descripcion']);
        $entity->setComponente(1);
        $entity->setCantidad(null);
        $entity->setPagado(false);

        try {
            $this->em->persist($entity);
            $this->em->flush();

            return true;
        } catch (\Exception $e) {
            $this->logger->addCritical(sprintf('Ha ocurrido un error persistiendo bonificación. Detalles: %s',
                $e->getMessage()));
            return false;
        }
    }

    /**
     * Busca para un empleado todas las componentes de tipo bonificaciones vigentes en el periodo entrado.
     * Si es entrada una planilla de empleado solo devuelve las componentes para esa planilla.
     *
     * @param CEmpleado $empleado
     * @param \DateTime $fechaInicio
     * @param \DateTime $fechaFin
     * @param \Planillas\CoreBundle\Entity\CPlanillas $planilla
     * @internal param \Planillas\CoreBundle\Entity\CPlanillasEmpleado $planillasEmpleado
     * @return array
     */
    public function findBonificacionesByEmpleado(CEmpleado $empleado, \DateTime $fechaInicio, \DateTime $fechaFin,
                                                 CPlanillas $planilla = null)
    {
        $bonificaciones = $this->em->getRepository('PlanillasEntidadesBundle:EComponentesSalariales')
            ->findByEmpleadoInPeriod($empleado->getId(), 1, $fechaInicio, $fechaFin, $planilla);

        return $bonificaciones;
    }

    /**
     * Busca las bonificaciones permanente en planilla
     *
     * @param CEmpleado $empleado
     * @param CPlanillas $planilla
     * @return array
     */
    public function findBonificacionesPermanentesByEmpleadoInPlanilla(CEmpleado $empleado, CPlanillas $planilla)
    {
        $bonificaciones = $this->em->getRepository('PlanillasCoreBundle:CPlanillasComponentesPermanentes')
            ->findComponentesPermanentesByEmpleadoInPlanilla($empleado->getId(), $planilla->getId(), 1);

        return $bonificaciones;
    }

    /**
     * Devuelve las bonificaciones en puesto para el empleado entrado por parámetros
     *
     * @param CEmpleado $empleado
     * @return \Doctrine\Common\Collections\Collection
     */
    public function findBonificacionesInPuestoByEmpleado(CEmpleado $empleado)
    {
        /** @var $puestoEmpleado \Planillas\CoreBundle\Entity\CPuestoEmpleado */
        $puestoEmpleado = $this->em->getRepository('PlanillasCoreBundle:CPuestoEmpleado')
            ->findOneByEmpleado($empleado);
        if($puestoEmpleado)
            $bonificaciones = $puestoEmpleado->getPuesto()->getBonificaciones();
        else
            $bonificaciones = array();

        return $bonificaciones;
    }

    public function findBonificacionesInPuestoByPlanilla(CPlanillas $planillas)
    {
        $query = $this->em->createQueryBuilder()
            ->select('b')
            ->from('PlanillasCoreBundle:CPlanillasBonificacionesPuesto', 'b')
            ->innerJoin('b.planillaEmpleado', 'pe')
            ->innerJoin('pe.planilla', 'p')
            ->where('p = :planilla')
            ->setParameter('planilla', $planillas)
            ->getQuery();
        try {
            $planillasBonificacionesPuesto = $query->getResult();
            $bonificacionesResult = array();
            foreach ($planillasBonificacionesPuesto as $bonificacion) {
                /** @var $bonificacion \Planillas\CoreBundle\Entity\CPlanillasBonificacionesPuesto */
                $bonificacionesResult[] = $bonificacion->getBonificacionPuesto();
            }

            return $bonificacionesResult;
        } catch (NoResultException $e) {
            return array();
        }
    }

    /**
     * Devuelve arreglo de datos para bonificaciones de empleado
     *
     * @param CEmpleado $empleado
     * @param \DateTime $fechaInicio
     * @param \DateTime $fechaFin
     * @param CPlanillas $planillas
     * @return array
     */
    public function getBonificacionesInDataArray(CEmpleado $empleado, \DateTime $fechaInicio, \DateTime $fechaFin,
                                                 CPlanillas $planillas = null)
    {
        $salida = array();
        $salida['total'] = 0;

        // componentes salariales bonificaciones registradas para el empleado
        $bonificaciones = $this->findBonificacionesByEmpleado($empleado, $fechaInicio, $fechaFin, $planillas);

        // bonificaciones en puesto para empleado
        if ($planillas === null) {
            $bonificacionesEnPuesto = $this->findBonificacionesInPuestoByEmpleado($empleado);
        } else {
            $bonificacionesEnPuesto = $this->findBonificacionesInPuestoByPlanilla($planillas);
        }


        if (count($bonificaciones)) {
            foreach ($bonificaciones as $bonificacion) {
                /** @var $bonificacion \Planillas\EntidadesBundle\Entity\EComponentesSalariales */
                $salida['bonificaciones'][] = array(
                    'id'            => $bonificacion->getId(),
                    'descripcion'   => $bonificacion->getDescripcion(),
                    'fecha_inicio'  => $bonificacion->getFecha()->format('d/m/Y'),
                    'monto_total'   => number_format($bonificacion->getMontoTotal(),2,'.',''),
                );

                $salida['total'] += $bonificacion->getMontoTotal();
            }
        }

        if (count($bonificacionesEnPuesto)) {
            foreach ($bonificacionesEnPuesto as $bonificacion) {
                /** @var $bonificacion \Planillas\EstructuraBundle\Entity\BonificacionesEnPuesto */
                $salida['bonificaciones'][] = array(
                    'id'            => $bonificacion->getId(),
                    'descripcion'   => sprintf('Bonificación %s para el puesto %s',
                        $bonificacion->getBonificacion()->getNombre(),
                        $bonificacion->getPuesto()->getNombre()
                    ),
                    'fecha_inicio'  => 'Permanente',
                    'monto_total'   => number_format($bonificacion->getMonto(),2,'.',''),
                );

                $salida['total'] += $bonificacion->getMonto();
            }
        }

        return $salida;
    }

    public function persistBonificacionesToPlanillaEmpleado(CEmpleado $empleado, \DateTime $fechaInicio,
                                                            \DateTime $fechaFin, CPlanillasEmpleado $planillasEmpleado)
    {
        // componentes salariales bonificaciones registradas para el empleado
        $bonificaciones = $this->findBonificacionesByEmpleado($empleado, $fechaInicio, $fechaFin);

        if (count($bonificaciones)) {
            foreach ($bonificaciones as $bonificacion) {
                /** @var $bonificacion \Planillas\EntidadesBundle\Entity\EComponentesSalariales */
                if(!$bonificacion->getPermanente()) {
                    $planillasEmpleado->addComponentesSalarial($bonificacion);
                } else {
                    $componentePermanente = new CPlanillasComponentesPermanentes();
                    $componentePermanente->setEmpleado($empleado);
                    $componentePermanente->setComponentePermanente($bonificacion);
                    // persist?
                    $planillasEmpleado->addComponentePermanente($componentePermanente);
                }
            }
        }

        // bonificaciones en puesto para empleado
        $bonificacionesEnPuesto = $this->findBonificacionesInPuestoByEmpleado($empleado);

        if (count($bonificacionesEnPuesto)) {
            foreach ($bonificacionesEnPuesto as $bonificacion) {
                /** @var $bonificacion \Planillas\EstructuraBundle\Entity\BonificacionesEnPuesto */
                $bonificacionPuesto = new CPlanillasBonificacionesPuesto($bonificacion, $empleado);
                $planillasEmpleado->addBonificacionesPuesto($bonificacionPuesto);
            }
        }

        return $planillasEmpleado;
    }
} 