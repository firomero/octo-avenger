<?php
/**
 * Created by JetBrains PhpStorm.
 * User: backkdoor
 * Date: 29/09/13
 * Time: 10:32
 * To change this template use File | Settings | File Templates.
 */

namespace Planillas\CoreBundle\Form\Managers;

use Planillas\CoreBundle\Entity\CEmpleado;
use Doctrine\ORM\EntityManager;

/**
 * Class EmpleadoManager Clase que maneja lo relacionado con el empleado
 * @package Planillas\CoreBundle\Form\Managers
 */
class EmpleadoManager
{

    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function  getManager()
    {
        return $this->em;
    }

    /**
     * Metodo que salva un empleado en la base de datos
     * @param CEmpleado $entity
     * @return CEmpleado
     */
    public function  save(CEmpleado $entity)
    {
        if ($entity instanceof CEmpleado) {
            $this->getManager()->persist($entity);
            $this->getManager()->flush();
            return $entity;
        }

    }

}