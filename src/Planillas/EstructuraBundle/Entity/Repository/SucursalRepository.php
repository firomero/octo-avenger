<?php

namespace Planillas\EstructuraBundle\Entity\Repository;

use Doctrine\ORM\NoResultException;

class SucursalRepository extends AbstractRepository
{
    public function findAllNotDeleted ($filters = array())
    {
        $dql = "SELECT s FROM PlanillasEstructuraBundle:Sucursal s";

        $dql = $this->addParamsToDql($dql, 's', $filters);

        $dql.=" ORDER BY s.id DESC";

        $query = $this->_em->createQuery($dql);
        $query = $this->addParamsToQuery($query, $filters);

        return $query;
    }

    public function findAllByClienteId($id)
    {
        $query = $this->findAllNotDeleted(array(
            'cliente' => $id,
        ));

        try {
            return $query->getResult();
        } catch (NoResultException $e) {
            return array();
        }
    }
}
