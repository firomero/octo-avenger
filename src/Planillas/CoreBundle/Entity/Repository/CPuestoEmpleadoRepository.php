<?php

namespace Planillas\CoreBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;

class CPuestoEmpleadoRepository extends EntityRepository
{
    public function getEmpresaByEmpleadoId($empleadoId)
    {
        $query = $this->_em->createQueryBuilder()
            ->select('pe','e')
            ->from('PlanillasCoreBundle:CPuestoEmpleado','pe')
            ->innerJoin('pe.empresa','e')
            ->innerJoin('pe.empleado', 'em')
            ->where('em.id = :empleadoId')
            ->setParameter('empleadoId', $empleadoId)
            ->getQuery();

        try {
            return $query->getSingleResult();
        } catch (NoResultException $e) {
            return false;
        }
    }
}
