<?php
/**
 * Created by PhpStorm.
 * User: cinfante
 * Date: 05/04/14
 * Time: 07:04 AM
 */

namespace Planillas\CoreBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;
use Planillas\CoreBundle\Entity\CSalarioBase;

class CSalarioBaseRepository extends EntityRepository
{
    /**
     * Obtiene el salario bruto de un empleado
     * @param  string $idEmpleado
     * @return int
     */
    public function getSalarioBaseByEmpleado($idEmpleado)
    {
        try {
            /** @var CSalarioBase $salarioBase */
            $salarioBase = $this->createQueryBuilder('s')
                ->where('s.empleado=:id_empleado')
                ->setParameter('id_empleado',$idEmpleado)
                ->getQuery()
                ->getSingleResult();

            return $salarioBase;
        } catch (NoResultException $e) {
            return null;
        }
    }
}
