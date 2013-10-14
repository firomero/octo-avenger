<?php
/**
 * Created by JetBrains PhpStorm.
 * User: backkdoor
 * Date: 29/09/13
 * Time: 10:32
 * To change this template use File | Settings | File Templates.
 */

namespace Planillas\EntidadesBundle\Form\Managers;

use Planillas\CoreBundle\Entity\CEmpleado;
use Doctrine\ORM\EntityManager;
use Proxies\__CG__\Planillas\EntidadesBundle\Entity\EDomicilio;

/**
 * Class EmpleadoManager Clase que maneja lo relacionado con el empleado
 * @package Planillas\EntidadesBundle\Form\Managers
 */
class DomicilioManager
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
     * Metodo que salva un localizaciones en la base de datos
     * @param EDomicilio $entity
     * @return EDomicilio
     */
    public function  save(EDomicilio $entity)
    {
        if ($entity instanceof EDomicilio) {
            $this->getManager()->persist($entity);
            $this->getManager()->flush();
            return $entity;
        }


    }

}