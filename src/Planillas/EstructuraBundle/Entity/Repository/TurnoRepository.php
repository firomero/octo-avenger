<?php

namespace Planillas\EstructuraBundle\Entity\Repository;

class TurnoRepository extends AbstractRepository
{
    public function findAllNotDeleted ($filters = array())
    {
        $dql = "SELECT t FROM PlanillasEstructuraBundle:Turno t";

        $this->addParamsToDql($dql, 't', $filters);

        $dql.=" ORDER BY t.id DESC";

        $query = $this->_em->createQuery($dql);
        $this->addParamsToQuery($query, $filters);

        return $query;
    }
} 