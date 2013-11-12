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

class CDiasExtraRepository extends EntityRepository
{

    /**
     * funcion que filtra las incapacidades
     * @param array $filtros
     * @return array
     */
    public function filterDiasextra($filtros = array())
    {
        try {


            $sql = "SELECT s  FROM PlanillasCoreBundle:CDiasExtra s INNER JOIN s.empleado e where e.activo=1";
            $case = true;


            if (isset($filtros['empleado']) && !empty($filtros['empleado'])) {
                $sql .= ($case == true) ? " AND " : " WHERE ";
                //print_r($filtros['empleado']);exit;
                //$sql .= ' s.empleado= '.$filtros['empleado'];//
                $sql .= ' e.nombre LIKE \'%' . $filtros['empleado'] . '%\'';
                $case = true;

            }

            if (isset($filtros['fecha']) && $filtros['fecha'] != null) {
                $sql .= ($case == true) ? " AND " : " WHERE ";
                $sql .= ' s.fecha = ' . $filtros['fecha']->format('Y-m-d');
                $case = true;
            }


            $sql .= ' ORDER BY s.id DESC';
            $query = $this->_em->createQuery($sql);

            return $query->getResult();
        } catch (NoResultException $e) {
            return array();
        }
    }

}