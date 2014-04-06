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

class CHorasExtrasRepository extends EntityRepository
{

    public function filterHorasExtras($filtros = array())
    {
        try {

            $sql = "SELECT s  FROM PlanillasCoreBundle:CHorasExtras s INNER JOIN s.empleado e WHERE e.activo=1";
            $case = true;

            if (isset($filtros['cantidadHoras']) && !empty($filtros['cantidadHoras'])) {
                $sql .= ($case == true) ? " AND " : " WHERE ";
                $sql .= ' s.cantidadHoras = '. $filtros['cantidadHoras'] ;
                $case = true;
            }
            if (isset($filtros['fechaHorasExtras']) && !empty($filtros['fechaHorasExtras'])) {
                $sql .= ($case == true) ? " AND " : " WHERE ";
                $sql .= ' s.fechaHorasExtras = '.$filtros['fechaHorasExtras']->format('Y-m-d');
                $case = true;
            }
            if (isset($filtros['empleado'])&& !empty($filtros['empleado'])) {
                $sql.=($case==true)?" AND ":" WHERE ";
                //print_r($filtros['empleado']);exit;
                $sql.=' e.nombre LIKE \'%'.$filtros['empleado'].'%\'';
                $case=true;

            }

            $sql .= ' ORDER BY s.fechaHorasExtras DESC';
            $query = $this->_em->createQuery($sql);

            return $query->getResult();
        } catch (NoResultException $e) {
            return array();
        }
    }

}
