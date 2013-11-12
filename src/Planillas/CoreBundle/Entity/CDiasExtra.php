<?php

namespace Planillas\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * CDiasExtra
 *
 * @ORM\Table(name="c_dias_extra")
 * @ORM\Entity(repositoryClass="Planillas\CoreBundle\Entity\Repository\CDiasExtraRepository")
 */
class CDiasExtra
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="date", nullable=false)
     */
    private $fecha;

    /**
     * @var $empleado Planillas/CoreBundle/Entity/CEmpleado
     *
     * @ORM\ManyToOne(targetEntity="Planillas\CoreBundle\Entity\CEmpleado", inversedBy="incapacidades")
     */
    private $empleado;



    public function getJson()
    {
        $obj= new \stdClass();
        $obj->id=$this->id;
        $obj->fecha=$this->fecha->format('Y-m-d');

        $obj->empleado=$this->empleado->getId();



        return $obj;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

   /**
     * Set empleado
     *
     * @param \Planillas\CoreBundle\Entity\CEmpleado $empleado
     * @return CDiasExtra
     */
    public function setEmpleado(\Planillas\CoreBundle\Entity\CEmpleado $empleado = null)
    {
        $this->empleado = $empleado;
    
        return $this;
    }

    /**
     * Get empleado
     *
     * @return \Planillas\CoreBundle\Entity\CEmpleado 
     */
    public function getEmpleado()
    {
        return $this->empleado;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return CDiasExtra
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;
    
        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime 
     */
    public function getFecha()
    {
        return $this->fecha;
    }
}