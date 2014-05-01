<?php

namespace Planillas\EntidadesBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;
use Planillas\CoreBundle\Entity\CPlanillas;
use Planillas\CoreBundle\Entity\CPlanillasEmpleado;

/**
 * EComponentesSalarialesRepository
 *
 */
class EComponentesSalarialesRepository extends EntityRepository
{
    public function findAllNotDeleted($filter = array())
    {
        $query = $this->_em->createQueryBuilder()
            ->select('c')
            ->from('PlanillasEntidadesBundle:EComponentesSalariales', 'c')
            ->orderBy('c.id','DESC');

        if (isset($filter['empleado']) && $filter['empleado']) {
            $query->andWhere('c.empleado=:empleado')
                ->setParameter('empleado',$filter['empleado']);
        }
        if (isset($filter['tipoComponente']) && $filter['tipoComponente'] !== null) {
            $query->andWhere('c.componente=:tipoComponente')
                ->setParameter('tipoComponente',$filter['tipoComponente']);
        }
        if (isset($filter['fechaInicio']) && $filter['fechaInicio']) {
            $query->andWhere('c.fecha>=:fechaInicio')
                ->setParameter('fechaInicio',$filter['fechaInicio']);
        }
        if (isset($filter['fechaFin']) && $filter['fechaFin']) {
            $query->andWhere('c.fecha<=:fechaFin')
                ->setParameter('fechaFin',$filter['fechaFin']);
        }
        if((isset($filter['fechaInicio']) && $filter['fechaInicio']) || (isset($filter['fechaFin']) && $filter['fechaFin'])) {
            $query->andWhere('c.permanente<>1');
        }

        try {
            return $query->getQuery();
        } catch(NoResultException $e) {
            return array();
        }
    }

    /**
     * Devuelve las componentes para empleado entrado por parámetros en el periodo indicado. En caso de entrar la
     * planilla de empleado limita los resultados a la misma
     *
     * @param $empleadoId
     * @param $componente
     * @param null $fechaInicio
     * @param null $fechaFin
     * @param \Planillas\CoreBundle\Entity\CPlanillas $planilla
     * @internal param null $planillaEmpleadoId
     * @return array
     */
    public function findByEmpleadoInPeriod($empleadoId, $componente, $fechaInicio = null, $fechaFin = null,
                                           CPlanillas $planilla = null)
    {
        $query = $this->_em->createQueryBuilder()
            ->select('c','e')
            ->from('PlanillasEntidadesBundle:EComponentesSalariales', 'c')
            ->join('c.empleado', 'e')
            ->where('e.id = :empleadoId AND c.componente = :componente AND c.deleted_at IS NULL')
            ->setParameters(array(
                'empleadoId' => $empleadoId,
                'componente' => $componente,
            ));

        if ($planilla !== null) {
            $query->leftJoin('c.planillaEmpleado','pe')
                ->leftJoin('pe.planilla','p')
                ->andWhere('pe IS NOT NULL AND p = :planilla')
                ->setParameter('planilla', $planilla);
        } else {
            $query->andWhere('c.fecha >= :fechaInicio AND c.fecha <= :fechaFin')
                ->setParameter('fechaInicio', $fechaInicio)
                ->setParameter('fechaFin', $fechaFin);
        }

        try {
            return $query->getQuery()->getResult();
        } catch (NoResultException $e) {
            return array();
        }
    }
}
