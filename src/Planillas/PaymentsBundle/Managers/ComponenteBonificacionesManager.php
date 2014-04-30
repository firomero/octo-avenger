<?php
/**
 * Created by PhpStorm.
 * User: cinfante
 * Date: 30/04/14
 * Time: 03:55 AM
 */

namespace Planillas\PaymentsBundle\Managers;


use Doctrine\ORM\EntityManager;
use Planillas\EntidadesBundle\Entity\EComponentesSalariales;
use Symfony\Bridge\Monolog\Logger;

class ComponenteBonificacionesManager
{
    /**
     * @var $em \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * @var $logger \Symfony\Bridge\Monolog\Logger
     */
    private $logger;

    public function __construct(EntityManager $em, Logger $logger)
    {
        $this->em = $em;
        $this->logger = $logger;
    }

    public function createBonificacion($data)
    {
        $entity = new EComponentesSalariales();
        $entity->setFecha($data['fechaVencimiento']);
        $entity->setEmpleado($data['empleado']);
        $entity->setMontoTotal($data['monto']);
        $entity->setPermanente($data['permanente']);
        $entity->setDescripcion($data['descripcion']);
        $entity->setComponente(1);
        $entity->setCantidad(null);
        $entity->setPagado(false);

        try {
            $this->em->persist($entity);
            $this->em->flush();

            return true;
        } catch (\Exception $e) {
            $this->logger->addCritical(sprintf('Ha ocurrido un error persistiendo bonificación. Detalles: %s',
                $e->getMessage()));
            return false;
        }
    }
} 