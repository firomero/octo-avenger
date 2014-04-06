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

class CDeudasRepository extends EntityRepository
{

    public function filterDeudas($filtros = array())
    {
        try {

            $sql = "SELECT s  FROM PlanillasCoreBundle:CDeudas s";
            $case = false;
            //echo ( $filtros['tipoDeuda']=="");
            //print_r($filtros['tipoDeuda']);exit;
            if (isset($filtros['tipoDeuda'])) {
                $sql .= ($case == true) ? " AND " : " WHERE ";
                $sql .= ' s.tipoDeuda = '. $filtros['tipoDeuda'] ;
                $case = true;

            }

            if (isset($filtros['pagado'])) {
                $sql .= ($case == true) ? " AND " : " WHERE ";
                $sql .= ' s.pagado = '.$filtros['pagado'];//->format('Y-m-d');
                $case = true;
            }
           if (isset($filtros['empleado'])&& !empty($filtros['empleado'])) {
                $sql.=($case==true)?" AND ":" WHERE ";
                //print_r($filtros['empleado']);exit;
                $sql .= ' s.empleado= '.$filtros['empleado'];//
                //$sql.=' e.nombre LIKE \'%'.$filtros['empleado'].'%\'';
                $case=true;

            }

            $sql .= ' ORDER BY s.id DESC';
            $query = $this->_em->createQuery($sql);
            ///print_r($query->getDQL());exit;
            return $query->getResult();
        } catch (NoResultException $e) {
            return array();
        }
    }

}
