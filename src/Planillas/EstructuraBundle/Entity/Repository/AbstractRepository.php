<?php

namespace Planillas\EstructuraBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

class AbstractRepository extends EntityRepository
{
    protected function addParamsToDql($dql, $alias, $filters = array())
    {
        if (count($filters)) {
            $flag = false;
            foreach ($filters as $key => $value) {
                $dql.= ($flag) ? ' WHERE' : ' AND';
                $dql.= sprintf(' %s.%s=:%s',$alias,$key,$key);
            }
        }

        return $dql;
    }

    protected function addParamsToQuery(Query $query, $filters = array())
    {
        $ready_filters = array();
        foreach ($filters as $key => $value) {
            if (is_object($value) && $value instanceof EntityEstructuraInstance) {
                $ready_filters[$key] = $value->getId();
            } else {
                $ready_filters[$key] = $value;
            }
        }
        if (count($filters)) {
            $query->setParameters($ready_filters);
        }

        return $query;
    }
}
