<?php

namespace Planillas\EstructuraBundle\Entity\Repository;

class SucursalRepository extends AbstractRepository
{
    public function findAllNotDeleted ($filters = array())
    {
        $dql = "SELECT s FROM PlanillasEstructuraBundle:Sucursal s";

        $this->addParamsToDql($dql, 's', $filters);

        $dql.=" ORDER BY s.id DESC";

        $query = $this->_em->createQuery($dql);
        $this->addParamsToQuery($query, $filters);

        return $query;
    }
} 