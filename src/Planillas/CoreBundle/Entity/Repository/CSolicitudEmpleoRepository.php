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

class CSolicitudEmpleoRepository extends EntityRepository
{


    public function filterSolicitudEmpleo($filtros = array())
    {
        try {

            $sql = "SELECT s  FROM PlanillasCoreBundle:CSolicitudEmpleo s  Join s.vacante v";
            $case = false;

            if (isset($filtros['nombre']) && !empty($filtros['nombre'])) {
                $sql .= ($case == true) ? " AND " : " WHERE ";
                $sql .= ' s.nombre LIKE \'%' . $filtros['nombre'] . '%\'';
                $case = true;
            }
            if (isset($filtros['apellidos']) && !empty($filtros['apellidos'])) {
                $sql .= ($case == true) ? " AND " : " WHERE ";
                $sql .= ' s.apellidos LIKE \'%' . $filtros['apellidos'] . '%\'';
                $case = true;

            }
            if (isset($filtros['fecha']) && !empty($filtros['fecha'])) {
                $sql .= ($case == true) ? " AND " : " WHERE ";
                $sql .= ' s.fecha = ' . $filtros['fecha']->format('Y-m-d');
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