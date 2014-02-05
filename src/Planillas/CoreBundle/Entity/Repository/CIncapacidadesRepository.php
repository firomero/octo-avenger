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

class CIncapacidadesRepository extends EntityRepository
{

    /**
     * funcion que filtra las incapacidades
     * @param array $filtros
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
                $case = true;

            }

            if (isset($filtros['fechaInicio']) && $filtros['fechaInicio']!=null) {


                if(isset($filtros['fechaFin']) && $filtros['fechaFin']!=null)
                {
                    $sql .= ($case == true) ? " AND " : " WHERE ";
                    $sql .= ' s.fechaInicio >= '.$filtros['fechaInicio']->format('Y-m-d');
                    $case = true;
                }
                else
                {
                    $sql .= ($case == true) ? " AND " : " WHERE ";
                    $sql .= ' s.fechaInicio = '.$filtros['fechaInicio']->format('Y-m-d');
                    $case = true;
                }

            }
            if (isset($filtros['fechaFin']) && $filtros['fechaFin']!=null) {

                if(isset($filtros['fechaInicio']) && $filtros['fechaInicio']!=null)
                {
                    $sql .= ($case == true) ? " AND " : " WHERE ";
                    $sql .= ' s.fechaFin >= '.$filtros['fechaFin']->format('Y-m-d');
                    $case = true;
                }
                else
                {
                    $sql .= ($case == true) ? " AND " : " WHERE ";
                    $sql .= ' s.fechaFin >= '.$filtros['fechaFin']->format('Y-m-d');
                    $case = true;
                }

            }


            $sql .= ' ORDER BY s.id DESC';
            $query = $this->_em->createQuery($sql);
            //print_r($query->getSQL());exit;
            return $query->getResult();
        } catch (NoResultException $e) {
            return array();
        }
    }

}