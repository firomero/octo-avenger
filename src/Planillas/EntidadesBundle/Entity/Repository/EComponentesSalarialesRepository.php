<?php

namespace Planillas\EntidadesBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;

/**
 * EComponentesSalarialesRepository
 *
 */
class EComponentesSalarialesRepository extends EntityRepository
{
    public function findAllNotDeleted($filter = array())
    {
        $query = $this->_em->createQueryBuilder()
            ->select('c')
            ->from('PlanillasEntidadesBundle:EComponentesSalariales', 'c')
            ->orderBy('c.id','DESC');

        if (isset($filter['empleado']) && $filter['empleado']) {
            $query->andWhere('c.empleado=:empleado')
                ->setParameter('empleado',$filter['empleado']);
        }
        if (isset($filter['tipoComponente']) && $filter['tipoComponente'] !== null) {
            $query->andWhere('c.componente=:tipoComponente')
                ->setParameter('tipoComponente',$filter['tipoComponente']);
        }
        if (isset($filter['fechaInicio']) && $filter['fechaInicio']) {
            $query->andWhere('c.fecha>=:fechaInicio')
                ->setParameter('fechaInicio',$filter['fechaInicio']);
        }
        if (isset($filter['fechaFin']) && $filter['fechaFin']) {
            $query->andWhere('c.fecha<=:fechaFin')
                ->setParameter('fechaFin',$filter['fechaFin']);
        }
        if((isset($filter['fechaInicio']) && $filter['fechaInicio']) || (isset($filter['fechaFin']) && $filter['fechaFin'])) {
            $query->andWhere('c.permanente<>1');
        }

        try {
            return $query->getQuery();
        } catch(NoResultException $e) {
            return array();
        }
    }
}
