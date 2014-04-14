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
use Doctrine\ORM\NoResultException;

class CIncapacidadesRepository extends EntityRepository
{

    /**
     * funcion que filtra las incapacidades
     * @param  array $filtros
     * @return array
     */
    public function filterIncapacidades($filtros = array())
    {
        try {

            $sql = "SELECT s  FROM PlanillasCoreBundle:CIncapacidades s INNER JOIN s.empleado e where e.activo=1";
            $case = true;

            if (isset($filtros['tipoIncapacidad']) && $filtros['tipoIncapacidad'] != 0) {
                if ($filtros['tipoIncapacidad'] != 0) {
                    $sql .= ($case == true) ? " AND " : " WHERE ";
                    $sql .= ' s.tipoIncapacidad = ' . $filtros['tipoIncapacidad'];
                    $case = true;
                }

            }

            if (isset($filtros['empleado']) && !empty($filtros['empleado'])) {
                $sql .= ($case == true) ? " AND " : " WHERE ";
                $sql .= ' e.nombre LIKE \'%' . $filtros['empleado'] . '%\'';
                $sql .= ' OR e.primerApellido LIKE \'%' . $filtros['empleado'] . '%\'';
                //$sql .= ' OR e.primerApellido LIKE \'%' . $filtros['empleado'] . '%\'';
                $case = true;

            }

                if (isset($filtros['fechaInicio']) && $filtros['fechaInicio']!=null) {
                    $sql .= ($case == true) ? " AND " : " WHERE ";
                    //$sql .= ' s.fechaInicio >= '.$filtros['fechaInicio']->format('Y-m-d');
                    $sql .= ' s.fechaInicio >- \'' . date_format($filtros['fechaInicio'],'Y-m-d'). '\'';
                    $case = true;
                }

                if (isset($filtros['fechaFin']) && $filtros['fechaFin']!=null) {
                    $sql .= ($case == true) ? " AND " : " WHERE ";
                    $sql .= ' s.fechaFin <= \'' . date_format($filtros['fechaFin'],'Y-m-d'). '\'';
                    //$sql .= ' s.fechaFin <= '.$filtros['fechaFin']->format('Y-m-d');
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
     * Obtiene las incapacidades anteriores a la fecha de inicio pasada por parámetros y pertenecientes al propio mes
     *
     * @param $id_empleado
     * @param \DateTime $fecha_inicio
     * @return array
     */
    public function findIncapacidadesAnterioresAPeriodo($id_empleado, \DateTime $fecha_inicio)
    {
        $query = $this->_em->createQueryBuilder()
            ->select('i')
            ->from('PlanillasCoreBundle:CIncapacidades', 'i')
            ->where('i.empleado = :idempleado AND DATE_FORMAT(i.fecha,\'%m\') = :mesfecha AND i.fecha < :fechainicio')
            ->setParameters(array(
                'idempleado'    => $id_empleado,
                'mesfecha'      => $fecha_inicio->format('m'),
                'fechainicio'   => $fecha_inicio,
            ))
            ->getQuery();

        try {
            return $query->getResult();
        } catch (NoResultException $e) {
            return array();
        }
    }
}