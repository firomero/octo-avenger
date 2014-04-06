<?php

namespace Planillas\EstructuraBundle\Entity\Repository;

class ClienteRepository extends AbstractRepository
{
    public function findAllNotDeleted ($filters = array())
    {
        $dql = "SELECT c FROM PlanillasEstructuraBundle:Cliente c";

        $this->addParamsToDql($dql, 'c', $filters);

        $dql.=" ORDER BY c.id DESC";

        $query = $this->_em->createQuery($dql);
        $this->addParamsToQuery($query, $filters);

        return $query;
    }
} 