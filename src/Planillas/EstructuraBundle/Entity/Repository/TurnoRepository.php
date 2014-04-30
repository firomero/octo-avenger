<?php

namespace Planillas\EstructuraBundle\Entity\Repository;

use Doctrine\ORM\NoResultException;

class TurnoRepository extends AbstractRepository
{
    public function findAllNotDeleted ($filters = array())
    {
        $dql = "SELECT t FROM PlanillasEstructuraBundle:Turno t";

        $dql = $this->addParamsToDql($dql, 't', $filters);

        $dql.=" ORDER BY t.id DESC";

        $query = $this->_em->createQuery($dql);
        $query = $this->addParamsToQuery($query, $filters);

        return $query;
    }

    public function findAllBySucursalId($id)
    {
        $query = $this->findAllNotDeleted(array(
            'sucursal' => $id,
        ));

        try {
            return $query->getResult();
        } catch (NoResultException $e) {
            return array();
        }
    }
}
