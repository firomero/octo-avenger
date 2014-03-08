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

class CAusenciasRepository extends EntityRepository
{


    public function filterAusencias($filtros = array())
    {
        try {


            $sql = "SELECT s  FROM PlanillasCoreBundle:CAusencias s INNER Join s.empleado e WHERE e.activo=1";
            $case = true;

            if (isset($filtros['fechaInicio']) && !empty($filtros['fechaInicio'])) {
                $sql .= ($case == true) ? " AND " : " WHERE ";
                //$fInicio=new \DateTime($filtros['fechaInicio']);
                $sql .= ' s.fechaInicio >= \'' . date_format($filtros['fechaInicio'],'Y-m-d'). '\'';
                //$sql .= ' s.fechaInicio >= '. date_format(new \DateTime($filtros['fechaInicio']) ,'Y-m-d');
                $case = true;
            }
            if(isset($filtros['empleado'])&& !empty($filtros['empleado']))
            {
                $sql.=($case==true)?" AND ":" WHERE ";
                //print_r($filtros['empleado']);exit;
                $sql.=' e.nombre LIKE \'%'.$filtros['empleado'].'%\'';
                $case=true;

            }
            if (isset($filtros['fechaFin']) && !empty($filtros['fechaFin'])) {
                $sql .= ($case == true) ? " AND " : " WHERE ";
                //$sql .= ' s.fechaFin <= '. $filtros['fechaFin'] ;
                $sql .= ' s.fechaFin <= \'' . date_format($filtros['fechaFin'],'Y-m-d'). '\'';
                $case = true;
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