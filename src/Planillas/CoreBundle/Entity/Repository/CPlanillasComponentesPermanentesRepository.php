<?php

namespace Planillas\CoreBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;

class CPlanillasComponentesPermanentesRepository extends EntityRepository
{
    public function findComponentesPermanentesByEmpleadoInPlanilla($empleadoId, $planillaId, $componente)
    {
        $query = $this->_em->createQueryBuilder()
            ->select('c')
            ->from('PlanillasCoreBundle:CPlanillasComponentesPermanentes','c')
            ->innerJoin('c.empleado', 'e')
            ->innerJoin('c.componentePermanente', 'cp')
            ->innerJoin('c.planillaEmpleado', 'pe')
            ->innerJoin('pe.planilla','p')
            ->where('e.id = :empleadoId AND p.id = :planillaId AND cp.componente = :componente')
            ->setParameters(array(
                'empleadoId' => $empleadoId,
                'planillaId' => $planillaId,
                'componente' => $componente,
            ))
            ->getQuery();
        ;

        try {
            return $query->getResult();
        } catch (NoResultException $e) {
            return array();
        }
    }
} 