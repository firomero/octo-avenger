<?php

namespace Planillas\EstructuraBundle\Entity\Repository;

use Doctrine\ORM\NoResultException;

class ClienteRepository extends AbstractRepository
{
    public function findAllNotDeleted ($filters = array())
    {
        $dql = "SELECT c FROM PlanillasEstructuraBundle:Cliente c";

        $dql = $this->addParamsToDql($dql, 'c', $filters);

        $dql.=" ORDER BY c.id DESC";

        $query = $this->_em->createQuery($dql);
        $query = $this->addParamsToQuery($query, $filters);

        return $query;
    }

    public function findAllByEmpresaId ($id)
    {
        $query = $this->findAllNotDeleted(array(
            'empresa' => $id,
        ));

        try {
            return $query->getResult();
        } catch (NoResultException $e) {
            return array();
        }
    }
}
