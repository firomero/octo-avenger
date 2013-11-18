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

class CEmpleadoRepository extends  EntityRepository {


    public function filterEmpleado($filtros=array()){
        try{

            $sql = "SELECT emp FROM PlanillasCoreBundle:CEmpleado emp";
            $case=false;
            if(isset($filtros['cedula'])&& !empty($filtros['cedula']))
            {
                $sql.=($case==true)?" AND ":" WHERE ";
                $sql.=' emp.cedula LIKE \'%'.$filtros['cedula'].'%\'';
                $case=true;
            }
            if(isset($filtros['nombre'])&& !empty($filtros['nombre']))
            {
              $sql.=($case==true)?" AND ":" WHERE ";
              $sql.=' emp.nombre LIKE \'%'.$filtros['nombre'].'%\'';
               $case=true;

            }
            if(isset($filtros['primerApellido'])&& !empty($filtros['primerApellido']))
            {
                $sql.=($case==true)?" AND ":" WHERE ";
                $sql.=' emp.primerApellido LIKE \'%'.$filtros['primerApellido'].'%\'';
                $case=true;
            }
            if(isset($filtros['segundoApellido'])&& !empty($filtros['segundoApellido']))
            {
                $sql.=($case==true)?" AND ":" WHERE ";
                $sql.=' emp.segundoApellido LIKE \'%'.$filtros['segundoApellido'].'%\'';

            }
            ;
            if(isset($filtros['inactivo']) && $filtros['inactivo']!=null)
            {
                $sql.=($case==true)?" AND ":" WHERE ";
                $sql.=' emp.activo =0';

            }


            $sql .=' ORDER BY emp.id DESC';
            $query = $this->_em->createQuery($sql);


            return $query->getResult();
        }catch (NoResultException $e){
            return array();
        }
    }

}