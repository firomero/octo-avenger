<?php
/**
 * Created by JetBrains PhpStorm.
 * User: cinfante
 * Date: 06/07/13
 * Time: 09:11 AM
 * To change this template use File | Settings | File Templates.
 */

namespace Planillas\CoreBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class CHorarioRepository extends EntityRepository
{


    public function filterHorario($filtros = array())
    {
        try {


            $sql = "SELECT s  FROM PlanillasCoreBundle:Chorario s";
          
            if (isset($filtros['titulo'])) {
                $sql .= ($case == true) ? " AND " : " WHERE ";
                $sql .= ' s.titulo = '. $filtros['titulo'] ;
                $case = true;

            }

           


            $sql .= ' ORDER BY s.id DESC';
            $query = $this->_em->createQuery($sql);
            ///print_r($query->getDQL());exit;
            return $query->getResult();
        } catch (NoResultException $e) {
            return array();
        }
    }

}