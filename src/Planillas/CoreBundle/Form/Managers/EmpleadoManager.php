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
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\HttpFoundation\File\UploadedFile;

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

    public function uploadFoto(CEmpleado $entity, UploadedFile $file)
    {
        try {
            $dir = $this->getAbsolutaRuta() . 'employee/fotos';
            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
            }
            $file->move($dir.'/',$file->getClientOriginalName());
            $entity->setFoto($file->getClientOriginalName());
            $this->getManager()->persist($entity);
            $this->getManager()->flush($entity);
            return $entity;
        } catch (Exception $e) {
            return false ;//$e->getMessage();

        }
    }

    public function getAbsolutaRuta()
    {
        return __DIR__ . '/../../../../../web/';
    }

}