<?php
/**
 * Created by PhpStorm.
 * User: cinfante
 * Date: 06/04/14
 * Time: 02:56 PM
 */

namespace Planillas\EstructuraBundle\Entity\Repository;


class PuestoRepository extends AbstractRepository
{
    public function findAllNotDeleted ($filters = array())
    {
        $dql = "SELECT p FROM PlanillasEstructuraBundle:Puesto p";

        $this->addParamsToDql($dql, 'p', $filters);

        $dql.=" ORDER BY p.id DESC";

        $query = $this->_em->createQuery($dql);
        $this->addParamsToQuery($query, $filters);

        return $query;
    }
} 