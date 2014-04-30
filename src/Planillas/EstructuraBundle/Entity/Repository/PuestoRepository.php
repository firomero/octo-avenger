<?php
/**
 * Created by PhpStorm.
 * User: cinfante
 * Date: 06/04/14
 * Time: 02:56 PM
 */

namespace Planillas\EstructuraBundle\Entity\Repository;

use Doctrine\ORM\NoResultException;

class PuestoRepository extends AbstractRepository
{
    public function findAllNotDeleted ($filters = array())
    {
        $dql = "SELECT p FROM PlanillasEstructuraBundle:Puesto p";

        $dql = $this->addParamsToDql($dql, 'p', $filters);

        $dql.=" ORDER BY p.id DESC";

        $query = $this->_em->createQuery($dql);
        $query = $this->addParamsToQuery($query, $filters);

        return $query;
    }

    public function findAllByTurnoId($id)
    {
        $query = $this->findAllNotDeleted(array(
            'turno' => $id,
        ));

        try {
            return $query->getResult();
        } catch (NoResultException $e) {
            return array();
        }
    }
}
