<?php

namespace Planillas\EstructuraBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class EmpresaRepository extends EntityRepository
{
    public function findAllNotDeleted()
    {
        $query = $this->_em->createQuery('SELECT e FROM PlanillasEstructuraBundle:Empresa e ORDER BY e.id DESC');
        return $query;
    }
} 