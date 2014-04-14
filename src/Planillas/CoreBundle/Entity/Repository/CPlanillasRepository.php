<?php
/**
 * Created by JetBrains PhpStorm.
 * User: cinfante
 * Date: 06/07/13
 * Time: 09:11 AM
 * To change this template use File | Settings | File Templates.
 */

namespace Planillas\CoreBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

class CPlanillasRepository extends EntityRepository
{

    /**
     * Obtiene las Ausencias dado el filtro especificado
     *
     * @param array $filtros
     * @return array
     */
    public function filterAusencias($filtros = array())
    {
        try {

            $sql = "SELECT s  FROM PlanillasCoreBundle:CAusencias s INNER Join s.empleado e WHERE e.activo=1";
            $case = true;

            if (isset($filtros['fechaInicio']) && !empty($filtros['fechaInicio'])) {
                $sql .= ($case == true) ? " AND " : " WHERE ";
                $sql .= ' s.fechaInicio >= '. $filtros['fechaInicio'] ;
                $case = true;
            }
            if (isset($filtros['empleado'])&& !empty($filtros['empleado'])) {
                $sql.=($case==true)?" AND ":" WHERE ";
                //print_r($filtros['empleado']);exit;
                $sql.=' e.nombre LIKE \'%'.$filtros['empleado'].'%\'';
                $case=true;

            }
            if (isset($filtros['fechaFin']) && !empty($filtros['fechaFin'])) {
                $sql .= ($case == true) ? " AND " : " WHERE ";
                $sql .= ' s.fechaFin <= '. $filtros['fechaFin'] ;
                $case = true;
            }

            $sql .= ' ORDER BY s.id DESC';
            $query = $this->_em->createQuery($sql);

            return $query->getResult();
        } catch (NoResultException $e) {
            return array();
        }
    }

    /**
     * Comprueba si existe al menos una planilla generada con los períodos entrados, o que al menos la de inicio
     * se encuentre incluida dentro de una planilla generada
     *
     * @param  \DateTime $fechaInicio
     * @param  \DateTime $fechaFin
     */
    public function isPeriodClean(\DateTime $fechaInicio, \DateTime $fechaFin)
    {
        $query = $this->_em->createQueryBuilder('p')
            ->select('p.id')
            ->from('PlanillasCoreBundle:CPlanillas', 'p')
            ->where('(p.fechaInicio = :fechaInicio AND p.fechaFin = :fechaFin) OR p.fechaFin >= :fechaInicio')
            ->setParameters(array(
                'fechaInicio' => $fechaInicio,
                'fechaFin' => $fechaFin,
            ))
            ->getQuery();

        try {
            return $query->getSingleResult();
        } catch (NoResultException $e) {
            return true;
        } catch (NonUniqueResultException $e) {
            return $query->getResult();
        }

    }

    /**
     * Obtiene la última planilla registrada en el sistema, falso en caso de no existir ninguna
     *
     * @return bool|mixed
     */
    public function getLastPlanilla()
    {
        $query = $this->_em->createQueryBuilder()
            ->select('p')
            ->from('PlanillasCoreBundle:CPlanillas','p')
            ->orderBy('p.fechaFin','DESC')
            ->setMaxResults('1')
            ->getQuery();
        try {
            return $query->getSingleResult();
        } catch (NoResultException $e) {
            return false;
        }
    }
}