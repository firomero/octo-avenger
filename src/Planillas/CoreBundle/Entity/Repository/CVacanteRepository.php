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

class CVacanteRepository extends  EntityRepository
{
    public function filterVacante($filtros=array())
    {
        try {
            $sql = "SELECT v,t  FROM PlanillasCoreBundle:CVacante v INNER JOIN v.trabajo t";
            $case=false;

            if (isset($filtros['nombre'])&& !empty($filtros['nombre'])) {
                $sql.=($case==true)?" AND ":" WHERE";
                $sql.=' v.nombre LIKE \'%'.$filtros['nombre'].'%\'';
                $case=true;
            }

            if (isset($filtros['trabajo'])&& !empty($filtros['trabajo'])) {
               $sql.=($case==true)?" AND ":" WHERE";
               $sql.=' t.id='.$filtros['trabajo']->getId();
               $case=true;

            }

            if (isset($filtros['activo'])&& !empty($filtros['activo'])) {
                $sql.=($case==true)?" AND ":" WHERE ";
                $sql.=' v.activo = 1';
            }

            $sql .=' ORDER BY v.id DESC';
            $query = $this->_em->createQuery($sql);

            return $query->getResult();
        } catch (NoResultException $e) {
            return array();
        }
    }
}
